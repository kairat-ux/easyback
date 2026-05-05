import { useState, useEffect, useCallback } from 'react'
import { useAuth } from '../context/AuthContext'
import { useLang } from '../context/LanguageContext'
import client from '../api/client'
import ActionsMenu from '../components/ActionsMenu'

// ---------- helpers ----------
function Badge({ type }) {
  const map = {
    multiple_choice: { label: 'Multiple Choice', cls: 'badge-blue' },
    fill_blank: { label: 'Fill in Blank', cls: 'badge-green' },
    matching: { label: 'Matching', cls: 'badge-purple' },
  }
  const info = map[type] || { label: type, cls: 'badge-blue' }
  return <span className={`badge ${info.cls}`}>{info.label}</span>
}

// ---------- Vocabulary builder ----------
function VocabBuilder({ vocab, setVocab }) {
  const add = () => setVocab(prev => [...prev, { word: '', translation: '' }])
  const remove = (i) => setVocab(prev => prev.filter((_, idx) => idx !== i))
  const update = (i, field, val) =>
    setVocab(prev => prev.map((row, idx) => idx === i ? { ...row, [field]: val } : row))

  return (
    <div>
      <label style={{ display: 'block', fontWeight: 600, fontSize: '.88rem', marginBottom: '.5rem', color: 'var(--ink)' }}>
        Vocabulary
      </label>
      {vocab.map((row, i) => (
        <div className="vocab-row" key={i}>
          <input
            className="form-control"
            placeholder="Word"
            value={row.word}
            onChange={e => update(i, 'word', e.target.value)}
          />
          <input
            className="form-control"
            placeholder="Translation"
            value={row.translation}
            onChange={e => update(i, 'translation', e.target.value)}
          />
          <button type="button" className="btn btn-danger btn-sm" onClick={() => remove(i)}>
            Remove
          </button>
        </div>
      ))}
      <button type="button" className="btn btn-ghost btn-sm" onClick={add} style={{ marginTop: '.25rem' }}>
        + Add Word
      </button>
    </div>
  )
}

// ---------- Questions builder ----------
function QuestionsBuilder({ type, questions, setQuestions }) {
  const addQuestion = () => {
    if (type === 'multiple_choice') {
      setQuestions(prev => [...prev, { question: '', options: ['', '', '', ''], correct_answer: '' }])
    } else if (type === 'fill_blank') {
      setQuestions(prev => [...prev, { question: '', correct_answer: '' }])
    } else if (type === 'matching') {
      setQuestions(prev => [...prev, { left: '', right: '' }])
    }
  }

  const remove = (i) => setQuestions(prev => prev.filter((_, idx) => idx !== i))

  const update = (i, field, val) =>
    setQuestions(prev => prev.map((q, idx) => idx === i ? { ...q, [field]: val } : q))

  const updateOption = (qi, oi, val) =>
    setQuestions(prev => prev.map((q, idx) => {
      if (idx !== qi) return q
      const opts = [...q.options]
      opts[oi] = val
      return { ...q, options: opts }
    }))

  return (
    <div>
      <label style={{ display: 'block', fontWeight: 600, fontSize: '.88rem', marginBottom: '.5rem', color: 'var(--ink)' }}>
        Questions
      </label>

      {questions.map((q, i) => (
        <div className="question-row" key={i}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '.5rem' }}>
            <strong style={{ fontSize: '.9rem' }}>Q{i + 1}</strong>
            <button type="button" className="btn btn-danger btn-sm" onClick={() => remove(i)}>Remove</button>
          </div>

          {type === 'multiple_choice' && (
            <>
              <div className="form-group" style={{ marginBottom: '.5rem' }}>
                <input
                  className="form-control"
                  placeholder="Question text"
                  value={q.question}
                  onChange={e => update(i, 'question', e.target.value)}
                />
              </div>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '.5rem', marginBottom: '.5rem' }}>
                {(q.options || ['', '', '', '']).map((opt, oi) => (
                  <input
                    key={oi}
                    className="form-control"
                    placeholder={`Option ${oi + 1}`}
                    value={opt}
                    onChange={e => updateOption(i, oi, e.target.value)}
                  />
                ))}
              </div>
              <select
                className="form-control"
                value={q.correct_answer}
                onChange={e => update(i, 'correct_answer', e.target.value)}
              >
                <option value="">Select correct answer...</option>
                {(q.options || []).filter(Boolean).map((opt, oi) => (
                  <option key={oi} value={opt}>{opt}</option>
                ))}
              </select>
            </>
          )}

          {type === 'fill_blank' && (
            <>
              <input
                className="form-control"
                placeholder="Question text (use ___ for blank)"
                value={q.question}
                onChange={e => update(i, 'question', e.target.value)}
                style={{ marginBottom: '.5rem' }}
              />
              <input
                className="form-control"
                placeholder="Correct answer"
                value={q.correct_answer}
                onChange={e => update(i, 'correct_answer', e.target.value)}
              />
            </>
          )}

          {type === 'matching' && (
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '.5rem' }}>
              <input
                className="form-control"
                placeholder="Left item"
                value={q.left}
                onChange={e => update(i, 'left', e.target.value)}
              />
              <input
                className="form-control"
                placeholder="Right item"
                value={q.right}
                onChange={e => update(i, 'right', e.target.value)}
              />
            </div>
          )}
        </div>
      ))}

      <button type="button" className="btn btn-ghost btn-sm" onClick={addQuestion} style={{ marginTop: '.25rem' }}>
        + Add Question
      </button>
    </div>
  )
}

