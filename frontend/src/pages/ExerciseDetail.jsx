import { useState, useEffect, useRef, useCallback } from 'react'
import { useParams, Link } from 'react-router-dom'
import client from '../api/client'
import { useLang } from '../context/LanguageContext'

const TYPE_BADGE = {
  multiple_choice: { label: 'Multiple Choice', cls: 'badge-blue' },
  fill_blank: { label: 'Fill in the Blank', cls: 'badge-green' },
  matching: { label: 'Matching', cls: 'badge-purple' },
}

const DIFFICULTY_DURATION = {
  easy: 3 * 60,
  medium: 5 * 60,
  hard: 8 * 60,
}

function getTimerDuration(difficulty) {
  if (!difficulty) return 5 * 60
  return DIFFICULTY_DURATION[difficulty.toLowerCase()] ?? 5 * 60
}

function formatTime(seconds) {
  const m = Math.floor(seconds / 60).toString().padStart(2, '0')
  const s = (seconds % 60).toString().padStart(2, '0')
  return `${m}:${s}`
}

function Timer({ totalSeconds, onExpire, timeLeftLabel }) {
  const [remaining, setRemaining] = useState(totalSeconds)
  const intervalRef = useRef(null)
  const expiredRef = useRef(false)

  useEffect(() => {
    setRemaining(totalSeconds)
    expiredRef.current = false
  }, [totalSeconds])

  useEffect(() => {
    intervalRef.current = setInterval(() => {
      setRemaining(prev => {
        if (prev <= 1) {
          clearInterval(intervalRef.current)
          if (!expiredRef.current) {
            expiredRef.current = true
            onExpire()
          }
          return 0
        }
        return prev - 1
      })
    }, 1000)
    return () => clearInterval(intervalRef.current)
  }, [totalSeconds, onExpire])

  const pct = totalSeconds > 0 ? (remaining / totalSeconds) * 100 : 0
  const urgent = remaining <= 30

  return (
    <div className="timer-bar">
      <span style={{ fontSize: '1rem' }}>⏱</span>
      <span className={`timer-display${urgent ? ' urgent' : ''}`}>
        {formatTime(remaining)}
      </span>
      <div className="timer-progress">
        <div
          className={`timer-progress-fill${urgent ? ' urgent' : ''}`}
          style={{ width: `${pct}%` }}
        />
      </div>
      <span style={{ fontSize: '.8rem', color: urgent ? 'var(--red)' : 'var(--ink-soft)', fontWeight: 600, minWidth: '60px', textAlign: 'right' }}>
        {urgent ? 'Hurry!' : timeLeftLabel}
      </span>
    </div>
  )
}

function ScoreCard({ score, max, timesUp, onReset, timesUpLabel, tryAgainLabel, yourScoreLabel }) {
  const pct = max > 0 ? Math.round((score / max) * 100) : 0
  const cls = pct >= 70 ? 'good' : pct >= 40 ? 'ok' : 'bad'
  const emoji = pct >= 70 ? '🎉' : pct >= 40 ? '👍' : '💪'
  const msg = timesUp ? timesUpLabel : (pct >= 70 ? 'Great job!' : pct >= 40 ? 'Good effort!' : 'Keep practicing!')

  return (
    <div className={`score-card ${cls}`}>
      <div style={{ fontSize: '2.5rem' }}>{timesUp ? '⏰' : emoji}</div>
      <p style={{ fontWeight: 600, fontSize: '1.1rem', marginTop: '.5rem' }}>{msg}</p>
      <div className="score-number">{pct}%</div>
      <p style={{ color: 'var(--ink-soft)' }}>
        {yourScoreLabel}: {score} / {max}
      </p>
      <button
        className="btn btn-primary"
        style={{ marginTop: '1.5rem' }}
        onClick={onReset}
      >
        {tryAgainLabel}
      </button>
    </div>
  )
}

