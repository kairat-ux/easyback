import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import client from '../api/client'
import { useLang } from '../context/LanguageContext'

export default function Lessons() {
  const { t } = useLang()
  const [lessons, setLessons] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [search, setSearch] = useState('')

  useEffect(() => {
    client
      .get('/lessons')
      .then(res => {
        setLessons(res.data.data ?? res.data)
      })
      .catch(() => {
        setError('Failed to load lessons. Please try again.')
      })
      .finally(() => setLoading(false))
  }, [])

  if (loading) {
    return (
      <div className="loading-page">
        <div className="spinner" />
        {t('loading')}
      </div>
    )
  }

  const filtered = lessons.filter(l =>
    l.title.toLowerCase().includes(search.toLowerCase())
  )

  return (
    <div>
      <div className="page-header">
        <div className="container">
          <h1>{t('allLessons')}</h1>
          <p>{t('lessonsSub')}</p>
        </div>
      </div>

      <div className="container" style={{ paddingTop: '2rem', paddingBottom: '3rem' }}>
        {error && (
          <div className="alert alert-error" style={{ marginBottom: '1.5rem' }}>
            {error}
          </div>
        )}

        <div className="search-bar">
          <span className="search-icon">🔍</span>
          <input
            type="text"
            placeholder={t('searchLessons')}
            value={search}
            onChange={e => setSearch(e.target.value)}
          />
        </div>

        {!error && filtered.length === 0 ? (
          <div style={{ textAlign: 'center', padding: '4rem 0', color: 'var(--ink-soft)' }}>
            <div style={{ fontSize: '3rem', marginBottom: '1rem' }}>📚</div>
            <h3 style={{ marginBottom: '.5rem' }}>
              {t('noLessons')}
            </h3>
          </div>
        ) : (
          <div className="cards-grid">
            {filtered.map(lesson => (
              <div className="card" key={lesson.id}>
                <h3>{lesson.title}</h3>
                <p>{lesson.description || 'No description provided.'}</p>
                <div className="card-footer">
                  <span style={{ fontSize: '.85rem', color: 'var(--ink-soft)' }}>
                    {t('by')} {lesson.teacher?.name || 'Unknown Teacher'}
                  </span>
                  <Link to={`/lessons/${lesson.id}`} className="btn btn-primary btn-sm">
                    {t('viewLesson')}
                  </Link>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  )
}