// ---------- Lesson Form ----------
function LessonForm({ initial, onSave, onCancel, saving, t }) {
  const [title, setTitle] = useState(initial?.title || '')
  const [description, setDescription] = useState(initial?.description || '')
  const [content, setContent] = useState(initial?.content || '')
  const [vocab, setVocab] = useState(initial?.vocabulary || [])
  const [error, setError] = useState('')

  const handleSubmit = (e) => {
    e.preventDefault()
    if (!title.trim()) { setError('Title is required.'); return }
    setError('')
    onSave({ title: title.trim(), description, content, vocabulary: vocab })
  }

  return (
    <div className="inline-form">
      <h3>{initial ? t('edit') + ' Lesson' : t('createLesson')}</h3>
      {error && <div className="alert alert-error" style={{ marginBottom: '.75rem' }}>{error}</div>}
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Title *</label>
          <input className="form-control" value={title} onChange={e => setTitle(e.target.value)} placeholder="Lesson title" />
        </div>
        <div className="form-group">
          <label>Description</label>
          <input className="form-control" value={description} onChange={e => setDescription(e.target.value)} placeholder="Short description" />
        </div>
        <div className="form-group">
          <label>Content</label>
          <textarea
            className="form-control"
            rows={6}
            value={content}
            onChange={e => setContent(e.target.value)}
            placeholder="Lesson content..."
          />
        </div>
        <div className="form-group">
          <VocabBuilder vocab={vocab} setVocab={setVocab} />
        </div>
        <div style={{ display: 'flex', gap: '.75rem', marginTop: '.5rem' }}>
          <button type="submit" className="btn btn-primary" disabled={saving}>
            {saving ? 'Saving...' : t('save')}
          </button>
          <button type="button" className="btn btn-ghost" onClick={onCancel}>{t('cancel')}</button>
        </div>
      </form>
    </div>
  )
}

// ---------- Exercise Form ----------
function ExerciseForm({ initial, lessons, onSave, onCancel, saving, t }) {
  const [title, setTitle] = useState(initial?.title || '')
  const [type, setType] = useState(initial?.type || 'multiple_choice')
  const [description, setDescription] = useState(initial?.description || '')
  const [lessonId, setLessonId] = useState(initial?.lesson_id || '')
  const [questions, setQuestions] = useState(initial?.questions || [])
  const [error, setError] = useState('')

  const handleTypeChange = (val) => {
    setType(val)
    setQuestions([])
  }

  const handleSubmit = (e) => {
    e.preventDefault()
    if (!title.trim()) { setError('Title is required.'); return }
    setError('')
    onSave({
      title: title.trim(),
      type,
      description,
      lesson_id: lessonId || null,
      questions,
    })
  }

  return (
    <div className="inline-form">
      <h3>{initial ? t('edit') + ' Exercise' : t('createExercise')}</h3>
      {error && <div className="alert alert-error" style={{ marginBottom: '.75rem' }}>{error}</div>}
      <form onSubmit={handleSubmit}>
        <div className="form-row">
          <div className="form-group">
            <label>Title *</label>
            <input className="form-control" value={title} onChange={e => setTitle(e.target.value)} placeholder="Exercise title" />
          </div>
          <div className="form-group">
            <label>Type</label>
            <select className="form-control" value={type} onChange={e => handleTypeChange(e.target.value)}>
              <option value="multiple_choice">Multiple Choice</option>
              <option value="fill_blank">Fill in the Blank</option>
              <option value="matching">Matching</option>
            </select>
          </div>
        </div>
        <div className="form-row">
          <div className="form-group">
            <label>Description</label>
            <input className="form-control" value={description} onChange={e => setDescription(e.target.value)} placeholder="Short description" />
          </div>
          <div className="form-group">
            <label>Linked Lesson (optional)</label>
            <select className="form-control" value={lessonId} onChange={e => setLessonId(e.target.value)}>
              <option value="">— None —</option>
              {lessons.map(l => (
                <option key={l.id} value={l.id}>{l.title}</option>
              ))}
            </select>
          </div>
        </div>
        <div className="form-group">
          <QuestionsBuilder type={type} questions={questions} setQuestions={setQuestions} />
        </div>
        <div style={{ display: 'flex', gap: '.75rem', marginTop: '.5rem' }}>
          <button type="submit" className="btn btn-primary" disabled={saving}>
            {saving ? 'Saving...' : t('save')}
          </button>
          <button type="button" className="btn btn-ghost" onClick={onCancel}>{t('cancel')}</button>
        </div>
      </form>
    </div>
  )
}