export default function ExerciseDetail() {
  const { id } = useParams()
  const { t } = useLang()
  const [exercise, setExercise] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [answers, setAnswers] = useState([])
  const [submitting, setSubmitting] = useState(false)
  const [result, setResult] = useState(null)
  const [timesUp, setTimesUp] = useState(false)
  const answersRef = useRef(answers)

  useEffect(() => {
    answersRef.current = answers
  }, [answers])

  const submitAnswers = useCallback(async (currentAnswers, isTimesUp = false) => {
    if (submitting) return
    setSubmitting(true)
    setTimesUp(isTimesUp)
    try {
      const res = await client.post(`/exercises/${id}/submit`, { answers: currentAnswers })
      setResult(res.data)
    } catch (err) {
      setError(err?.response?.data?.message || 'Submission failed. Please try again.')
    } finally {
      setSubmitting(false)
    }
  }, [id, submitting])

  const handleTimerExpire = useCallback(() => {
    submitAnswers(answersRef.current, true)
  }, [submitAnswers])

  const fetchExercise = () => {
    setLoading(true)
    setError('')
    setResult(null)
    setTimesUp(false)
    client
      .get(`/exercises/${id}`)
      .then(res => {
        const data = res.data.data ?? res.data
        setExercise(data)
        initAnswers(data)
      })
      .catch(() => setError('Failed to load exercise. It may not exist.'))
      .finally(() => setLoading(false))
  }

  const initAnswers = (data) => {
    const questions = data.questions || []
    if (data.type === 'matching') {
      setAnswers(questions.map(() => ({ left: '', right: '' })))
    } else {
      setAnswers(questions.map(() => ''))
    }
  }

  useEffect(() => {
    fetchExercise()
  }, [id])

  const handleReset = () => {
    setResult(null)
    setTimesUp(false)
    initAnswers(exercise)
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    await submitAnswers(answers, false)
  }

  if (loading) {
    return (
      <div className="loading-page">
        <div className="spinner" />
        {t('loading')}
      </div>
    )
  }

  if (error && !exercise) {
    return (
      <div className="container" style={{ paddingTop: '3rem' }}>
        <div className="alert alert-error">{error}</div>
        <Link to="/exercises" className="btn btn-ghost" style={{ marginTop: '1rem' }}>
          {t('backToExercises')}
        </Link>
      </div>
    )
  }

  const questions = exercise?.questions || []
  const badgeInfo = TYPE_BADGE[exercise?.type] || { label: exercise?.type, cls: 'badge-blue' }
  const timerDuration = getTimerDuration(exercise?.difficulty)

  return (
    <div>
      <div className="page-header">
        <div className="container">
          <Link
            to="/exercises"
            style={{ color: 'var(--ink-soft)', fontSize: '.9rem', display: 'inline-flex', alignItems: 'center', gap: '.4rem', marginBottom: '.75rem' }}
          >
            {t('backToExercises')}
          </Link>
          <div style={{ display: 'flex', alignItems: 'center', gap: '1rem', flexWrap: 'wrap' }}>
            <h1>{exercise.title}</h1>
            <span className={`badge ${badgeInfo.cls}`}>{badgeInfo.label}</span>
          </div>
          {exercise.description && <p>{exercise.description}</p>}
        </div>
      </div>

      <div className="container" style={{ paddingBottom: '3rem', maxWidth: '800px' }}>
        {error && <div className="alert alert-error" style={{ marginBottom: '1rem' }}>{error}</div>}

        {result ? (
          <ScoreCard
            score={result.score ?? result.correct ?? 0}
            max={result.max ?? result.total ?? questions.length}
            timesUp={timesUp}
            onReset={handleReset}
            timesUpLabel={t('timesUp')}
            tryAgainLabel={t('tryAgain')}
            yourScoreLabel={t('yourScore')}
          />
        ) : (
          <>
            {questions.length > 0 && (
              <Timer
                key={exercise.id}
                totalSeconds={timerDuration}
                onExpire={handleTimerExpire}
                timeLeftLabel={t('timeLeft')}
              />
            )}

            <form onSubmit={handleSubmit}>
              {questions.length === 0 && (
                <p style={{ color: 'var(--ink-soft)', padding: '2rem 0' }}>
                  This exercise has no questions yet.
                </p>
              )}

              {exercise.type === 'multiple_choice' && questions.map((q, qi) => (
                <div className="question-block" key={qi}>
                  <h4>Question {qi + 1}: {q.question}</h4>
                  {(q.options || []).map((opt, oi) => (
                    <label className="option-label" key={oi}>
                      <input
                        type="radio"
                        name={`q_${qi}`}
                        value={opt}
                        checked={answers[qi] === opt}
                        onChange={() => {
                          const next = [...answers]
                          next[qi] = opt
                          setAnswers(next)
                        }}
                      />
                      {opt}
                    </label>
                  ))}
                </div>
              ))}

              {exercise.type === 'fill_blank' && questions.map((q, qi) => (
                <div className="question-block" key={qi}>
                  <h4>Question {qi + 1}:</h4>
                  <p style={{ marginBottom: '.75rem', lineHeight: 1.6 }}>{q.question}</p>
                  <input
                    type="text"
                    className="form-control"
                    placeholder="Your answer..."
                    value={answers[qi] || ''}
                    onChange={e => {
                      const next = [...answers]
                      next[qi] = e.target.value
                      setAnswers(next)
                    }}
                  />
                </div>
              ))}

              {exercise.type === 'matching' && questions.map((q, qi) => (
                <div className="question-block" key={qi}>
                  <h4>Pair {qi + 1}</h4>
                  <div style={{ display: 'grid', gridTemplateColumns: '1fr auto 1fr', gap: '.75rem', alignItems: 'center' }}>
                    <div style={{ background: 'var(--surface)', padding: '.6rem 1rem', borderRadius: 'var(--radius-sm)', fontWeight: 500 }}>
                      {q.left || q.question}
                    </div>
                    <span style={{ color: 'var(--ink-soft)' }}>matches</span>
                    <select
                      className="form-control"
                      value={typeof answers[qi] === 'object' ? answers[qi]?.right || '' : answers[qi] || ''}
                      onChange={e => {
                        const next = [...answers]
                        next[qi] = e.target.value
                        setAnswers(next)
                      }}
                    >
                      <option value="">Select match...</option>
                      {questions.map((item, idx) => (
                        <option key={idx} value={item.right || item.correct_answer || ''}>
                          {item.right || item.correct_answer || `Option ${idx + 1}`}
                        </option>
                      ))}
                    </select>
                  </div>
                </div>
              ))}

              {questions.length > 0 && (
                <button
                  type="submit"
                  className="btn btn-primary"
                  style={{ marginTop: '1.5rem', minWidth: '160px' }}
                  disabled={submitting}
                >
                  {submitting ? t('submitting') : t('submitAnswers')}
                </button>
              )}
            </form>
          </>
        )}
      </div>
    </div>
  )
}
