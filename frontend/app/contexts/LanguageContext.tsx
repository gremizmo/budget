'use client'

import React, { createContext, useState, useContext, useEffect, ReactNode } from 'react'
import Cookies from 'js-cookie'

type LanguageContextType = {
    language: string
    setLanguage: (lang: string) => void
}

const LanguageContext = createContext<LanguageContextType | undefined>(undefined)

export function LanguageProvider({ children }: { children: ReactNode }) {
    const [language, setLanguage] = useState('en')

    useEffect(() => {
        const storedLang = Cookies.get('NEXT_LOCALE')
        if (storedLang) {
            setLanguage(storedLang)
        }
    }, [])

    const changeLanguage = (lang: string) => {
        setLanguage(lang)
        Cookies.set('NEXT_LOCALE', lang, { expires: 365 })
    }

    return (
        <LanguageContext.Provider value={{ language, setLanguage: changeLanguage }}>
            {children}
        </LanguageContext.Provider>
    )
}

export function useLanguage() {
    const context = useContext(LanguageContext)
    if (context === undefined) {
        throw new Error('useLanguage must be used within a LanguageProvider')
    }
    return context
}