// ---------- Lessons Tab ----------
function LessonsTab({ user, t }) {
  const [lessons, setLessons] = useState([])
  const [loading, setLoading] = useState(true)
  const [showForm, setShowForm] = useState(false)
  const [editItem, setEditItem] = useState(null)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState('')

  const fetchLessons = useCallback(() => {
    setLoading(true)
    client
      .get('/lessons')
      .then(res => {
        const all = res.data.data ?? res.data
        const mine = user.role === 'admin'
          ? all
          : all.filter(l => l.teacher?.id === user.id || l.user_id === user.id)
        setLessons(mine)
      })
      .catch(() => setError('Failed to load lessons.'))
      .finally(() => setLoading(false))
  }, [user])

  useEffect(() => { fetchLessons() }, [fetchLessons])

  const handleSave = async (data) => {
    setSaving(true)
    setError('')
    try {
      if (editItem) {
        await client.put(`/lessons/${editItem.id}`, data)
      } else {
        await client.post('/lessons', data)
      }
      setShowForm(false)
      setEditItem(null)
      fetchLessons()
    } catch (err) {
      setError(err?.response?.data?.message || 'Failed to save lesson.')
    } finally {
      setSaving(false)
    }
  }

  const handleDelete = async (id) => {
    if (!window.confirm('Delete this lesson? This cannot be undone.')) return
    try {
      await client.delete(`/lessons/${id}`)
      setLessons(prev => prev.filter(l => l.id !== id))
    } catch {
      setError('Failed to delete lesson.')
    }
  }

  return (
    <div>
      {error && <div className="alert alert-error" style={{ marginBottom: '1rem' }}>{error}</div>}

      {!showForm && (
        <div style={{ marginBottom: '1.25rem' }}>
          <button className="btn btn-primary" onClick={() => { setShowForm(true); setEditItem(null) }}>
            + {t('createLesson')}
          </button>
        </div>
      )}

      {showForm && (
        <LessonForm
          initial={editItem}
          onSave={handleSave}
          onCancel={() => { setShowForm(false); setEditItem(null) }}
          saving={saving}
          t={t}
        />
      )}

      {loading ? (
        <div className="loading-page" style={{ minHeight: '200px' }}>
          <div className="spinner" />
          {t('loading')}
        </div>
      ) : lessons.length === 0 ? (
        <div style={{ textAlign: 'center', padding: '3rem', color: 'var(--ink-soft)' }}>
          {t('noData')}
        </div>
      ) : (
        <div className="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Title</th>
                <th>Description</th>
                <th style={{ width: '60px' }}>{t('actions')}</th>
              </tr>
            </thead>
            <tbody>
              {lessons.map(lesson => (
                <tr key={lesson.id}>
                  <td style={{ fontWeight: 500 }}>{lesson.title}</td>
                  <td style={{ color: 'var(--ink-soft)', maxWidth: '260px' }}>
                    {lesson.description || '—'}
                  </td>
                  <td>
                    <ActionsMenu actions={[
                      { label: t('edit'), onClick: () => { setEditItem(lesson); setShowForm(true) } },
                      { label: t('delete'), onClick: () => handleDelete(lesson.id), danger: true },
                    ]} />
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  )
}

// ---------- Exercises Tab ----------
function ExercisesTab({ user, t }) {
  const [exercises, setExercises] = useState([])
  const [lessons, setLessons] = useState([])
  const [loading, setLoading] = useState(true)
  const [showForm, setShowForm] = useState(false)
  const [editItem, setEditItem] = useState(null)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState('')

  const fetchData = useCallback(() => {
    setLoading(true)
    Promise.all([client.get('/exercises'), client.get('/lessons')])
      .then(([exRes, lRes]) => {
        const allEx = exRes.data.data ?? exRes.data
        const allLessons = lRes.data.data ?? lRes.data
        const myEx = user.role === 'admin'
          ? allEx
          : allEx.filter(e => e.teacher?.id === user.id || e.user_id === user.id)
        const myLessons = user.role === 'admin'
          ? allLessons
          : allLessons.filter(l => l.teacher?.id === user.id || l.user_id === user.id)
        setExercises(myEx)
        setLessons(myLessons)
      })
      .catch(() => setError('Failed to load data.'))
      .finally(() => setLoading(false))
  }, [user])

  useEffect(() => { fetchData() }, [fetchData])

  const handleSave = async (data) => {
    setSaving(true)
    setError('')
    try {
      if (editItem) {
        await client.put(`/exercises/${editItem.id}`, data)
      } else {
        await client.post('/exercises', data)
      }
      setShowForm(false)
      setEditItem(null)
      fetchData()
    } catch (err) {
      setError(err?.response?.data?.message || 'Failed to save exercise.')
    } finally {
      setSaving(false)
    }
  }

  const handleDelete = async (id) => {
    if (!window.confirm('Delete this exercise? This cannot be undone.')) return
    try {
      await client.delete(`/exercises/${id}`)
      setExercises(prev => prev.filter(e => e.id !== id))
    } catch {
      setError('Failed to delete exercise.')
    }
  }

  return (
    <div>
      {error && <div className="alert alert-error" style={{ marginBottom: '1rem' }}>{error}</div>}

      {!showForm && (
        <div style={{ marginBottom: '1.25rem' }}>
          <button className="btn btn-primary" onClick={() => { setShowForm(true); setEditItem(null) }}>
            + {t('createExercise')}
          </button>
        </div>
      )}

      {showForm && (
        <ExerciseForm
          initial={editItem}
          lessons={lessons}
          onSave={handleSave}
          onCancel={() => { setShowForm(false); setEditItem(null) }}
          saving={saving}
          t={t}
        />
      )}

      {loading ? (
        <div className="loading-page" style={{ minHeight: '200px' }}>
          <div className="spinner" />
          {t('loading')}
        </div>
      ) : exercises.length === 0 ? (
        <div style={{ textAlign: 'center', padding: '3rem', color: 'var(--ink-soft)' }}>
          {t('noData')}
        </div>
      ) : (
        <div className="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Linked Lesson</th>
                <th style={{ width: '60px' }}>{t('actions')}</th>
              </tr>
            </thead>
            <tbody>
              {exercises.map(ex => (
                <tr key={ex.id}>
                  <td style={{ fontWeight: 500 }}>{ex.title}</td>
                  <td><Badge type={ex.type} /></td>
                  <td style={{ color: 'var(--ink-soft)' }}>{ex.lesson?.title || '—'}</td>
                  <td>
                    <ActionsMenu actions={[
                      { label: t('edit'), onClick: () => { setEditItem(ex); setShowForm(true) } },
                      { label: t('delete'), onClick: () => handleDelete(ex.id), danger: true },
                    ]} />
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  )
}

// ---------- Main Component ----------
export default function TeacherDashboard() {
  const { user } = useAuth()
  const { t } = useLang()
  const [tab, setTab] = useState('lessons')

  return (
    <div>
      <div className="page-header">
        <div className="container">
          <h1>{t('teacherDashTitle')}</h1>
          <p>{t('teacherDashSub')}</p>
        </div>
      </div>

      <div className="container" style={{ paddingTop: '2rem', paddingBottom: '3rem' }}>
        <div className="tabs">
          <button
            className={`tab ${tab === 'lessons' ? 'active' : ''}`}
            onClick={() => setTab('lessons')}
          >
            {t('myLessons')}
          </button>
          <button
            className={`tab ${tab === 'exercises' ? 'active' : ''}`}
            onClick={() => setTab('exercises')}
          >
            {t('myExercises')}
          </button>
        </div>

        {tab === 'lessons' && <LessonsTab user={user} t={t} />}
        {tab === 'exercises' && <ExercisesTab user={user} t={t} />}
      </div>
    </div>
  )
}
