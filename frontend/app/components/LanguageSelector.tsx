'use client'

import { useState } from 'react'
import { Globe } from 'lucide-react'
import { useTranslation } from '../hooks/useTranslation'

const languages = [
    { code: 'en', name: 'English' },
    { code: 'fr', name: 'Français' },
]

export function LanguageSelector({ showText = false, iconSize = 20, className = '' }: { showText?: boolean, iconSize?: number, className?: string }) {
    const [isOpen, setIsOpen] = useState(false)
    const { language, setLanguage, t } = useTranslation()

    const handleLanguageChange = (langCode: string) => {
        setLanguage(langCode)
        setIsOpen(false)
    }

    return (
        <div className="relative">
            <button
                onClick={() => setIsOpen(!isOpen)}
                className={`flex items-center py-2 text-gray-600 hover:text-gray-900 ${className}`}
            >
                <Globe className={`w-${iconSize/4} h-${iconSize/4} mr-2`} />
                <span className={showText ? 'inline' : 'hidden md:inline'}>{t('nav.language')}</span>
            </button>
            {isOpen && (
                <div className="absolute left-0 md:right-0 mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20 neomorphic">
                    {languages.map((lang) => (
                        <button
                            key={lang.code}
                            className={`block px-4 py-2 text-sm w-full text-left ${
                                lang.code === language ? 'bg-gray-100 text-primary' : 'text-gray-700 hover:bg-gray-100'
                            }`}
                            onClick={() => handleLanguageChange(lang.code)}
                        >
                            {lang.name}
                        </button>
                    ))}
                </div>
            )}
        </div>
    )
}
