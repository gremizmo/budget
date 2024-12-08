import { motion } from 'framer-motion'
import { useTranslation } from '../hooks/useTranslation'

interface DeleteConfirmationModalProps {
    isOpen: boolean
    onClose: () => void
    onConfirm: () => void
    envelopeName: string
}

export function DeleteConfirmationModal({ isOpen, onClose, onConfirm, envelopeName }: DeleteConfirmationModalProps) {
    const { t } = useTranslation()

    if (!isOpen) return null

    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        >
            <motion.div
                initial={{ scale: 0.8, opacity: 0 }}
                animate={{ scale: 1, opacity: 1 }}
                exit={{ scale: 0.8, opacity: 0 }}
                className="neomorphic p-4 md:p-6 w-full max-w-sm bg-white rounded-lg"
            >
                <h2 className="text-xl md:text-2xl font-bold mb-4">{t('envelopes.deleteEnvelope')}</h2>
                <p className="mb-6 text-sm md:text-base">
                    {t('envelopes.deleteConfirmation').replace('{name}', envelopeName)}
                </p>
                <div className="flex justify-between">
                    <button onClick={onConfirm} className="py-2 px-4 neomorphic-button text-red-500 text-sm md:text-base">{t('envelopes.delete')}</button>
                    <button onClick={onClose} className="py-2 px-4 neomorphic-button text-primary text-sm md:text-base">{t('envelopes.cancel')}</button>
                </div>
            </motion.div>
        </motion.div>
    )
}
