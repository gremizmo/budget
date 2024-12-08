'use client'

import { useState } from 'react'
import { useEnvelopes } from '../domain/envelope/envelopeHooks'
import { PlusCircle, Trash2, Edit2, Loader2, Check, X } from 'lucide-react'
import { PieChart, Pie, Cell, ResponsiveContainer } from 'recharts'
import { motion, AnimatePresence } from 'framer-motion'
import { DeleteConfirmationModal } from './DeleteConfirmationModal'
import { useTranslation } from '../hooks/useTranslation'

export default function EnvelopeManagement() {
    const { envelopesData, createEnvelope, creditEnvelope, debitEnvelope, deleteEnvelope, updateEnvelopeName, loading, error } = useEnvelopes()
    const [amounts, setAmounts] = useState<{ [key: string]: string }>({})
    const [isCreating, setIsCreating] = useState(false)
    const [newEnvelopeName, setNewEnvelopeName] = useState('')
    const [newEnvelopeTarget, setNewEnvelopeTarget] = useState('')
    const [pendingActions, setPendingActions] = useState<{ [key: string]: string }>({})
    const [deleteModalOpen, setDeleteModalOpen] = useState(false)
    const [envelopeToDelete, setEnvelopeToDelete] = useState<{ id: string, name: string } | null>(null)
    const [editingName, setEditingName] = useState<{ id: string, name: string } | null>(null)
    const { t } = useTranslation()

    const handleAmountChange = (id: string, value: string) => {
        setAmounts(prev => ({ ...prev, [id]: value }))
    }

    const handleCreditEnvelope = (id: string) => {
        if (amounts[id]) {
            creditEnvelope(id, amounts[id])
            handleAmountChange(id, '')
        }
    }

    const handleDebitEnvelope = (id: string) => {
        if (amounts[id]) {
            debitEnvelope(id, amounts[id])
            handleAmountChange(id, '')
        }
    }

    const handleCreateEnvelope = async () => {
        if (newEnvelopeName && newEnvelopeTarget) {
            await createEnvelope(newEnvelopeName, newEnvelopeTarget)
            setIsCreating(false)
            setNewEnvelopeName('')
            setNewEnvelopeTarget('')
        }
    }

    const handleDeleteEnvelope = async () => {
        if (envelopeToDelete) {
            const { id } = envelopeToDelete
            setDeleteModalOpen(false)
            await deleteEnvelope(id)
            setEnvelopeToDelete(null)
        }
    }

    const openDeleteModal = (id: string, name: string) => {
        setEnvelopeToDelete({ id, name })
        setDeleteModalOpen(true)
    }

    const handleStartEditingName = (id: string, currentName: string) => {
        setEditingName({ id, name: currentName })
    }

    const handleNameChange = (newName: string) => {
        if (editingName) {
            setEditingName({ ...editingName, name: newName })
        }
    }

    const handleUpdateEnvelopeName = async () => {
        if (editingName && editingName.name.trim() !== '') {
            const { id, name } = editingName
            setPendingActions(prev => ({ ...prev, [id]: 'updating' }))

            try {
                await updateEnvelopeName(id, name)
            } catch (error) {
                console.error('Failed to update envelope name:', error)
            } finally {
                setPendingActions(prev => {
                    const newPending = { ...prev }
                    delete newPending[id]
                    return newPending
                })
                setEditingName(null)
            }
        }
    }

    const cancelNameEdit = () => {
        setEditingName(null)
    }

    const isEmptyEnvelopes = !envelopesData || envelopesData.envelopes.length === 0

    if (error) return <div className="text-center mt-8 text-red-500">{t('envelopes.error', { error })}</div>

    return (
        <div className="space-y-8">
            <div className="flex justify-between items-center mb-6">
                <h1 className="text-2xl md:text-3xl font-bold">{t('envelopes.title')}</h1>
                <button
                    onClick={() => setIsCreating(true)}
                    className="p-3 neomorphic-button text-primary hover:text-primary-dark transition-colors rounded-full"
                    aria-label={t('envelopes.createNew')}
                >
                    <PlusCircle className="h-6 w-6" />
                </button>
            </div>

            {isEmptyEnvelopes ? (
                <div className="text-center py-12">
                    <p className="text-lg md:text-xl mb-6">{t('envelopes.empty')}</p>
                </div>
            ) : (
                <AnimatePresence initial={false}>
                    <motion.div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        {envelopesData.envelopes.map(envelope => (
                            <motion.div
                                key={envelope.uuid}
                                layout
                                initial={{ opacity: 0, scale: 0.8 }}
                                animate={{ opacity: 1, scale: 1 }}
                                exit={{ opacity: 0, scale: 0.8 }}
                                transition={{ duration: 0.3 }}
                                className={`neomorphic p-4 md:p-6 ${envelope.pending ? 'opacity-70' : ''} ${envelope.deleted ? 'bg-red-100' : ''}`}
                            >
                                <div className="flex items-center justify-between mb-4">
                                    <div className="flex-grow">
                                        {editingName && editingName.id === envelope.uuid ? (
                                            <div className="flex items-center">
                                                <input
                                                    type="text"
                                                    value={editingName.name}
                                                    onChange={(e) => handleNameChange(e.target.value)}
                                                    className="flex-grow p-1 mr-2 neomorphic-input text-lg md:text-xl font-bold"
                                                    autoFocus
                                                />
                                                <button
                                                    onClick={handleUpdateEnvelopeName}
                                                    className="p-1 neomorphic-button text-green-500 mr-1"
                                                    disabled={envelope.pending || !!pendingActions[envelope.uuid]}
                                                >
                                                    <Check className="h-4 w-4 md:h-5 md:w-5" />
                                                </button>
                                                <button
                                                    onClick={cancelNameEdit}
                                                    className="p-1 neomorphic-button text-red-500"
                                                    disabled={envelope.pending || !!pendingActions[envelope.uuid]}
                                                >
                                                    <X className="h-4 w-4 md:h-5 md:w-5" />
                                                </button>
                                            </div>
                                        ) : (
                                            <h3
                                                className="text-lg md:text-xl font-bold cursor-pointer"
                                                onClick={() => handleStartEditingName(envelope.uuid, envelope.name)}
                                            >
                                                {envelope.name}
                                            </h3>
                                        )}
                                    </div>
                                    <div className="flex items-center">
                                        {envelope.pending && <Loader2 className="ml-2 h-4 w-4 animate-spin" />}
                                    </div>
                                </div>
                                <div className="flex justify-between items-center mb-4">
                                    <div>
                                        <p className="text-xl md:text-2xl font-semibold">
                                            ${parseFloat(envelope.currentBudget).toFixed(2)}
                                        </p>
                                        <p className="text-sm text-muted-foreground">
                                            {t('envelopes.of')} ${parseFloat(envelope.targetBudget).toFixed(2)}
                                        </p>
                                    </div>
                                    <div className="w-20 h-20 md:w-24 md:h-24 neomorphic-circle flex items-center justify-center">
                                        <ResponsiveContainer width="100%" height="100%">
                                            <PieChart>
                                                <Pie
                                                    data={[
                                                        { name: 'Used', value: parseFloat(envelope.currentBudget) },
                                                        { name: 'Remaining', value: parseFloat(envelope.targetBudget) - parseFloat(envelope.currentBudget) }
                                                    ]}
                                                    cx="50%"
                                                    cy="50%"
                                                    innerRadius={25}
                                                    outerRadius={35}
                                                    fill="#8884d8"
                                                    dataKey="value"
                                                    strokeWidth={0}
                                                >
                                                    <Cell key="cell-0" fill="#4CAF50" />
                                                    <Cell key="cell-1" fill="#E0E0E0" />
                                                </Pie>
                                            </PieChart>
                                        </ResponsiveContainer>
                                    </div>
                                </div>
                                <div className="space-y-4">
                                    <div className="flex items-center space-x-2">
                                        <input
                                            type="number"
                                            value={amounts[envelope.uuid] || ''}
                                            onChange={(e) => handleAmountChange(envelope.uuid, e.target.value)}
                                            placeholder={t('envelopes.amount')}
                                            className="flex-grow p-2 md:p-3 neomorphic-input text-sm md:text-base"
                                            step="0.01"
                                            disabled={envelope.pending || !!pendingActions[envelope.uuid]}
                                        />
                                        <button
                                            onClick={() => handleCreditEnvelope(envelope.uuid)}
                                            className="p-2 md:p-3 neomorphic-button text-green-500 font-bold"
                                            disabled={envelope.pending || !!pendingActions[envelope.uuid]}
                                        >
                                            +
                                        </button>
                                        <button
                                            onClick={() => handleDebitEnvelope(envelope.uuid)}
                                            className="p-2 md:p-3 neomorphic-button text-red-500 font-bold"
                                            disabled={envelope.pending || !!pendingActions[envelope.uuid]}
                                        >
                                            -
                                        </button>
                                    </div>
                                    <div className="flex justify-end mt-4">
                                        <button
                                            onClick={() => openDeleteModal(envelope.uuid, envelope.name)}
                                            className="p-2 neomorphic-button text-red-500 hover:text-red-600"
                                            disabled={envelope.pending || !!pendingActions[envelope.uuid]}
                                        >
                                            <Trash2 className="h-4 w-4 md:h-5 md:w-5" />
                                        </button>
                                    </div>
                                </div>
                                {envelope.deleted && <p className="text-red-500 mt-2">Deleting...</p>}
                            </motion.div>
                        ))}
                    </motion.div>
                </AnimatePresence>
            )}
            {isCreating && (
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
                        className="neomorphic p-4 md:p-6 w-full max-w-md bg-white rounded-lg"
                    >
                        <h2 className="text-xl md:text-2xl font-bold mb-4">{t('envelopes.createNewEnvelope')}</h2>
                        <input
                            type="text"
                            value={newEnvelopeName}
                            onChange={(e) => setNewEnvelopeName(e.target.value)}
                            placeholder={t('envelopes.envelopeName')}
                            className="w-full p-2 md:p-3 mb-4 neomorphic-input"
                        />
                        <input
                            type="number"
                            value={newEnvelopeTarget}
                            onChange={(e) => setNewEnvelopeTarget(e.target.value)}
                            placeholder={t('envelopes.targetBudget')}
                            className="w-full p-2 md:p-3 mb-4 neomorphic-input"
                            step="0.01"
                        />
                        <div className="flex justify-between">
                            <button onClick={handleCreateEnvelope} className="py-2 px-4 neomorphic-button text-green-500">{t('envelopes.create')}</button>
                            <button onClick={() => setIsCreating(false)} className="py-2 px-4 neomorphic-button text-red-500">{t('envelopes.cancel')}</button>
                        </div>
                    </motion.div>
                </motion.div>
            )}
            <DeleteConfirmationModal
                isOpen={deleteModalOpen}
                onClose={() => setDeleteModalOpen(false)}
                onConfirm={handleDeleteEnvelope}
                envelopeName={envelopeToDelete?.name || ''}
            />
        </div>
    )
}
