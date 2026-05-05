import { useState } from 'react'
import client from '../api/client'
import { useLang } from '../context/LanguageContext'

export default function Contact() {
  const { t } = useLang()
  const [name, setName] = useState('')
  const [email, setEmail] = useState('')
  const [message, setMessage] = useState('')
  const [loading, setLoading] = useState(false)
  const [success, setSuccess] = useState(false)
  const [error, setError] = useState('')

  const validate = () => {
    if (!name.trim()) return 'Name is required.'
    if (!email.trim()) return 'Email is required.'
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim())) return 'Please enter a valid email.'
    if (!message.trim()) return 'Message is required.'
    return ''
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    const validationError = validate()
    if (validationError) { setError(validationError); return }

    setError('')
    setLoading(true)
    try {
      await client.post('/contact', { name: name.trim(), email: email.trim(), message: message.trim() })
      setSuccess(true)
      setName('')
      setEmail('')
      setMessage('')
    } catch (err) {
      setError(err?.response?.data?.message || 'Failed to send message. Please try again.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="auth-page" style={{ paddingBottom: '3rem' }}>
      <div className="auth-card" style={{ maxWidth: '520px' }}>
        <h1 style={{ fontSize: '1.8rem' }}>{t('contactTitle')}</h1>
        <p className="auth-sub">{t('contactSub')}</p>

        {success ? (
          <div className="alert alert-success" style={{ textAlign: 'center', padding: '1.5rem' }}>
            <div style={{ fontSize: '1.5rem', marginBottom: '.5rem' }}>{t('messageSent')}</div>
            <div style={{ marginTop: '1rem' }}>
              <button
                className="btn btn-ghost"
                onClick={() => setSuccess(false)}
                style={{ fontSize: '.9rem' }}
              >
                {t('sendAnother')}
              </button>
            </div>
          </div>
        ) : (
          <form onSubmit={handleSubmit} noValidate>
            {error && (
              <div className="alert alert-error">
                {error}
              </div>
            )}

            <div className="form-group">
              <label htmlFor="contact-name">{t('yourName')}</label>
              <input
                id="contact-name"
                type="text"
                className="form-control"
                placeholder={t('yourName')}
                value={name}
                onChange={e => setName(e.target.value)}
                disabled={loading}
              />
            </div>

            <div className="form-group">
              <label htmlFor="contact-email">{t('yourEmail')}</label>
              <input
                id="contact-email"
                type="email"
                className="form-control"
                placeholder={t('yourEmail')}
                value={email}
                onChange={e => setEmail(e.target.value)}
                disabled={loading}
              />
            </div>

            <div className="form-group">
              <label htmlFor="contact-message">{t('message')}</label>
              <textarea
                id="contact-message"
                className="form-control"
                rows={5}
                placeholder={t('message')}
                value={message}
                onChange={e => setMessage(e.target.value)}
                disabled={loading}
              />
            </div>

            <button
              type="submit"
              className="btn btn-primary btn-full"
              disabled={loading}
            >
              {loading ? t('sending') : t('send')}
            </button>
          </form>
        )}
      </div>

      {/* Contact info */}
      <div style={{ maxWidth: '520px', width: '100%', marginTop: '1.5rem', display: 'flex', gap: '1rem', flexWrap: 'wrap', justifyContent: 'center' }}>
        <div style={{
          flex: 1, minWidth: '220px',
          background: 'var(--white)',
          border: '1px solid rgba(44,188,253,.15)',
          borderRadius: 'var(--radius-md)',
          padding: '1.25rem 1.5rem',
        }}>
          <div style={{ fontWeight: 600, fontSize: '.9rem', marginBottom: '.5rem', color: 'var(--ink)' }}>{t('contactEmail')}</div>
          <a href="mailto:support@englisheasy.com" style={{ color: 'var(--blue)', fontSize: '.9rem', textDecoration: 'none' }}>
            support@englisheasy.com
          </a>
        </div>
        <div style={{
          flex: 1, minWidth: '220px',
          background: 'var(--white)',
          border: '1px solid rgba(44,188,253,.15)',
          borderRadius: 'var(--radius-md)',
          padding: '1.25rem 1.5rem',
        }}>
          <div style={{ fontWeight: 600, fontSize: '.9rem', marginBottom: '.5rem', color: 'var(--ink)' }}>{t('contactFollow')}</div>
          <div style={{ display: 'flex', gap: '.75rem', flexWrap: 'wrap' }}>
            <a href="https://github.com" target="_blank" rel="noreferrer"
              style={{ fontSize: '.9rem', color: 'var(--blue)', textDecoration: 'none' }}>GitHub</a>
            <a href="https://t.me" target="_blank" rel="noreferrer"
              style={{ fontSize: '.9rem', color: 'var(--blue)', textDecoration: 'none' }}>Telegram</a>
            <a href="https://instagram.com" target="_blank" rel="noreferrer"
              style={{ fontSize: '.9rem', color: 'var(--blue)', textDecoration: 'none' }}>Instagram</a>
          </div>
        </div>
      </div>
    </div>
  )
}
