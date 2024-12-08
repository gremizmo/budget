'use client'

import { useTranslation } from '../hooks/useTranslation'
import { Modal } from './Modal'

interface TermsModalProps {
    isOpen: boolean;
    onClose: () => void;
}

export function TermsModal({ isOpen, onClose }: TermsModalProps) {
    const { t } = useTranslation()

    return (
        <Modal isOpen={isOpen} onClose={onClose}>
            <h2 className="text-2xl font-bold mb-4">{t('terms.title')}</h2>
            <div className="space-y-6 text-gray-700">
                <section>
                    <h3 className="text-xl font-semibold mb-3">{t('terms.section1.title')}</h3>
                    <p>{t('terms.section1.content')}</p>
                </section>
                <section>
                    <h3 className="text-xl font-semibold mb-3">{t('terms.section2.title')}</h3>
                    <p>{t('terms.section2.content')}</p>
                </section>
                <section>
                    <h3 className="text-xl font-semibold mb-3">{t('terms.section3.title')}</h3>
                    <p>{t('terms.section3.content')}</p>
                </section>
                <section>
                    <h3 className="text-xl font-semibold mb-3">{t('terms.section4.title')}</h3>
                    <p>{t('terms.section4.content')}</p>
                </section>
                <section>
                    <h3 className="text-xl font-semibold mb-3">{t('terms.section5.title')}</h3>
                    <p>{t('terms.section5.content')}</p>
                </section>
            </div>
        </Modal>
    )
}
