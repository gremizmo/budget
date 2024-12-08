'use client'

import { useTranslation } from '../hooks/useTranslation'

export default function TermsAndConditions() {
    const { t } = useTranslation()

    return (
        <div className="max-w-4xl mx-auto px-4 py-8">
            <h1 className="text-3xl font-bold mb-6">{t('terms.title')}</h1>
            <div className="space-y-6 text-gray-700">
                <section>
                    <h2 className="text-2xl font-semibold mb-3">{t('terms.section1.title')}</h2>
                    <p>{t('terms.section1.content')}</p>
                </section>
                <section>
                    <h2 className="text-2xl font-semibold mb-3">{t('terms.section2.title')}</h2>
                    <p>{t('terms.section2.content')}</p>
                </section>
                <section>
                    <h2 className="text-2xl font-semibold mb-3">{t('terms.section3.title')}</h2>
                    <p>{t('terms.section3.content')}</p>
                </section>
                <section>
                    <h2 className="text-2xl font-semibold mb-3">{t('terms.section4.title')}</h2>
                    <p>{t('terms.section4.content')}</p>
                </section>
                <section>
                    <h2 className="text-2xl font-semibold mb-3">{t('terms.section5.title')}</h2>
                    <p>{t('terms.section5.content')}</p>
                </section>
            </div>
        </div>
    )
}
