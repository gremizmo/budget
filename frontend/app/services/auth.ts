import Cookies from 'js-cookie'

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000/api'

export const authService = {
  setToken: (token: string) => {
    Cookies.set('jwtToken', token, { expires: 7 }) // Token expires in 7 days
  },

  getToken: () => {
    return Cookies.get('jwtToken')
  },

  removeToken: () => {
    Cookies.remove('jwtToken')
  },

  isAuthenticated: () => {
    return !!Cookies.get('jwtToken')
  },

  login: async (email: string, password: string) => {
    try {
      const response = await fetch(`${API_URL}/login_check`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, password }),
      })

      if (!response.ok) {
        throw new Error('Login failed')
      }

      const data = await response.json()
      if (data.token) {
        authService.setToken(data.token)
        return true
      }
      throw new Error('Login failed')
    } catch (error) {
      console.error('Login error:', error)
      throw error
    }
  },

  logout: () => {
    authService.removeToken()
  },
}
