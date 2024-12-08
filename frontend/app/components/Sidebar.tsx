'use client'

import Link from 'next/link'
import { usePathname } from 'next/navigation'

const navItems = [
  { href: '/', label: 'Dashboard' },
  { href: '/envelopes', label: 'Envelopes' },
  { href: '/profile', label: 'Profile' },
]

export default function Sidebar() {
  const pathname = usePathname()

  return (
    <aside className="w-64 bg-white shadow-md">
      <nav className="mt-5">
        <ul>
          {navItems.map((item) => (
            <li key={item.href} className="mb-2">
              <Link href={item.href}>
                <span className={`block p-4 ${pathname === item.href ? 'neomorphic-inset text-primary' : 'neomorphic-button'} mx-2`}>
                  {item.label}
                </span>
              </Link>
            </li>
          ))}
        </ul>
      </nav>
    </aside>
  )
}

