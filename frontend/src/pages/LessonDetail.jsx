import { useState, useEffect } from 'react'
import { useParams, Link } from 'react-router-dom'
import client from '../api/client'
import FileUpload from '../components/FileUpload'
import { useAuth } from '../context/AuthContext'
import { useLang } from '../context/LanguageContext'

function CommentsSection({ lessonId }) {
  const { user } = useAuth()
  const { t } = useLang()
  const [comments, setComments] = useState([])
  const [loadingComments, setLoadingComments] = useState(true)
  const [commentError, setCommentError] = useState('')
  const [body, setBody] = useState('')
  const [posting, setPosting] = useState(false)

  useEffect(() => {
    fetchComments()
  }, [lessonId])

  const fetchComments = () => {
    setLoadingComments(true)
    client
      .get(`/lessons/${lessonId}/comments`)
      .then(res => setComments(res.data.data ?? res.data))
      .catch(() => setCommentError('Failed to load comments.'))
      .finally(() => setLoadingComments(false))
  }

  const handlePost = async (e) => {
    e.preventDefault()
    if (!body.trim()) return
    setPosting(true)
    setCommentError('')
    try {
      await client.post(`/lessons/${lessonId}/comments`, { body: body.trim() })
      setBody('')
      fetchComments()
    } catch (err) {
      setCommentError(err?.response?.data?.message || 'Failed to post comment.')
    } finally {
      setPosting(false)
    }
  }

  const handleDelete = async (commentId) => {
    if (!window.confirm('Delete this comment?')) return
    try {
      await client.delete(`/lessons/${lessonId}/comments/${commentId}`)
      setComments(prev => prev.filter(c => c.id !== commentId))
    } catch {
      setCommentError('Failed to delete comment.')
    }
  }

  const canDelete = (comment) => {
    if (!user) return false
    return user.id === comment.user_id || user.role === 'admin'
  }

  const formatDate = (dateStr) => {
    if (!dateStr) return ''
    return new Date(dateStr).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' })
  }

  return (
    <div className="comments-section">
      <h3>{t('comments')}</h3>

      {commentError && (
        <div className="alert alert-error" style={{ marginBottom: '1rem' }}>{commentError}</div>
      )}

      {loadingComments ? (
        <div style={{ color: 'var(--ink-soft)', padding: '1rem 0' }}>{t('loading')}</div>
      ) : comments.length === 0 ? (
        <div style={{ color: 'var(--ink-soft)', padding: '1rem 0', fontSize: '.92rem' }}>
          {t('noComments')}
        </div>
      ) : (
        <div>
          {comments.map(comment => (
            <div className="comment" key={comment.id}>
              <div className="comment-avatar">
                {(comment.user?.name || comment.name || 'U')[0].toUpperCase()}
              </div>
              <div className="comment-body">
                <div className="comment-header">
                  <span className="comment-name">{comment.user?.name || comment.name || 'Unknown'}</span>
                  <span className="comment-date">{formatDate(comment.created_at)}</span>
                  {canDelete(comment) && (
                    <button
                      onClick={() => handleDelete(comment.id)}
                      style={{ background: 'none', border: 'none', cursor: 'pointer', fontSize: '1rem', marginLeft: '.5rem', color: 'var(--ink-soft)' }}
                      title="Delete comment"
                    >
                      🗑
                    </button>
                  )}
                </div>
                <div className="comment-text">{comment.body}</div>
              </div>
            </div>
          ))}
        </div>
      )}

      {user && (
        <form onSubmit={handlePost} style={{ marginTop: '1.5rem' }}>
          <div className="form-group">
            <textarea
              className="form-control"
              placeholder={t('writeComment')}
              value={body}
              onChange={e => setBody(e.target.value)}
              rows={3}
              required
            />
          </div>
          <button
            type="submit"
            className="btn btn-primary btn-sm"
            disabled={posting || !body.trim()}
          >
            {posting ? t('posting') : t('postComment')}
          </button>
        </form>
      )}
    </div>
  )
}

