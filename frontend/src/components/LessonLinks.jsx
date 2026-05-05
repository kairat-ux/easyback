import { useState, useEffect } from 'react'
import { useAuth } from '../context/AuthContext'
import { useLang } from '../context/LanguageContext'
import client from '../api/client'

export default function LessonLinks({ lessonId }) {
  const { user } = useAuth()
  const { t } = useLang()
  const [links, setLinks] = useState([])
  const [title, setTitle] = useState('')
  const [url, setUrl] = useState('')
  const [adding, setAdding] = useState(false)
  const [showForm, setShowForm] = useState(false)
  const [error, setError] = useState('')

  const canEdit = user?.role === 'teacher' || user?.role === 'admin'

  useEffect(() => {
    client.get(`/lessons/${lessonId}/links`)
      .then(res => setLinks(res.data))
      .catch(() => {})
  }, [lessonId])

  const handleAdd = async (e) => {
    e.preventDefault()
    if (!title.trim() || !url.trim()) return
    setError('')
    setAdding(true)
    try {
      const res = await client.post(`/lessons/${lessonId}/links`, { title: title.trim(), url: url.trim() })
      setLinks(prev => [res.data, ...prev])
      setTitle('')
      setUrl('')
      setShowForm(false)
    } catch (err) {
      setError(err?.response?.data?.message || 'Invalid URL or something went wrong.')
    } finally {
      setAdding(false)
    }
  }

  const handleDelete = async (id) => {
    if (!window.confirm('Delete this link?')) return
    try {
      await client.delete(`/lessons/${lessonId}/links/${id}`)
      setLinks(prev => prev.filter(l => l.id !== id))
    } catch {
      setError('Failed to delete link.')
    }
  }

  if (!canEdit && links.length === 0) return null

  return (
    <div className="file-upload">
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '1rem' }}>
        <h3 style={{ fontWeight: 600, fontSize: '1rem' }}>{t('links')}</h3>
        {canEdit && !showForm && (
          <button
            className="btn btn-ghost btn-sm"
            style={{ padding: '.3rem .85rem', fontSize: '.82rem' }}
            onClick={() => setShowForm(true)}
          >
            + {t('addLink')}
          </button>
        )}
      </div>

      {showForm && canEdit && (
        <form onSubmit={handleAdd} style={{ marginBottom: '1rem', display: 'flex', flexDirection: 'column', gap: '.6rem' }}>
          {error && <div className="alert alert-error" style={{ fontSize: '.85rem' }}>{error}</div>}
          <input
            className="form-control"
            placeholder={t('linkTitle')}
            value={title}
            onChange={e => setTitle(e.target.value)}
            disabled={adding}
            style={{ fontSize: '.9rem' }}
          />
          <input
            className="form-control"
            placeholder="https://..."
            value={url}
            onChange={e => setUrl(e.target.value)}
            disabled={adding}
            style={{ fontSize: '.9rem' }}
          />
          <div style={{ display: 'flex', gap: '.5rem' }}>
            <button className="btn btn-primary btn-sm" type="submit" disabled={adding || !title.trim() || !url.trim()}>
              {adding ? t('saving') : t('save')}
            </button>
            <button className="btn btn-ghost btn-sm" type="button" onClick={() => { setShowForm(false); setError('') }}>
              {t('cancel')}
            </button>
          </div>
        </form>
      )}

      {links.length > 0 ? (
        <div className="file-list">
          {links.map(link => (
            <div className="file-item" key={link.id}>
              <span style={{ fontSize: '1rem', marginRight: '.5rem', flexShrink: 0 }}>🔗</span>
              <a
                href={link.url}
                target="_blank"
                rel="noreferrer"
                style={{ color: 'var(--blue)', wordBreak: 'break-all', flex: 1, fontSize: '.9rem' }}
              >
                {link.title}
              </a>
              {canEdit && (
                <button
                  className="btn btn-danger btn-sm"
                  style={{ padding: '.3rem .75rem', fontSize: '.82rem', flexShrink: 0 }}
                  onClick={() => handleDelete(link.id)}
                >
                  {t('delete')}
                </button>
              )}
            </div>
          ))}
        </div>
      ) : (
        canEdit && (
          <p style={{ color: 'var(--ink-soft)', fontSize: '.88rem' }}>
            {t('noLinks')}
          </p>
        )
      )}
    </div>
  )
}
