import { NextResponse } from 'next/server';
import { db } from '../../../lib/db/index.js';
import { products } from '../../../lib/db/schema.js';
import { eq } from 'drizzle-orm';

export async function GET(request) {
  try {
    const { searchParams } = new URL(request.url);
    const category = searchParams.get('category');
    
    let query = db.select().from(products).where(eq(products.status, 'active'));
    
    // In a real app, we would join with categories to filter by slug.
    // For now, this just returns all active products.
    const allProducts = await query;
    
    return NextResponse.json({ success: true, products: allProducts });
  } catch (error) {
    console.error("Products API Error:", error);
    return NextResponse.json({ error: "Internal Server Error" }, { status: 500 });
  }
}
