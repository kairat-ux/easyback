import { useState, useEffect } from 'react'
import client from '../api/client'
import { useAuth } from '../context/AuthContext'
import { useLang } from '../context/LanguageContext'

const PODIUM_EMOJI = ['🥇', '🥈', '🥉']
const PODIUM_CLASS = ['podium-1', 'podium-2', 'podium-3']

export default function Leaderboard() {
  const { user } = useAuth()
  const { t } = useLang()
  const [entries, setEntries] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    client
      .get('/leaderboard')
      .then(res => setEntries(res.data.data ?? res.data))
      .catch(() => setError('Failed to load leaderboard. Please try again.'))
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

  if (error) {
    return (
      <div className="container" style={{ paddingTop: '3rem' }}>
        <div className="alert alert-error">{error}</div>
      </div>
    )
  }

  const top3 = entries.slice(0, 3)
  const rest = entries.slice(3, 10)

  return (
    <div>
      <div className="page-header">
        <div className="container">
          <h1>{t('leaderboardTitle')} 🏆</h1>
          <p>{t('leaderboardSub')}</p>
        </div>
      </div>

      <div className="container" style={{ paddingTop: '2rem', paddingBottom: '3rem', maxWidth: '800px' }}>
        {entries.length === 0 ? (
          <div style={{ textAlign: 'center', padding: '4rem 0', color: 'var(--ink-soft)' }}>
            <div style={{ fontSize: '3rem', marginBottom: '1rem' }}>🏆</div>
            <h3 style={{ marginBottom: '.5rem' }}>{t('noLeaderboard')}</h3>
          </div>
        ) : (
          <>
            {top3.length > 0 && (
              <div className="podium">
                {top3.map((entry, idx) => (
                  <div
                    key={entry.id ?? idx}
                    className={`podium-item ${PODIUM_CLASS[idx]}`}
                    style={user && entry.id === user.id ? { boxShadow: '0 0 0 3px var(--blue)' } : {}}
                  >
                    <div className="podium-rank">{PODIUM_EMOJI[idx]}</div>
                    <div className="podium-name">
                      {entry.name}
                      {user && entry.id === user.id && (
                        <span style={{ fontSize: '.75rem', marginLeft: '.4rem', color: 'var(--blue)', fontWeight: 700 }}>({t('you')})</span>
                      )}
                    </div>
                    <div className="podium-points">⭐ {entry.points ?? 0} pts</div>
                    {entry.streak_days != null && (
                      <div style={{ fontSize: '.8rem', color: 'var(--ink-soft)', marginTop: '.2rem' }}>
                        🔥 {entry.streak_days} {t('streak').toLowerCase()}
                      </div>
                    )}
                  </div>
                ))}
              </div>
            )}

            {rest.length > 0 && (
              <div className="table-wrap">
                <table>
                  <thead>
                    <tr>
                      <th>{t('rank')}</th>
                      <th>{t('name') ?? 'Name'}</th>
                      <th>{t('points')}</th>
                      <th>{t('streak')}</th>
                    </tr>
                  </thead>
                  <tbody>
                    {rest.map((entry, idx) => {
                      const rank = idx + 4
                      const isCurrentUser = user && entry.id === user.id
                      return (
                        <tr key={entry.id ?? idx} className={isCurrentUser ? 'current-user-row' : ''}>
                          <td style={{ fontWeight: 700, color: 'var(--ink-soft)' }}>#{rank}</td>
                          <td>
                            {entry.name}
                            {isCurrentUser && (
                              <span style={{ fontSize: '.75rem', marginLeft: '.4rem', color: 'var(--blue)', fontWeight: 700 }}>({t('you')})</span>
                            )}
                          </td>
                          <td>⭐ {entry.points ?? 0}</td>
                          <td>{entry.streak_days != null ? `🔥 ${entry.streak_days}d` : '—'}</td>
                        </tr>
                      )
                    })}
                  </tbody>
                </table>
              </div>
            )}
          </>
        )}
      </div>
    </div>
  )
}
