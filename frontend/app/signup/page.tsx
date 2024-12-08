'use client'

import { useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import { useUser } from '../domain/user/userHooks'
import { useTranslation } from '../hooks/useTranslation'
import { TermsModal } from '../components/TermsModal'

export default function SignUp() {
  const [firstname, setFirstname] = useState('')
  const [lastname, setLastname] = useState('')
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [confirmPassword, setConfirmPassword] = useState('')
  const [consentGiven, setConsentGiven] = useState(false)
  const [isTermsModalOpen, setIsTermsModalOpen] = useState(false)
  const { createUser, loading, error } = useUser()
  const router = useRouter()
  const { t } = useTranslation()

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    if (password !== confirmPassword) {
      alert(t('signup.passwordMismatch'))
      return
    }
    const success = await createUser({ firstname, lastname, email, password, consentGiven })
    if (success) {
      router.push('/signin')
    }
  }

  return (
      <div className="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-background">
        <div className="max-w-md w-full space-y-8">
          <div className="text-center">
            <h1 className="text-3xl font-extrabold text-foreground">{t('signup.title')}</h1>
          </div>
          <form onSubmit={handleSubmit} className="mt-8 space-y-6 neomorphic p-8 rounded-lg">
            <div className="rounded-md space-y-4">
              <div>
                <label htmlFor="firstname" className="sr-only">{t('signup.firstname')}</label>
                <input
                    id="firstname"
                    name="firstname"
                    type="text"
                    required
                    value={firstname}
                    onChange={(e) => setFirstname(e.target.value)}
                    className="neomorphic-input w-full px-3 py-2 text-foreground"
                    placeholder={t('signup.firstname')}
                />
              </div>
              <div>
                <label htmlFor="lastname" className="sr-only">{t('signup.lastname')}</label>
                <input
                    id="lastname"
                    name="lastname"
                    type="text"
                    required
                    value={lastname}
                    onChange={(e) => setLastname(e.target.value)}
                    className="neomorphic-input w-full px-3 py-2 text-foreground"
                    placeholder={t('signup.lastname')}
                />
              </div>
              <div>
                <label htmlFor="email" className="sr-only">{t('signup.email')}</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    autoComplete="email"
                    required
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="neomorphic-input w-full px-3 py-2 text-foreground"
                    placeholder={t('signup.email')}
                />
              </div>
              <div>
                <label htmlFor="password" className="sr-only">{t('signup.password')}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autoComplete="new-password"
                    required
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="neomorphic-input w-full px-3 py-2 text-foreground"
                    placeholder={t('signup.password')}
                />
              </div>
              <div>
                <label htmlFor="confirmPassword" className="sr-only">{t('signup.confirmPassword')}</label>
                <input
                    id="confirmPassword"
                    name="confirmPassword"
                    type="password"
                    autoComplete="new-password"
                    required
                    value={confirmPassword}
                    onChange={(e) => setConfirmPassword(e.target.value)}
                    className="neomorphic-input w-full px-3 py-2 text-foreground"
                    placeholder={t('signup.confirmPassword')}
                />
              </div>
            </div>

            <div className="flex items-center">
              <input
                  id="consentGiven"
                  name="consentGiven"
                  type="checkbox"
                  checked={consentGiven}
                  onChange={(e) => setConsentGiven(e.target.checked)}
                  className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                  required
              />
              <label htmlFor="consentGiven" className="ml-2 block text-sm text-foreground">
                {t('signup.consentPart1')}{' '}
                <button
                    type="button"
                    onClick={() => setIsTermsModalOpen(true)}
                    className="font-medium text-primary hover:text-primary-dark"
                >
                  {t('signup.termsAndConditions')}
                </button>
              </label>
            </div>

            {error && <p className="text-red-500 text-sm mt-2">{error}</p>}

            <div>
              <button
                  type="submit"
                  disabled={loading}
                  className="group relative w-full flex justify-center py-2 px-4 neomorphic-button text-primary hover:text-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
              >
                {loading ? t('signup.signingUp') : t('signup.signUp')}
              </button>
            </div>
          </form>
          <p className="mt-2 text-center text-sm text-foreground">
            {t('signup.alreadyHaveAccount')}{' '}
            <Link href="/signin" className="font-medium text-primary hover:text-primary-dark">
              {t('signup.signIn')}
            </Link>
          </p>
        </div>
        <TermsModal isOpen={isTermsModalOpen} onClose={() => setIsTermsModalOpen(false)} />
      </div>
  )
}
