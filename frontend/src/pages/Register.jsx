import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { useLang } from '../context/LanguageContext'

export default function Register() {
  const { register } = useAuth()
  const { t } = useLang()
  const navigate = useNavigate()

  const [name, setName] = useState('')
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [role, setRole] = useState('student')
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const [teacherSuccess, setTeacherSuccess] = useState(false)

  const validate = () => {
    if (!name.trim()) return 'Full name is required.'
    if (!email.trim()) return 'Email is required.'
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim())) return 'Please enter a valid email address.'
    if (!password) return 'Password is required.'
    if (password.length < 8) return 'Password must be at least 8 characters.'
    return null
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    setError('')

    const validationError = validate()
    if (validationError) return setError(validationError)

    setLoading(true)
    try {
      const user = await register(name.trim(), email.trim(), password, role)
      if (user.role === 'teacher') {
        setTeacherSuccess(true)
      } else {
        navigate('/lessons')
      }
    } catch (err) {
      const data = err?.response?.data
      if (data?.errors) {
        const firstKey = Object.keys(data.errors)[0]
        setError(data.errors[firstKey][0])
      } else {
        setError(
          data?.message ||
          data?.error ||
          'Registration failed. Please try again.'
        )
      }
    } finally {
      setLoading(false)
    }
  }

  if (teacherSuccess) {
    return (
      <div className="auth-page">
        <div className="auth-card">
          <div className="alert alert-success">
            <strong>{t('applicationSent')}</strong> {t('applicationDesc')}
          </div>
          <p style={{ marginBottom: '1.5rem', color: 'var(--ink-soft)' }}>
            {t('applicationDesc')}
          </p>
          <Link to="/login" className="btn btn-primary btn-full">
            {t('logIn')}
          </Link>
        </div>
      </div>
    )
  }

  return (
    <div className="auth-page">
      <div className="auth-card">
        <h1>{t('createAccount')}</h1>
        <p className="auth-sub">{t('registerSub')}</p>

        {error && (
          <div className="alert alert-error" role="alert">
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} noValidate>
          <div className="form-group">
            <label htmlFor="name">{t('name')}</label>
            <input
              id="name"
              type="text"
              className="form-control"
              placeholder="John Doe"
              value={name}
              onChange={e => setName(e.target.value)}
              disabled={loading}
              autoComplete="name"
              autoFocus
            />
          </div>

          <div className="form-group">
            <label htmlFor="reg-email">{t('email')}</label>
            <input
              id="reg-email"
              type="email"
              className="form-control"
              placeholder="you@example.com"
              value={email}
              onChange={e => setEmail(e.target.value)}
              disabled={loading}
              autoComplete="email"
            />
          </div>

          <div className="form-group">
            <label htmlFor="reg-password">{t('password')}</label>
            <input
              id="reg-password"
              type="password"
              className="form-control"
              placeholder="At least 8 characters"
              value={password}
              onChange={e => setPassword(e.target.value)}
              disabled={loading}
              autoComplete="new-password"
            />
          </div>

          <div className="form-group">
            <label>{t('selectRole')}</label>
            <div className="role-selector">
              <div
                className={`role-card${role === 'student' ? ' active' : ''}`}
                onClick={() => !loading && setRole('student')}
                role="button"
                tabIndex={0}
                onKeyDown={e => e.key === 'Enter' && !loading && setRole('student')}
              >
                <h4>{t('student')}</h4>
                <p>{t('studentDesc')}</p>
              </div>
              <div
                className={`role-card${role === 'teacher' ? ' active' : ''}`}
                onClick={() => !loading && setRole('teacher')}
                role="button"
                tabIndex={0}
                onKeyDown={e => e.key === 'Enter' && !loading && setRole('teacher')}
              >
                <h4>{t('teacher')}</h4>
                <p>{t('teacherDesc')}</p>
              </div>
            </div>

            {role === 'teacher' && (
              <div className="alert alert-info">
                {t('teacherNote')}
              </div>
            )}
          </div>

          <button
            type="submit"
            className="btn btn-primary btn-full"
            disabled={loading}
          >
            {loading ? t('registering') : t('register')}
          </button>
        </form>

        <div className="auth-footer">
          {t('alreadyAccount')}{' '}
          <Link to="/login">{t('logIn')}</Link>
        </div>
      </div>
    </div>
  )
}
