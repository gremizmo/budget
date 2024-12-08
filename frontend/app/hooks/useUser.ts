'use client'

import { useState } from 'react'
import { useAppContext } from '../providers'
import { api } from '../services/api'
import { v4 as uuidv4 } from 'uuid'

export function useUser() {
  const { state, setState } = useAppContext()
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const createUser = async (userData: any) => {
    setLoading(true)
    setError(null)
    try {
      const userId = uuidv4()
      const user = await api.createUser({ ...userData, id: userId })
      setState(prev => ({ ...prev, user }))
    } catch (err) {
      setError('Failed to create user')
    } finally {
      setLoading(false)
    }
  }

  const editUser = async (userData: any) => {
    if (!state.user) return
    setLoading(true)
    setError(null)
    try {
      const user = await api.editUser(state.user.id, userData)
      setState(prev => ({ ...prev, user }))
    } catch (err) {
      setError('Failed to edit user')
    } finally {
      setLoading(false)
    }
  }

  const requestPasswordReset = async (email: string) => {
    setLoading(true)
    setError(null)
    try {
      await api.requestPasswordReset(email)
    } catch (err) {
      setError('Failed to request password reset')
    } finally {
      setLoading(false)
    }
  }

  const resetPassword = async (token: string, newPassword: string) => {
    setLoading(true)
    setError(null)
    try {
      await api.resetPassword(token, newPassword)
    } catch (err) {
      setError('Failed to reset password')
    } finally {
      setLoading(false)
    }
  }

  const changePassword = async (oldPassword: string, newPassword: string) => {
    if (!state.user) return
    setLoading(true)
    setError(null)
    try {
      await api.changePassword(state.user.id, oldPassword, newPassword)
    } catch (err) {
      setError('Failed to change password')
    } finally {
      setLoading(false)
    }
  }

  return {
    user: state.user,
    loading,
    error,
    createUser,
    editUser,
    requestPasswordReset,
    resetPassword,
    changePassword,
  }
}

