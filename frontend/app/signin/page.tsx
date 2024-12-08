'use client'

import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import { useUser } from '../domain/user/userHooks'
import { useTranslation } from '../hooks/useTranslation'

export default function SignIn() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const { signIn, isAuthenticated, loading, error } = useUser()
  const router = useRouter()
  const { t } = useTranslation()

  useEffect(() => {
    if (isAuthenticated) {
      router.push('/dashboard')
    }
  }, [isAuthenticated, router])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    const success = await signIn(email, password)
    if (success) {
      router.push('/dashboard')
    }
  }

  if (loading) {
    return <div className="flex justify-center items-center h-screen">
      <div className="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-primary"></div>
    </div>
  }

  if (isAuthenticated) {
    return null
  }

  return (
      <div className="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-background">
        <div className="max-w-md w-full space-y-8">
          <div className="text-center">
            <h1 className="text-3xl font-extrabold text-foreground">{t('signin.title')}</h1>
          </div>
          <form onSubmit={handleSubmit} className="mt-8 space-y-6 neomorphic p-8 rounded-lg">
            <div className="rounded-md space-y-4">
              <div>
                <label htmlFor="email" className="sr-only">{t('signin.email')}</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    autoComplete="email"
                    required
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="neomorphic-input w-full px-3 py-2 text-foreground"
                    placeholder={t('signin.email')}
                />
              </div>
              <div>
                <label htmlFor="password" className="sr-only">{t('signin.password')}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autoComplete="current-password"
                    required
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="neomorphic-input w-full px-3 py-2 text-foreground"
                    placeholder={t('signin.password')}
                />
              </div>
            </div>

            {error && <p className="text-red-500 text-sm">{error}</p>}

            <div className="flex items-center justify-between">
              <div className="text-sm">
                <Link href="/forgot-password" className="font-medium text-primary hover:text-primary-dark">
                  {t('signin.forgotPassword')}
                </Link>
              </div>
            </div>

            <div>
              <button
                  type="submit"
                  className="group relative w-full flex justify-center py-2 px-4 neomorphic-button text-primary hover:text-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
              >
                {t('signin.signIn')}
              </button>
            </div>
          </form>
          <p className="mt-2 text-center text-sm text-foreground">
            {t('signin.dontHaveAccount')}{' '}
            <Link href="/signup" className="font-medium text-primary hover:text-primary-dark">
              {t('signin.signUp')}
            </Link>
          </p>
        </div>
      </div>
  )
}
