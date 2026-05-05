import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import client from '../api/client'
import { useLang } from '../context/LanguageContext'

const TYPE_BADGE = {
  multiple_choice: { label: 'Multiple Choice', cls: 'badge-blue' },
  fill_blank: { label: 'Fill in the Blank', cls: 'badge-green' },
  matching: { label: 'Matching', cls: 'badge-purple' },
}

const DIFFICULTY_BADGE = {
  easy:   { label: 'Easy',   cls: 'badge-green' },
  medium: { label: 'Medium', cls: 'badge-amber' },
  hard:   { label: 'Hard',   cls: 'badge-red' },
}

export default function Exercises() {
  const { t } = useLang()
  const [exercises, setExercises] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [difficultyFilter, setDifficultyFilter] = useState('all')

  const DIFFICULTY_LEVELS = [
    { key: 'all', label: t('all') },
    { key: 'easy', label: t('easy') },
    { key: 'medium', label: t('medium') },
    { key: 'hard', label: t('hard') },
  ]

  useEffect(() => {
    client
      .get('/exercises')
      .then(res => setExercises(res.data.data ?? res.data))
      .catch(() => setError('Failed to load exercises. Please try again.'))
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

  const typeBadge = (type) => {
    const info = TYPE_BADGE[type] || { label: type, cls: 'badge-blue' }
    return <span className={`badge ${info.cls}`}>{info.label}</span>
  }

  const diffBadge = (difficulty) => {
    if (!difficulty) return null
    const key = difficulty.toLowerCase()
    const info = DIFFICULTY_BADGE[key]
    if (!info) return null
    return <span className={`badge ${info.cls}`} style={{ marginLeft: '.4rem' }}>{info.label}</span>
  }

  const filtered = difficultyFilter === 'all'
    ? exercises
    : exercises.filter(ex => (ex.difficulty || '').toLowerCase() === difficultyFilter)

  return (
    <div>
      <div className="page-header">
        <div className="container">
          <h1>{t('allExercises')}</h1>
          <p>{t('exercisesSub')}</p>
        </div>
      </div>

      <div className="container" style={{ paddingTop: '2rem', paddingBottom: '3rem' }}>
        {error && (
          <div className="alert alert-error" style={{ marginBottom: '1.5rem' }}>{error}</div>
        )}

        <div className="filter-bar">
          <span style={{ fontSize: '.85rem', fontWeight: 600, color: 'var(--ink-soft)', alignSelf: 'center', marginRight: '.25rem' }}>
            {t('difficulty')}:
          </span>
          {DIFFICULTY_LEVELS.map(level => (
            <button
              key={level.key}
              className={`filter-btn${difficultyFilter === level.key ? ' active' : ''}`}
              onClick={() => setDifficultyFilter(level.key)}
            >
              {level.label}
            </button>
          ))}
        </div>

        {!error && filtered.length === 0 ? (
          <div style={{ textAlign: 'center', padding: '4rem 0', color: 'var(--ink-soft)' }}>
            <div style={{ fontSize: '3rem', marginBottom: '1rem' }}>🧠</div>
            <h3 style={{ marginBottom: '.5rem' }}>{t('noExercises')}</h3>
          </div>
        ) : (
          <div className="cards-grid">
            {filtered.map(ex => (
              <div className="card" key={ex.id}>
                <div style={{ marginBottom: '.6rem', display: 'flex', flexWrap: 'wrap', gap: '.3rem' }}>
                  {typeBadge(ex.type)}
                  {diffBadge(ex.difficulty)}
                </div>
                <h3>{ex.title}</h3>
                <p>{ex.description || 'No description provided.'}</p>
                {ex.lesson && (
                  <p style={{ fontSize: '.82rem', color: 'var(--ink-soft)', marginTop: '.5rem', WebkitLineClamp: 'unset', overflow: 'visible', display: 'block' }}>
                    {t('lesson')}: {ex.lesson.title}
                  </p>
                )}
                <div className="card-footer">
                  <span />
                  <Link
                    to={`/exercises/${ex.id}`}
                    className="btn btn-primary btn-sm"
                  >
                    {t('startExercise')}
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
