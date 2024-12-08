import { authService } from '../services/auth'

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000/api'

async function fetchWithAuth(endpoint: string, options: RequestInit = {}) {
  const token = authService.getToken()
  const headers = {
    'Content-Type': 'application/json',
    ...options.headers,
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
  }

  const response = await fetch(`${API_URL}${endpoint}`, { ...options, headers })
  if (!response.ok) {
    if (response.status === 401) {
      authService.removeToken() // Clear invalid token
      throw new Error('Unauthorized')
    }
    throw new Error('API request failed')
  }
  return response.json()
}

export const api = {
  commands: {
    createUser: (userData: any) => fetchWithAuth('/users/new', { method: 'POST', body: JSON.stringify(userData) }),
    editUser: (userId: string, userData: any) => fetchWithAuth(`/users/${userId}`, { method: 'PUT', body: JSON.stringify(userData) }),
    changePassword: (userId: string, oldPassword: string, newPassword: string) => fetchWithAuth(`/users/${userId}/change-password`, { method: 'POST', body: JSON.stringify({ oldPassword, newPassword }) }),
  },
  queries: {
    getCurrentUser: () => fetchWithAuth('/users/me'),
  },
  envelopeCommands: {
    createEnvelope: (envelopeData: any) => fetchWithAuth('/envelopes/new', { method: 'POST', body: JSON.stringify(envelopeData) }),
    creditEnvelope: (envelopeId: string, amount: string) => fetchWithAuth(`/envelopes/${envelopeId}/credit`, { method: 'POST', body: JSON.stringify({ creditMoney: amount }) }),
    debitEnvelope: (envelopeId: string, amount: string) => fetchWithAuth(`/envelopes/${envelopeId}/debit`, { method: 'POST', body: JSON.stringify({ debitMoney: amount }) }),
    deleteEnvelope: (envelopeId: string) => fetchWithAuth(`/envelopes/${envelopeId}`, { method: 'DELETE' }),
    nameEnvelope: (envelopeId: string, name: string) => fetchWithAuth(`/envelopes/${envelopeId}/name`, { method: 'POST', body: JSON.stringify({ name }) }),
  },
  envelopeQueries: {
    listEnvelopes: () => fetchWithAuth('/envelopes'),
    getEnvelope: (envelopeId: string) => fetchWithAuth(`/envelopes/${envelopeId}`),
  },
}
