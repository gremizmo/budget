'use client'

import { useState } from 'react'
import { useAppContext } from '../../providers'
import { User, UserState } from './userTypes'
import { api } from '../../infrastructure/api'

export function useUser() {
  const { state, login, logout, setState } = useAppContext()
  const [error, setError] = useState<string | null>(null)

  const signIn = async (email: string, password: string): Promise<boolean> => {
    setError(null)
    try {
      const success = await login(email, password)
      if (!success) {
        setError('Failed to sign in. Please check your credentials and try again.')
      }
      return success
    } catch (err) {
      setError('An error occurred during sign in. Please try again.')
      return false
    }
  }

  const signOut = () => {
    logout()
  }

  const createUser = async (userData: any) => {
    setError(null)
    try {
      await api.commands.createUser(userData)
      return true
    } catch (err) {
      setError('Failed to create user. Please try again.')
      return false
    }
  }

  const hasEnvelopes = async (): Promise<boolean> => {
    try {
      const envelopes = await api.envelopeQueries.listEnvelopes()
      return envelopes.envelopes.length > 0
    } catch (err) {
      console.error('Error checking for envelopes:', err)
      return false
    }
  }

  return {
    user: state.user,
    isAuthenticated: state.isAuthenticated,
    loading: state.loading,
    error,
    signIn,
    signOut,
    createUser,
    hasEnvelopes,
  }
}

