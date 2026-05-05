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
import { useLang } from '../context/LanguageContext'
import ActionsMenu from '../components/ActionsMenu'

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

export default function AdminPanel() {
  const { t } = useLang()
  const [users, setUsers] = useState([])
  const [charts, setCharts] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  const fetchData = () => {
    setLoading(true)
    Promise.all([
      client.get('/users'),
      client.get('/charts/admin'),
    ])
      .then(([usersRes, chartsRes]) => {
        setUsers(usersRes.data.users ?? usersRes.data.data ?? usersRes.data)
        setCharts(chartsRes.data)
      })
      .catch(() => setError('Failed to load admin data.'))
      .finally(() => setLoading(false))
  }

  useEffect(() => { fetchData() }, [])

  const handleApprove = async (id) => {
    try {
      await client.patch(`/users/${id}/approve`)
      setUsers(prev => prev.map(u => u.id === id ? { ...u, status: 'approved' } : u))
    } catch {
      setError('Failed to approve user.')
    }
  }

  const handleReject = async (id) => {
    try {
      await client.patch(`/users/${id}/reject`)
      setUsers(prev => prev.map(u => u.id === id ? { ...u, status: 'rejected' } : u))
    } catch {
      setError('Failed to reject user.')
    }
  }

  const handleDelete = async (id) => {
    if (!window.confirm('Delete this user? This cannot be undone.')) return
    try {
      await client.delete(`/users/${id}`)
      setUsers(prev => prev.filter(u => u.id !== id))
    } catch {
      setError('Failed to delete user.')
    }
  }

  if (loading) {
    return (
      <div className="loading-page">
        <div className="spinner" />
        {t('loading')}
      </div>
    )
  }

  if (error && users.length === 0) {
    return (
      <div className="container" style={{ paddingTop: '3rem' }}>
        <div className="alert alert-error">{error}</div>
      </div>
    )
  }

  // Stats
  const total = users.length
  const studentsCount = users.filter(u => u.role === 'student').length
  const teachersCount = users.filter(u => u.role === 'teacher').length
  const pending = users.filter(u => u.status === 'pending').length

  const noDataLabel = t('noData')

  // Bar chart: top exercises by attempt count
  const barData = (() => {
    const items = charts?.top_exercises || []
    if (!items.length) return null
    return {
      labels: items.map(e => e.title || e.exercise || `#${e.id}`),
      datasets: [{
        label: 'Attempts',
        data: items.map(e => e.attempts_count ?? e.count ?? 0),
        backgroundColor: 'rgba(44,188,253,0.7)',
        borderRadius: 6,
      }],
    }
  })()

  // Pie chart: users by role
  const pieData = (() => {
    const byRole = charts?.users_by_role || {}
    const keys = Object.keys(byRole)
    if (!keys.length) {
      if (!total) return null
      return {
        labels: [t('students'), t('teachers')],
        datasets: [{ data: [studentsCount, teachersCount], backgroundColor: ['#2cbcfd', '#8b5cf6'] }],
      }
    }
    return {
      labels: keys,
      datasets: [{
        data: keys.map(k => byRole[k]),
        backgroundColor: ['#2cbcfd', '#8b5cf6', '#22c55e'],
      }],
    }
  })()

  // Polar area: avg score by type
  const polarData = (() => {
    const byType = charts?.avg_score_by_type || {}
    const keys = Object.keys(byType)
    if (!keys.length) return null
    return {
      labels: keys,
      datasets: [{
        data: keys.map(k => byType[k]),
        backgroundColor: ['rgba(44,188,253,.7)', 'rgba(34,197,94,.7)', 'rgba(139,92,246,.7)'],
      }],
    }
  })()

  // Line chart: registrations last 30 days
  const lineData = (() => {
    const daily = charts?.registrations || {}
    const keys = Object.keys(daily)
    if (!keys.length) return null
    return {
      labels: keys,
      datasets: [{
        label: 'Registrations',
        data: keys.map(k => daily[k]),
        borderColor: '#8b5cf6',
        backgroundColor: 'rgba(139,92,246,.1)',
        tension: 0.4,
        fill: true,
      }],
    }
  })()

  const roleLabel = (role) => {
    const map = { student: t('student'), teacher: t('teacher'), admin: 'Admin' }
    return map[role] || role
  }

  const statusBadge = (status) => {
    if (status === 'approved') return <span className="badge badge-green">Approved</span>
    if (status === 'pending') return <span className="badge badge-amber">Pending</span>
    if (status === 'rejected') return <span className="badge badge-red">Rejected</span>
    return <span className="badge badge-blue">{status || 'Active'}</span>
  }

  return (
    <div>
      <div className="page-header">
        <div className="container">
          <h1>{t('adminTitle')}</h1>
          <p>{t('adminSub')}</p>
        </div>
      </div>

      <div className="container" style={{ paddingTop: '2rem', paddingBottom: '3rem' }}>
        {error && <div className="alert alert-error" style={{ marginBottom: '1rem' }}>{error}</div>}

        {/* Stats */}
        <div className="stats-row">
          <div className="stat-card">
            <div className="stat-value">{total}</div>
            <div className="stat-label">{t('totalUsers')}</div>
          </div>
          <div className="stat-card">
            <div className="stat-value">{studentsCount}</div>
            <div className="stat-label">{t('students')}</div>
          </div>
          <div className="stat-card">
            <div className="stat-value">{teachersCount}</div>
            <div className="stat-label">{t('teachers')}</div>
          </div>
          <div className="stat-card">
            <div className="stat-value" style={{ color: pending > 0 ? 'var(--amber)' : 'var(--blue)' }}>{pending}</div>
            <div className="stat-label">{t('pendingApproval')}</div>
          </div>
        </div>

        {/* Charts */}
        <div className="charts-grid">
          <div className="chart-card">
            <h3>Top Exercises by Attempts</h3>
            {barData ? <Bar data={barData} options={chartOptions} /> : <NoData label={noDataLabel} />}
          </div>
          <div className="chart-card">
            <h3>Users by Role</h3>
            {pieData ? <Pie data={pieData} options={chartOptions} /> : <NoData label={noDataLabel} />}
          </div>
          <div className="chart-card">
            <h3>Avg Score by Exercise Type</h3>
            {polarData ? <PolarArea data={polarData} options={chartOptions} /> : <NoData label={noDataLabel} />}
          </div>
          <div className="chart-card">
            <h3>Registrations (Last 30 Days)</h3>
            {lineData ? <Line data={lineData} options={chartOptions} /> : <NoData label={noDataLabel} />}
          </div>
        </div>

        {/* Users table */}
        <h2 style={{ fontSize: '1.2rem', marginBottom: '1rem' }}>{t('totalUsers')}</h2>
        <div className="table-wrap">
          <table>
            <thead>
              <tr>
                <th>{t('name')}</th>
                <th>{t('email')}</th>
                <th>{t('role')}</th>
                <th>{t('status')}</th>
                <th style={{ width: '60px' }}>{t('actions')}</th>
              </tr>
            </thead>
            <tbody>
              {users.map(u => (
                <tr key={u.id}>
                  <td style={{ fontWeight: 500 }}>{u.name}</td>
                  <td style={{ color: 'var(--ink-soft)', fontSize: '.85rem' }}>{u.email}</td>
                  <td>{roleLabel(u.role)}</td>
                  <td>{statusBadge(u.status)}</td>
                  <td>
                    <ActionsMenu actions={[
                      u.status === 'pending' && u.role === 'teacher'
                        ? { label: t('approve'), onClick: () => handleApprove(u.id) }
                        : null,
                      u.status === 'pending' && u.role === 'teacher'
                        ? { label: t('reject'), onClick: () => handleReject(u.id), danger: true }
                        : null,
                      u.role !== 'admin'
                        ? { label: t('delete'), onClick: () => handleDelete(u.id), danger: true }
                        : null,
                    ].filter(Boolean)} />
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  )
}
