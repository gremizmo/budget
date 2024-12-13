import { NextResponse } from 'next/server'
import type { NextRequest } from 'next/server'

export function middleware(request: NextRequest) {
  const token = request.cookies.get('jwtToken')?.value
  const isAuthPage = request.nextUrl.pathname.startsWith('/signin') || request.nextUrl.pathname.startsWith('/signup')
  const isHomePage = request.nextUrl.pathname === '/'

  if (!token && !isAuthPage && !isHomePage) {
    // Redirect to login page if there's no token and it's not already an auth page
    return NextResponse.redirect(new URL('/signin', request.url))
  }

  if (token && isAuthPage) {
    // Redirect to envelopes page if there's a token and user is trying to access an auth page
    return NextResponse.redirect(new URL('/envelopes', request.url))
  }

  return NextResponse.next()
}

export const config = {
  matcher: [
    '/((?!api|_next/static|_next/image|favicon.ico).*)',
  ],
}
