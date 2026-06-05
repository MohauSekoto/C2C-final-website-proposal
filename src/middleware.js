import NextAuth from "next-auth";
import authConfig from "./auth.config.js";

const { auth } = NextAuth(authConfig);

export default auth((req) => {
  const isLoggedIn = !!req.auth;
  const userRole = req.auth?.user?.role;
  const { nextUrl } = req;

  // Protect seller routes
  if (nextUrl.pathname.startsWith("/seller")) {
    if (!isLoggedIn) {
      return Response.redirect(new URL("/login", nextUrl));
    }
    if (userRole !== "seller" && userRole !== "admin") {
      return Response.redirect(new URL("/", nextUrl)); // Unauthorized
    }
  }

  // Protect buyer routes
  if (nextUrl.pathname.startsWith("/buyer")) {
    if (!isLoggedIn) {
      return Response.redirect(new URL("/login", nextUrl));
    }
    if (userRole !== "buyer" && userRole !== "admin") {
      return Response.redirect(new URL("/", nextUrl)); // Unauthorized
    }
  }

  return null;
});

export const config = {
  matcher: ['/((?!api|_next/static|_next/image|favicon.ico).*)'],
};
