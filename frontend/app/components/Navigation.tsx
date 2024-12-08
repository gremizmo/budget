'use client'

import { useState } from 'react'
import Link from 'next/link'
import { usePathname } from 'next/navigation'
import { useUser } from '../domain/user/userHooks'
import { Home, Menu, X, PieChart, Mail, User } from 'lucide-react'
import { useTranslation } from '../hooks/useTranslation'
import { LanguageSelector } from './LanguageSelector'

export default function Navigation() {
    const pathname = usePathname()
    const { user } = useUser()
    const { t } = useTranslation()
    const [isMenuOpen, setIsMenuOpen] = useState(false)

    const toggleMenu = () => setIsMenuOpen(!isMenuOpen)

    const NavLink = ({ href, children, onClick }: { href: string; children: React.ReactNode; onClick?: (e: React.MouseEvent<HTMLAnchorElement>) => void }) => (
        <Link
            href={href}
            className={`block py-2 px-0 md:px-2 ${pathname === href ? 'text-primary font-bold' : 'text-gray-600'}`}
            onClick={(e) => {
                if (onClick) {
                    onClick(e)
                } else {
                    setIsMenuOpen(false)
                }
            }}
        >
            {children}
        </Link>
    )

    return (
        <nav className="bg-white shadow-md mb-8">
            <div className="container mx-auto px-4">
                <div className="flex justify-between items-center py-4">
                    <Link href="/" className="text-xl md:text-2xl font-bold text-primary flex items-center">
                        <Home className="mr-2 h-5 w-5 md:h-6 md:w-6" /> {t('nav.home')}
                    </Link>
                    <div className="md:hidden">
                        <button onClick={toggleMenu} className="text-gray-600 hover:text-gray-900">
                            {isMenuOpen ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
                        </button>
                    </div>
                    <div className={`${isMenuOpen ? 'block' : 'hidden'} md:flex md:items-center md:space-x-4 absolute md:relative top-16 md:top-0 left-0 right-0 bg-white md:bg-transparent shadow-md md:shadow-none z-10 p-4 md:p-0 space-y-2 md:space-y-0`}>
                        <LanguageSelector showText={isMenuOpen} iconSize={16} className="w-full md:w-auto" />
                        {user ? (
                            <>
                                <NavLink href="/dashboard">
                                    <PieChart className="inline-block mr-1 h-4 w-4" />
                                    {t('nav.dashboard')}
                                </NavLink>
                                <NavLink href="/envelopes" onClick={(e) => {
                                    if (pathname === '/envelopes') {
                                        e.preventDefault();
                                    }
                                    setIsMenuOpen(false);
                                }}>
                                    <Mail className="inline-block mr-1 h-4 w-4" />
                                    {t('nav.envelopes')}
                                </NavLink>
                                <NavLink href="/settings">
                                    <User className="inline-block mr-1 h-4 w-4" />
                                    {t('nav.settings')}
                                </NavLink>
                            </>
                        ) : (
                            <>
                                <NavLink href="/signin">{t('nav.signIn')}</NavLink>
                                <NavLink href="/signup">{t('nav.signUp')}</NavLink>
                            </>
                        )}
                    </div>
                </div>
            </div>
        </nav>
    )
}
