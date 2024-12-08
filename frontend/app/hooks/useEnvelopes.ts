'use client'

import { useState } from 'react'
import { useAppContext } from '../providers'
import { api } from '../services/api'
import { v4 as uuidv4 } from 'uuid'

export function useEnvelopes() {
  const { state, setState } = useAppContext()
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const createEnvelope = async (name: string) => {
    setLoading(true)
    setError(null)
    try {
      const envelopeId = uuidv4()
      const envelope = await api.createEnvelope({ id: envelopeId, name, balance: 0 })
      setState(prev => ({ ...prev, envelopes: [...prev.envelopes, envelope] }))
    } catch (err) {
      setError('Failed to create envelope')
    } finally {
      setLoading(false)
    }
  }

  const creditEnvelope = async (envelopeId: string, amount: number) => {
    setLoading(true)
    setError(null)
    try {
      const updatedEnvelope = await api.creditEnvelope(envelopeId, amount)
      setState(prev => ({
        ...prev,
        envelopes: prev.envelopes.map(env => env.id === envelopeId ? updatedEnvelope : env)
      }))
    } catch (err) {
      setError('Failed to credit envelope')
    } finally {
      setLoading(false)
    }
  }

  const debitEnvelope = async (envelopeId: string, amount: number) => {
    setLoading(true)
    setError(null)
    try {
      const updatedEnvelope = await api.debitEnvelope(envelopeId, amount)
      setState(prev => ({
        ...prev,
        envelopes: prev.envelopes.map(env => env.id === envelopeId ? updatedEnvelope : env)
      }))
    } catch (err) {
      setError('Failed to debit envelope')
    } finally {
      setLoading(false)
    }
  }

  const deleteEnvelope = async (envelopeId: string) => {
    setLoading(true)
    setError(null)
    try {
      await api.deleteEnvelope(envelopeId)
      setState(prev => ({
        ...prev,
        envelopes: prev.envelopes.filter(env => env.id !== envelopeId)
      }))
    } catch (err) {
      setError('Failed to delete envelope')
    } finally {
      setLoading(false)
    }
  }

  const listEnvelopes = async () => {
    setLoading(true)
    setError(null)
    try {
      const envelopes = await api.listEnvelopes()
      setState(prev => ({ ...prev, envelopes }))
    } catch (err) {
      setError('Failed to list envelopes')
    } finally {
      setLoading(false)
    }
  }

  const updateEnvelopeName = async (envelopeId: string, name: string) => {
    setLoading(true)
    setError(null)
    try {
      const updatedEnvelope = await api.updateEnvelopeName(envelopeId, name)
      setState(prev => ({
        ...prev,
        envelopes: prev.envelopes.map(env => env.id === envelopeId ? updatedEnvelope : env)
      }))
    } catch (err) {
      setError('Failed to update envelope name')
    } finally {
      setLoading(false)
    }
  }

  return {
    envelopes: state.envelopes,
    loading,
    error,
    createEnvelope,
    creditEnvelope,
    debitEnvelope,
    deleteEnvelope,
    listEnvelopes,
    updateEnvelopeName,
  }
}

