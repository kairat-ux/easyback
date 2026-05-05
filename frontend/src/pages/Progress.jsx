import { useState, useEffect } from 'react'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  ArcElement,
  PointElement,
  LineElement,
  RadialLinearScale,
  Title,
  Tooltip,
  Legend,
  Filler,
} from 'chart.js'
import { Bar, Pie, PolarArea, Line } from 'react-chartjs-2'
import client from '../api/client'
import { useAuth } from '../context/AuthContext'
import { useLang } from '../context/LanguageContext'

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  ArcElement,
  PointElement,
  LineElement,
  RadialLinearScale,
  Title,
  Tooltip,
  Legend,
  Filler
)

const chartOptions = {
  responsive: true,
  plugins: { legend: { position: 'bottom' } },
  maintainAspectRatio: true,
}

function NoData({ label }) {
  return (
    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', height: '180px', color: 'var(--ink-soft)' }}>
      {label}
    </div>
  )
}

export default function Progress() {
  const { user } = useAuth()
  const { t } = useLang()
  const [progress, setProgress] = useState(null)
  const [charts, setCharts] = useState(null)
  const [certificates, setCertificates] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    Promise.all([
      client.get('/progress'),
      client.get('/charts/student'),
      client.get('/certificates'),
    ])
      .then(([progressRes, chartsRes, certsRes]) => {
        setProgress(progressRes.data)
        setCharts(chartsRes.data)
        setCertificates(certsRes.data.data ?? certsRes.data)
      })
      .catch(() => setError('Failed to load progress data.'))
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

  const stats = progress?.stats || {}
  const attempts = progress?.attempts || []

  const totalPoints = user?.points ?? stats.total_points ?? 0
  const streakDays = user?.streak_days ?? stats.streak_days ?? 0

  // Bar chart
  const barData = (() => {
    const items = charts?.bar || []
    if (!items.length) return null
    return {
      labels: items.map(a => a.label),
      datasets: [{
        label: 'Score %',
        data: items.map(a => a.score ?? 0),
        backgroundColor: 'rgba(44,188,253,0.7)',
        borderRadius: 6,
      }],
    }
  })()

  // Pie chart
  const pieData = (() => {
    const items = charts?.pie || []
    if (!items.length) return null
    return {
      labels: items.map(i => i.label),
      datasets: [{
        data: items.map(i => i.count),
        backgroundColor: ['#2cbcfd', '#e8f7ff'],
      }],
    }
  })()

  // Polar area
  const polarData = (() => {
    const items = charts?.polar || []
    if (!items.length) return null
    return {
      labels: items.map(i => i.type),
      datasets: [{
        data: items.map(i => i.avg_score),
        backgroundColor: ['rgba(44,188,253,.7)', 'rgba(34,197,94,.7)', 'rgba(139,92,246,.7)'],
      }],
    }
  })()

  // Line chart
  const lineData = (() => {
    const items = charts?.line || []
    if (!items.length) return null
    return {
      labels: items.map(i => i.date),
      datasets: [{
        label: 'Avg Score %',
        data: items.map(i => i.avg_score),
        borderColor: '#2cbcfd',
        backgroundColor: 'rgba(44,188,253,.1)',
        tension: 0.4,
        fill: true,
      }],
    }
  })()

  const noDataLabel = t('noData')

  return (
    <div>
      <div className="page-header">
        <div className="container">
          <h1>{t('myProgress')}</h1>
          <p>{t('progressSub')}</p>
        </div>
      </div>

      <div className="container" style={{ paddingTop: '2rem', paddingBottom: '3rem' }}>
        {/* Stats — 4 cards */}
        <div className="stats-row">
          <div className="stat-card">
            <div className="stat-value">{stats.exercises_completed ?? 0}</div>
            <div className="stat-label">{t('exercisesCompleted')}</div>
          </div>
          <div className="stat-card">
            <div className="stat-value">{stats.avg_score != null ? `${Math.round(stats.avg_score)}%` : '—'}</div>
            <div className="stat-label">{t('averageScore')}</div>
          </div>
          <div className="stat-card">
            <div className="stat-value">⭐ {totalPoints}</div>
            <div className="stat-label">{t('totalPoints')}</div>
          </div>
          <div className="stat-card">
            <div className="stat-value">🔥 {streakDays}</div>
            <div className="stat-label">{t('dayStreak')}</div>
          </div>
        </div>

        {/* Charts */}
        <div className="charts-grid">
          <div className="chart-card">
            <h3>Last 10 Attempts — Scores</h3>
            {barData ? <Bar data={barData} options={chartOptions} /> : <NoData label={noDataLabel} />}
          </div>
          <div className="chart-card">
            <h3>Exercises: Completed vs Not Started</h3>
            {pieData ? <Pie data={pieData} options={chartOptions} /> : <NoData label={noDataLabel} />}
          </div>
          <div className="chart-card">
            <h3>Avg Score by Exercise Type</h3>
            {polarData ? <PolarArea data={polarData} options={chartOptions} /> : <NoData label={noDataLabel} />}
          </div>
          <div className="chart-card">
            <h3>Daily Average Score (Last 14 Days)</h3>
            {lineData ? <Line data={lineData} options={chartOptions} /> : <NoData label={noDataLabel} />}
          </div>
        </div>

        {/* Attempts table */}
        <h2 style={{ fontSize: '1.2rem', marginBottom: '1rem' }}>{t('attemptHistory')}</h2>
        <div className="table-wrap" style={{ marginBottom: '2.5rem' }}>
          {attempts.length === 0 ? (
            <div style={{ padding: '2rem', textAlign: 'center', color: 'var(--ink-soft)' }}>
              {t('noAttempts')}
            </div>
          ) : (
            <table>
              <thead>
                <tr>
                  <th>{t('exercise')}</th>
                  <th>{t('score')}</th>
                  <th>{t('maxScore')}</th>
                  <th>{t('bestScore')}</th>
                  <th>{t('attempts')}</th>
                  <th>{t('date')}</th>
                </tr>
              </thead>
              <tbody>
                {attempts.map((a, i) => (
                  <tr key={i}>
                    <td>{a.exercise_title || a.exercise || '—'}</td>
                    <td>{a.score ?? '—'}</td>
                    <td>{a.max_score ?? '—'}</td>
                    <td>{a.best_score ?? '—'}</td>
                    <td>{a.attempt_count ?? 1}</td>
                    <td>{a.attempted_at ? new Date(a.attempted_at).toLocaleDateString() : '—'}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </div>

        {/* Certificates */}
        <h2 style={{ fontSize: '1.2rem', marginBottom: '1rem' }}>{t('certificates')}</h2>
        {certificates.length === 0 ? (
          <div className="alert alert-info">
            {t('noCerts')}
          </div>
        ) : (
          <div>
            {certificates.map((cert, idx) => (
              <div className="cert-card" key={cert.id ?? idx}>
                <div className="cert-icon">🎓</div>
                <div>
                  <div className="cert-title">{cert.lesson_title || cert.lesson?.title || 'Lesson Certificate'}</div>
                  <div className="cert-date">
                    {t('earned')} {cert.earned_at ? new Date(cert.earned_at).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' }) : '—'}
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  )
}
