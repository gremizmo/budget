'use client'

import { useState } from 'react'
import { useUser } from '../domain/user/userHooks'

export default function UserManagement() {
  const { user, createUser, editUser, requestPasswordReset, resetPassword, changePassword, loading, error } = useUser()
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [name, setName] = useState('')
  const [resetToken, setResetToken] = useState('')

  const handleCreateUser = (e: React.FormEvent) => {
    e.preventDefault()
    createUser({ email, password, name })
  }

  const handleEditUser = (e: React.FormEvent) => {
    e.preventDefault()
    editUser({ name })
  }

  const handleRequestPasswordReset = (e: React.FormEvent) => {
    e.preventDefault()
    requestPasswordReset(email)
  }

  const handleResetPassword = (e: React.FormEvent) => {
    e.preventDefault()
    resetPassword(resetToken, password)
  }

  const handleChangePassword = (e: React.FormEvent) => {
    e.preventDefault()
    changePassword(password, password) // In a real app, you'd have separate fields for old and new password
  }

  if (loading) return <div className="text-center mt-8">Loading...</div>
  if (error) return <div className="text-center mt-8 text-red-500">Error: {error}</div>

  if (user) {
    return (
      <div className="neomorphic p-8 max-w-md mx-auto">
        <h2 className="text-2xl font-bold mb-6 text-center">User Management</h2>
        <form onSubmit={handleEditUser} className="mb-6 space-y-4">
          <input
            type="text"
            value={name}
            onChange={(e) => setName(e.target.value)}
            placeholder="New name"
            className="neomorphic-input w-full p-3"
          />
          <button type="submit" className="neomorphic-button w-full p-3 text-primary font-semibold">Edit User</button>
        </form>
        <form onSubmit={handleChangePassword} className="space-y-4">
          <input
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            placeholder="New password"
            className="neomorphic-input w-full p-3"
          />
          <button type="submit" className="neomorphic-button w-full p-3 text-primary font-semibold">Change Password</button>
        </form>
      </div>
    )
  }

  return (
    <div className="neomorphic p-8 max-w-md mx-auto">
      <h2 className="text-2xl font-bold mb-6 text-center">User Management</h2>
      <form onSubmit={handleCreateUser} className="mb-6 space-y-4">
        <input
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          placeholder="Email"
          className="neomorphic-input w-full p-3"
        />
        <input
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          placeholder="Password"
          className="neomorphic-input w-full p-3"
        />
        <input
          type="text"
          value={name}
          onChange={(e) => setName(e.target.value)}
          placeholder="Name"
          className="neomorphic-input w-full p-3"
        />
        <button type="submit" className="neomorphic-button w-full p-3 text-primary font-semibold">Create User</button>
      </form>
      <form onSubmit={handleRequestPasswordReset} className="mb-6 space-y-4">
        <input
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          placeholder="Email"
          className="neomorphic-input w-full p-3"
        />
        <button type="submit" className="neomorphic-button w-full p-3 text-primary font-semibold">Request Password Reset</button>
      </form>
      <form onSubmit={handleResetPassword} className="space-y-4">
        <input
          type="text"
          value={resetToken}
          onChange={(e) => setResetToken(e.target.value)}
          placeholder="Reset Token"
          className="neomorphic-input w-full p-3"
        />
        <input
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          placeholder="New Password"
          className="neomorphic-input w-full p-3"
        />
        <button type="submit" className="neomorphic-button w-full p-3 text-primary font-semibold">Reset Password</button>
      </form>
    </div>
  )
}

