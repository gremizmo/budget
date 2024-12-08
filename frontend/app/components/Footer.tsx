'use client'

import Link from 'next/link'
import { useTranslation } from '../hooks/useTranslation'
import { Github, ChevronUp } from 'lucide-react'
import { useState, useEffect } from 'react'

export default function Footer() {
    const { t } = useTranslation()
    const [showScrollTop, setShowScrollTop] = useState(false)

    useEffect(() => {
        const handleScroll = () => {
            setShowScrollTop(window.pageYOffset > 300)
        }

        window.addEventListener('scroll', handleScroll)
        return () => window.removeEventListener('scroll', handleScroll)
    }, [])

    const scrollToTop = () => {
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }

    return (
        <footer className="bg-gray-100 mt-12 py-8 px-4 sm:px-6 lg:px-8">
            <div className="max-w-7xl mx-auto">
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div className="text-center sm:text-left">
                        <h3 className="text-lg font-semibold mb-4">{t('footer.companyName')}</h3>
                        <p className="text-sm text-gray-600">
                            {t('footer.description')}
                        </p>
                    </div>
                    <div className="text-center sm:text-left">
                        <h3 className="text-lg font-semibold mb-4">{t('footer.quickLinks')}</h3>
                        <ul className="space-y-2">
                            <li>
                                <Link href="/" className="text-sm text-gray-600 hover:text-gray-900">
                                    {t('footer.home')}
                                </Link>
                            </li>
                            <li>
                                <Link href="/envelopes" className="text-sm text-gray-600 hover:text-gray-900">
                                    {t('footer.envelopes')}
                                </Link>
                            </li>
                            <li>
                                <Link href="/settings" className="text-sm text-gray-600 hover:text-gray-900">
                                    {t('footer.settings')}
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div className="text-center sm:text-left">
                        <h3 className="text-lg font-semibold mb-4">{t('footer.legal')}</h3>
                        <ul className="space-y-2">
                            <li>
                                <Link href="/terms" className="text-sm text-gray-600 hover:text-gray-900">
                                    {t('footer.termsAndConditions')}
                                </Link>
                            </li>
                            <li>
                                <Link href="/privacy" className="text-sm text-gray-600 hover:text-gray-900">
                                    {t('footer.privacyPolicy')}
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div className="text-center sm:text-left">
                        <h3 className="text-lg font-semibold mb-4">{t('footer.connect')}</h3>
                        <a
                            href="https://github.com/yourusername/your-repo"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="inline-flex items-center text-sm text-gray-600 hover:text-gray-900"
                        >
                            <Github className="w-5 h-5 mr-2" />
                            {t('footer.sourceCode')}
                        </a>
                    </div>
                </div>
                <div className="mt-8 pt-8 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center">
                    <p className="text-sm text-gray-600">
                        © {new Date().getFullYear()} {t('footer.companyName')}. {t('footer.allRightsReserved')}
                    </p>
                    <div className="mt-4 sm:mt-0">
                        <button
                            onClick={scrollToTop}
                            className={`${showScrollTop ? 'opacity-100' : 'opacity-0'} transition-opacity duration-300 bg-primary text-white p-2 rounded-full hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary`}
                            aria-label={t('footer.scrollToTop')}
                        >
                            <ChevronUp className="w-5 h-5" />
                        </button>
                    </div>
                </div>
            </div>
        </footer>
    )
}
