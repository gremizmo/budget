'use client'

import { useState, useEffect } from 'react'
import { useUser } from '../domain/user/userHooks'
import { useTranslation } from '../hooks/useTranslation'
import { api } from '../infrastructure/api'

interface UserProfile {
  email: string;
  firstname: string;
  lastname: string;
}

export default function SettingsPage() {
  const { user } = useUser()
  const { t } = useTranslation()
  const [profile, setProfile] = useState<UserProfile | null>(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)

  useEffect(() => {
    const fetchUserProfile = async () => {
      try {
        const userData = await api.queries.getCurrentUser()
        setProfile({
          email: userData.email,
          firstname: userData.firstname,
          lastname: userData.lastname,
        })
      } catch (err) {
        setError(t('settings.fetchError'))
      } finally {
        setLoading(false)
      }
    }

    fetchUserProfile()
  }, [t])

  if (loading) {
    return <div className="flex justify-center items-center h-screen">
      <div className="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-primary"></div>
    </div>
  }

  if (error) {
    return <div className="text-center text-red-500 mt-8">{error}</div>
  }

  if (!profile) {
    return <div className="text-center mt-8">{t('settings.noProfile')}</div>
  }

  return (
      <div className="container mx-auto px-4 py-8">
        <h1 className="text-3xl font-bold mb-6">{t('settings.title')}</h1>
        <div className="bg-white shadow-md rounded-lg p-6 neomorphic">
          <h2 className="text-2xl font-semibold mb-4">{t('settings.profile')}</h2>
          <div className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700">{t('settings.email')}</label>
              <p className="mt-1 text-lg">{profile.email}</p>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">{t('settings.firstName')}</label>
              <p className="mt-1 text-lg">{profile.firstname}</p>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">{t('settings.lastName')}</label>
              <p className="mt-1 text-lg">{profile.lastname}</p>
            </div>
          </div>
        </div>
      </div>
  )
}