export default function LessonDetail() {
  const { id } = useParams()
  const { user } = useAuth()
  const { t } = useLang()
  const [lesson, setLesson] = useState(null)
  const [allLessons, setAllLessons] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    Promise.all([
      client.get(`/lessons/${id}`),
      client.get('/lessons'),
    ])
      .then(([lessonRes, listRes]) => {
        setLesson(lessonRes.data.data ?? lessonRes.data)
        setAllLessons(listRes.data.data ?? listRes.data)
      })
      .catch(() => {
        setError('Failed to load lesson. It may not exist.')
      })
      .finally(() => setLoading(false))
  }, [id])

  if (loading) {
    return (
      <div className="loading-page">
        <div className="spinner" />
        {t('loading')}
      </div>
    )
  }

  if (error || !lesson) {
    return (
      <div className="container" style={{ paddingTop: '3rem' }}>
        <div className="alert alert-error">{error || 'Lesson not found.'}</div>
        <Link to="/lessons" className="btn btn-ghost" style={{ marginTop: '1rem' }}>
          {t('backToLessons')}
        </Link>
      </div>
    )
  }

  const sortedLessons = [...allLessons].sort((a, b) => a.id - b.id)
  const currentIndex = sortedLessons.findIndex(l => l.id === lesson.id)
  const prevLesson = currentIndex > 0 ? sortedLessons[currentIndex - 1] : null
  const nextLesson = currentIndex < sortedLessons.length - 1 ? sortedLessons[currentIndex + 1] : null

  return (
    <div>
      <div className="page-header">
        <div className="container">
          <Link
            to="/lessons"
            style={{ color: 'var(--ink-soft)', fontSize: '.9rem', display: 'inline-flex', alignItems: 'center', gap: '.4rem', marginBottom: '.75rem' }}
          >
            {t('backToLessons')}
          </Link>
          <h1>{lesson.title}</h1>
          <p>{t('by')} {lesson.teacher?.name || 'Unknown Teacher'}</p>
        </div>
      </div>

      <div className="container" style={{ paddingBottom: '3rem', maxWidth: '800px' }}>
        {lesson.description && (
          <p style={{ color: 'var(--ink-soft)', marginBottom: '1.5rem', lineHeight: 1.7 }}>
            {lesson.description}
          </p>
        )}

        {lesson.content && (
          <div style={{ background: 'var(--white)', border: '1px solid rgba(44,188,253,.1)', borderRadius: 'var(--radius-md)', padding: '1.75rem', marginBottom: '2rem', lineHeight: 1.8, whiteSpace: 'pre-wrap', fontSize: '.95rem' }}>
            {lesson.content}
          </div>
        )}

        {Array.isArray(lesson.vocabulary) && lesson.vocabulary.length > 0 && (
          <div style={{ marginBottom: '2rem' }}>
            <h2 style={{ fontSize: '1.25rem', marginBottom: '1rem' }}>{t('vocabulary')}</h2>
            <div className="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>{t('word')}</th>
                    <th>{t('translation')}</th>
                  </tr>
                </thead>
                <tbody>
                  {lesson.vocabulary.map((item, idx) => (
                    <tr key={idx}>
                      <td style={{ fontWeight: 500 }}>{item.word}</td>
                      <td>{item.translation}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {user && (
          <FileUpload
            category="lesson_material"
            fileableType="lesson"
            fileableId={lesson.id}
            readOnly={user.role === 'student'}
          />
        )}

        <CommentsSection lessonId={id} />

        {/* Prev / Next navigation */}
        <div style={{
          display: 'flex',
          justifyContent: 'space-between',
          gap: '1rem',
          marginTop: '2.5rem',
          paddingTop: '1.5rem',
          borderTop: '1px solid rgba(44,188,253,.1)',
          flexWrap: 'wrap',
        }}>
          {prevLesson ? (
            <Link
              to={`/lessons/${prevLesson.id}`}
              className="btn btn-ghost"
              style={{ fontSize: '.9rem' }}
            >
              {t('prevLesson')}
              <span style={{ display: 'block', fontSize: '.8rem', color: 'var(--ink-soft)', marginTop: '.1rem' }}>
                {prevLesson.title}
              </span>
            </Link>
          ) : <div />}

          {nextLesson ? (
            <Link
              to={`/lessons/${nextLesson.id}`}
              className="btn btn-primary"
              style={{ fontSize: '.9rem', textAlign: 'right' }}
            >
              {t('nextLesson')}
              <span style={{ display: 'block', fontSize: '.8rem', opacity: .8, marginTop: '.1rem' }}>
                {nextLesson.title}
              </span>
            </Link>
          ) : <div />}
        </div>
      </div>
    </div>
  )
}
