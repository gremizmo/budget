'use client'

import { useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import { authService } from '../services/auth'

export default function ForgotPassword() {
  const [email, setEmail] = useState('')
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [success, setSuccess] = useState(false)
  const router = useRouter()

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError(null)
    try {
      await authService.requestPasswordReset(email)
      setSuccess(true)
    } catch (err) {
      setError('Failed to request password reset. Please try again.')
    } finally {
      setLoading(false)
    }
  }

  if (success) {
    return (
      <div className="max-w-md mx-auto">
        <div className="neomorphic p-8">
          <h1 className="text-3xl font-bold mb-6 text-center">Password Reset Requested</h1>
          <p className="text-center mb-4">
            If an account exists for {email}, you will receive a password reset link shortly.
          </p>
          <Link href="/signin" className="block text-center text-primary hover:underline">
            Return to Sign In
          </Link>
        </div>
      </div>
    )
  }

  return (
    <div className="max-w-md mx-auto">
      <div className="neomorphic p-8">
        <h1 className="text-3xl font-bold mb-6 text-center">Forgot Password</h1>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label htmlFor="email" className="block mb-1 font-medium">Email</label>
            <input
              type="email"
              id="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="w-full p-3 neomorphic-input"
              required
            />
          </div>
          {error && <p className="text-red-500">{error}</p>}
          <button
            type="submit"
            className="w-full py-3 px-4 neomorphic-button text-primary font-semibold"
            disabled={loading}
          >
            {loading ? 'Requesting Reset...' : 'Request Password Reset'}
          </button>
        </form>
        <p className="mt-4 text-center">
          Remember your password? <Link href="/signin" className="text-primary hover:underline">Sign In</Link>
        </p>
      </div>
    </div>
  )
}

