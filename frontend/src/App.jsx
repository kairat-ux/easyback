import { BrowserRouter, Routes, Route, Navigate, Outlet } from 'react-router-dom'
import { AuthProvider, useAuth } from './context/AuthContext'
import { LanguageProvider } from './context/LanguageContext'
import Navbar from './components/Navbar'
import Home from './pages/Home'
import Login from './pages/Login'
import Register from './pages/Register'
import Lessons from './pages/Lessons'
import LessonDetail from './pages/LessonDetail'
import Exercises from './pages/Exercises'
import ExerciseDetail from './pages/ExerciseDetail'
import Progress from './pages/Progress'
import TeacherDashboard from './pages/TeacherDashboard'
import AdminPanel from './pages/AdminPanel'
import Contact from './pages/Contact'
import About from './pages/About'
import Leaderboard from './pages/Leaderboard'

function PrivateRoute({ roles }) {
  const { user, loading } = useAuth()
  if (loading) return (
    <div className="loading-page">
      <div className="spinner" />
      Loading...
    </div>
  )
  if (!user) return <Navigate to="/login" />
  if (roles && !roles.includes(user.role)) return <Navigate to="/" />
  return <Outlet />
}

function Footer() {
  return (
    <footer>
      <div className="footer-inner">
        <div>
          <div className="footer-brand">EnglishEasy</div>
          <p style={{ color: 'rgba(255,255,255,.55)', fontSize: '.85rem', marginTop: '.4rem', maxWidth: '260px', lineHeight: 1.6 }}>
            An online platform for learning English through lessons, exercises, and progress tracking.
          </p>
        </div>
        <div className="footer-col">
          <div className="footer-col-title">Pages</div>
          <a href="/about">About Us</a>
          <a href="/contact">Contact</a>
          <a href="/lessons">Lessons</a>
          <a href="/exercises">Exercises</a>
        </div>
        <div className="footer-col">
          <div className="footer-col-title">Contact</div>
          <a href="mailto:support@englisheasy.com">support@englisheasy.com</a>
          <a href="https://t.me" target="_blank" rel="noreferrer">Telegram</a>
          <a href="https://github.com" target="_blank" rel="noreferrer">GitHub</a>
        </div>
      </div>
      <div className="footer-bottom">&copy; 2025 EnglishEasy. All rights reserved.</div>
    </footer>
  )
}

export default function App() {
  return (
    <AuthProvider>
      <LanguageProvider>
      <BrowserRouter>
        <Navbar />
        <Routes>
          {/* Public routes */}
          <Route path="/" element={<Home />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/contact" element={<Contact />} />
          <Route path="/about" element={<About />} />

          {/* Auth required — any role */}
          <Route element={<PrivateRoute />}>
            <Route path="/lessons" element={<Lessons />} />
            <Route path="/lessons/:id" element={<LessonDetail />} />
            <Route path="/exercises" element={<Exercises />} />
            <Route path="/exercises/:id" element={<ExerciseDetail />} />
            <Route path="/leaderboard" element={<Leaderboard />} />
          </Route>

          {/* Students only */}
          <Route element={<PrivateRoute roles={['student']} />}>
            <Route path="/progress" element={<Progress />} />
          </Route>

          {/* Teacher or admin */}
          <Route element={<PrivateRoute roles={['teacher', 'admin']} />}>
            <Route path="/teacher" element={<TeacherDashboard />} />
          </Route>

          {/* Admin only */}
          <Route element={<PrivateRoute roles={['admin']} />}>
            <Route path="/admin" element={<AdminPanel />} />
          </Route>
        </Routes>
        <Footer />
      </BrowserRouter>
      </LanguageProvider>
    </AuthProvider>
  )
}
