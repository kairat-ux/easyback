import { Link, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { useLang } from '../context/LanguageContext'
import { useState, useEffect, useRef } from 'react'
import client from '../api/client'

function AdminBadgeLink({ to, children }) {
  const [pendingCount, setPendingCount] = useState(0)
  const intervalRef = useRef(null)
  const fetchCount = () => {
    client.get('/users/pending-count')
      .then(res => setPendingCount(Number(res.data.count ?? 0)))
      .catch(() => {})
  }
  useEffect(() => {
    fetchCount()
    intervalRef.current = setInterval(fetchCount, 60000)
    return () => clearInterval(intervalRef.current)
  }, [])
  return (
    <span className="nav-badge" style={{ display: 'inline-block', position: 'relative' }}>
      <Link to={to}>{children}</Link>
      {pendingCount > 0 && <span className="nav-badge-dot" />}
    </span>
  )
}

function LanguageSwitcher() {
  const { lang, changeLang } = useLang()
  const langs = [{ code: 'en', label: 'EN' }, { code: 'ru', label: 'RU' }, { code: 'kz', label: 'KZ' }]
  return (
    <div className="lang-switcher">
      {langs.map(l => (
        <button key={l.code} className={`lang-btn${lang === l.code ? ' active' : ''}`} onClick={() => changeLang(l.code)}>
          {l.label}
        </button>
      ))}
    </div>
  )
}

export default function Navbar() {
  const { user, logout } = useAuth()
  const { t } = useLang()
  const navigate = useNavigate()
  const [menuOpen, setMenuOpen] = useState(false)

  // close menu on route change
  useEffect(() => { setMenuOpen(false) }, [navigate])

  // close on outside click
  useEffect(() => {
    if (!menuOpen) return
    const handle = (e) => {
      if (!e.target.closest('.navbar') && !e.target.closest('.mobile-drawer')) {
        setMenuOpen(false)
      }
    }
    document.addEventListener('click', handle)
    return () => document.removeEventListener('click', handle)
  }, [menuOpen])

  const handleLogout = async () => {
    setMenuOpen(false)
    await logout()
    navigate('/')
  }

  const close = () => setMenuOpen(false)

  const navLinks = () => {
    const logoutBtn = user ? (
      <button
        key="logout"
        className="mobile-logout-btn"
        onClick={handleLogout}
      >
        {t('logOut')}
      </button>
    ) : null

    if (!user) return (
      <>
        <Link to="/" onClick={close}>{t('home')}</Link>
        <Link to="/about" onClick={close}>{t('about')}</Link>
        <Link to="/contact" onClick={close}>{t('contact')}</Link>
        <div className="mobile-drawer-divider" />
        <Link to="/login" className="mobile-auth-link" onClick={close}>{t('logIn')}</Link>
        <Link to="/register" className="mobile-auth-link mobile-auth-primary" onClick={close}>{t('register')}</Link>
      </>
    )
    if (user.role === 'student') return (
      <>
        <Link to="/lessons" onClick={close}>{t('lessons')}</Link>
        <Link to="/exercises" onClick={close}>{t('exercises')}</Link>
        <Link to="/progress" onClick={close}>{t('progress')}</Link>
        <Link to="/leaderboard" onClick={close}>{t('leaderboard')}</Link>
        <Link to="/about" onClick={close}>{t('about')}</Link>
        <Link to="/contact" onClick={close}>{t('contact')}</Link>
        <div className="mobile-drawer-divider" />
        {logoutBtn}
      </>
    )
    if (user.role === 'teacher') return (
      <>
        <Link to="/lessons" onClick={close}>{t('lessons')}</Link>
        <Link to="/exercises" onClick={close}>{t('exercises')}</Link>
        <Link to="/teacher" onClick={close}>{t('dashboard')}</Link>
        <Link to="/leaderboard" onClick={close}>{t('leaderboard')}</Link>
        <Link to="/about" onClick={close}>{t('about')}</Link>
        <Link to="/contact" onClick={close}>{t('contact')}</Link>
        <div className="mobile-drawer-divider" />
        {logoutBtn}
      </>
    )
    if (user.role === 'admin') return (
      <>
        <Link to="/lessons" onClick={close}>{t('lessons')}</Link>
        <Link to="/exercises" onClick={close}>{t('exercises')}</Link>
        <Link to="/teacher" onClick={close}>{t('dashboard')}</Link>
        <AdminBadgeLink to="/admin">{t('adminPanel')}</AdminBadgeLink>
        <Link to="/leaderboard" onClick={close}>{t('leaderboard')}</Link>
        <Link to="/about" onClick={close}>{t('about')}</Link>
        <div className="mobile-drawer-divider" />
        {logoutBtn}
      </>
    )
  }

  return (
    <>
      <nav className="navbar">
        <Link to="/" className="navbar-logo">EnglishEasy</Link>

        {/* Desktop links */}
        <ul className="navbar-links">
          {!user && (
            <><li><Link to="/">{t('home')}</Link></li><li><Link to="/about">{t('about')}</Link></li><li><Link to="/contact">{t('contact')}</Link></li></>
          )}
          {user?.role === 'student' && (
            <><li><Link to="/lessons">{t('lessons')}</Link></li><li><Link to="/exercises">{t('exercises')}</Link></li><li><Link to="/progress">{t('progress')}</Link></li><li><Link to="/leaderboard">{t('leaderboard')}</Link></li><li><Link to="/about">{t('about')}</Link></li><li><Link to="/contact">{t('contact')}</Link></li></>
          )}
          {user?.role === 'teacher' && (
            <><li><Link to="/lessons">{t('lessons')}</Link></li><li><Link to="/exercises">{t('exercises')}</Link></li><li><Link to="/teacher">{t('dashboard')}</Link></li><li><Link to="/leaderboard">{t('leaderboard')}</Link></li><li><Link to="/about">{t('about')}</Link></li><li><Link to="/contact">{t('contact')}</Link></li></>
          )}
          {user?.role === 'admin' && (
            <><li><Link to="/lessons">{t('lessons')}</Link></li><li><Link to="/exercises">{t('exercises')}</Link></li><li><Link to="/teacher">{t('dashboard')}</Link></li><li><AdminBadgeLink to="/admin">{t('adminPanel')}</AdminBadgeLink></li><li><Link to="/leaderboard">{t('leaderboard')}</Link></li><li><Link to="/about">{t('about')}</Link></li></>
          )}
        </ul>

        {/* Desktop auth + lang */}
        <div className="nav-auth">
          <LanguageSwitcher />
          {user ? (
            <>
              <div className="nav-user">
                <div className="avatar">{user.name[0].toUpperCase()}</div>
                <span className="nav-user-name">{user.name}</span>
                <span className={`role-pill ${user.role}`}>{user.role}</span>
                {user.points != null && (
                  <span style={{ fontSize: '.8rem', color: 'var(--blue-dark)', fontWeight: 600 }}>&#11088; {user.points}</span>
                )}
              </div>
              <button className="btn btn-sm btn-ghost" onClick={handleLogout}>{t('logOut')}</button>
            </>
          ) : (
            <>
              <Link to="/login" className="btn btn-sm btn-ghost">{t('logIn')}</Link>
              <Link to="/register" className="btn btn-sm btn-solid">{t('register')}</Link>
            </>
          )}
        </div>

        {/* Hamburger button — mobile only */}
        <button
          className={`nav-hamburger${menuOpen ? ' open' : ''}`}
          onClick={() => setMenuOpen(o => !o)}
          aria-label="Toggle menu"
        >
          <span /><span /><span />
        </button>
      </nav>

      {/* Mobile drawer */}
      {menuOpen && (
        <div className="mobile-drawer">
          <div className="mobile-drawer-top">
            <LanguageSwitcher />
            {user && (
              <div className="nav-user" style={{ flex: 1 }}>
                <div className="avatar">{user.name[0].toUpperCase()}</div>
                <span className="nav-user-name">{user.name}</span>
                <span className={`role-pill ${user.role}`}>{user.role}</span>
                {user.points != null && <span style={{ fontSize: '.8rem', color: 'var(--blue-dark)', fontWeight: 600 }}>&#11088; {user.points}</span>}
              </div>
            )}
          </div>
          <div className="mobile-drawer-links">
            {navLinks()}
          </div>
        </div>
      )}
    </>
  )
}
