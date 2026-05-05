import { useState } from 'react'
import client from '../api/client'
import { useAuth } from '../context/AuthContext'
import { useLang } from '../context/LanguageContext'

export default function Contact() {
  const { t } = useLang()
  const { user } = useAuth()
  const [name, setName] = useState(user?.name || '')
  const [email, setEmail] = useState(user?.email || '')
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
    <div className="auth-page">
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

        {/* Divider + contact info */}
        <div style={{ borderTop: '1px solid rgba(44,188,253,.12)', marginTop: '1.75rem', paddingTop: '1.5rem', display: 'flex', flexDirection: 'column', gap: '.85rem' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '.75rem' }}>
            <span style={{ fontSize: '1.1rem' }}>📧</span>
            <div>
              <div style={{ fontSize: '.78rem', color: 'var(--ink-soft)', fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.05em', marginBottom: '.1rem' }}>{t('contactEmail')}</div>
              <a href="mailto:support@englisheasy.com" style={{ color: 'var(--blue)', fontSize: '.9rem' }}>
                support@englisheasy.com
              </a>
            </div>
          </div>

          <div style={{ display: 'flex', alignItems: 'center', gap: '.75rem' }}>
            <span style={{ fontSize: '1.1rem' }}>🌐</span>
            <div>
              <div style={{ fontSize: '.78rem', color: 'var(--ink-soft)', fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.05em', marginBottom: '.35rem' }}>{t('contactFollow')}</div>
              <div style={{ display: 'flex', gap: '.6rem', flexWrap: 'wrap' }}>
                {[
                  { label: 'GitHub',    href: 'https://github.com' },
                  { label: 'Telegram',  href: 'https://t.me' },
                  { label: 'Instagram', href: 'https://instagram.com' },
                ].map(s => (
                  <a key={s.label} href={s.href} target="_blank" rel="noreferrer"
                    style={{
                      fontSize: '.82rem', fontWeight: 600,
                      color: 'var(--ink-mid)',
                      background: 'var(--surface)',
                      border: '1px solid rgba(44,188,253,.18)',
                      borderRadius: '999px',
                      padding: '.25rem .75rem',
                      transition: 'all .2s',
                    }}
                  >{s.label}</a>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
