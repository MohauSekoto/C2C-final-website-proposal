import { NextResponse } from 'next/server';
import { db } from '../../../lib/db/index.js';
import { orders, orderItems, users } from '../../../lib/db/schema.js';
import { eq } from 'drizzle-orm';
import { auth } from '../../../auth.js';
import crypto from 'crypto';

function generatePayfastSignature(data, passPhrase = null) {
    let pfOutput = "";
    for (let key in data) {
        if (data.hasOwnProperty(key)) {
            if (data[key] !== "") {
                pfOutput += `${key}=${encodeURIComponent(data[key].trim()).replace(/%20/g, "+")}&`;
            }
        }
    }

    let getString = pfOutput.slice(0, -1);
    if (passPhrase !== null) {
        getString += `&passphrase=${encodeURIComponent(passPhrase.trim()).replace(/%20/g, "+")}`;
    }

    return crypto.createHash("md5").update(getString).digest("hex");
}

export async function POST(request) {
  try {
    const session = await auth();
    if (!session || !session.user) {
      return NextResponse.json({ error: "Unauthorized. Please log in to checkout." }, { status: 401 });
    }

    const body = await request.json();
    const { items, shippingAddress, paymentMethod } = body;

    if (!items || items.length === 0) {
      return NextResponse.json({ error: "Cart is empty" }, { status: 400 });
    }

    // Save shipping address to user profile
    if (shippingAddress) {
      await db.update(users)
        .set({ shippingAddress })
        .where(eq(users.id, session.user.id));
    }

    // For MVP, we assume the cart items belong to a single seller.
    const sellerId = items[0].sellerId;
    if (!sellerId) {
      return NextResponse.json({ error: "Invalid product data (missing seller)" }, { status: 400 });
    }
    
    // Calculate totals
    const subtotal = items.reduce((acc, item) => acc + (Number(item.price) * item.quantity), 0);
    const shippingCost = 50.00; // Flat mock shipping rate
    const total = subtotal + shippingCost;
    
    // Commission simulation (10% flat for MVP)
    const commissionRate = 10.00;
    const commissionAmount = subtotal * 0.10;

    const orderNumber = `KB-${Math.floor(100000 + Math.random() * 900000)}`;
    const orderId = crypto.randomUUID();

    // 1. Create Order
    await db.insert(orders).values({
      id: orderId,
      orderNumber,
      buyerId: session.user.id,
      sellerId: sellerId,
      status: "pending_payment",
      subtotal: subtotal.toString(),
      shippingCost: shippingCost.toString(),
      commissionAmount: commissionAmount.toString(),
      commissionRate: commissionRate.toString(),
      total: total.toString(),
      shippingAddress: shippingAddress,
      escrowStatus: "held",
    });

    // 2. Create Order Items
    for (const item of items) {
      await db.insert(orderItems).values({
        id: crypto.randomUUID(),
        orderId: orderId,
        productId: item.id,
        quantity: item.quantity,
        unitPrice: item.price.toString(),
        totalPrice: (Number(item.price) * item.quantity).toString(),
      });
    }

    // 3. Prepare PayFast Data
    const merchantId = process.env.PAYFAST_MERCHANT_ID || '10000100';
    const merchantKey = process.env.PAYFAST_MERCHANT_KEY || '46f0cd694581a';
    const passPhrase = process.env.PAYFAST_PASSPHRASE || 'kasibuy_secret';
    
    const baseUrl = process.env.NEXT_PUBLIC_APP_URL || 'http://localhost:3000';
    const returnUrl = `${baseUrl}/checkout/success`;
    const cancelUrl = `${baseUrl}/checkout`;
    
    // Determine ITN URL based on gateway choice
    const isMock = paymentMethod === 'mock';
    const notifyUrl = isMock 
      ? 'http://localhost:8080/php-services/payments/payfast-itn.php'
      : `${process.env.NGROK_URL}/php-services/payments/payfast-itn.php`;

    if (!isMock && !process.env.NGROK_URL) {
      console.warn("WARNING: NGROK_URL is not set. PayFast ITN will fail.");
    }

    const data = {
      merchant_id: merchantId,
      merchant_key: merchantKey,
      return_url: returnUrl,
      cancel_url: cancelUrl,
      notify_url: notifyUrl,
      name_first: session.user.name ? session.user.name.split(' ')[0] : 'Buyer',
      name_last: session.user.name && session.user.name.split(' ').length > 1 ? session.user.name.split(' ')[1] : 'Name',
      email_address: session.user.email,
      m_payment_id: orderId,
      amount: total.toFixed(2),
      item_name: `KasiBuy Order ${orderNumber}`
    };

    const signature = generatePayfastSignature(data, passPhrase);
    data.signature = signature;

    const targetUrl = isMock 
      ? "http://localhost:8080/html-pages/payment-gateway/index.php"
      : "https://sandbox.payfast.co.za/eng/process";

    return NextResponse.json({
      success: true,
      payfastUrl: targetUrl,
      formData: data
    });

  } catch (error) {
    console.error("Checkout POST Error:", error);
    return NextResponse.json({ error: "Internal Server Error" }, { status: 500 });
  }
}
