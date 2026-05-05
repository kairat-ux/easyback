import { Link } from 'react-router-dom'
import { useLang } from '../context/LanguageContext'

export default function About() {
  const { t } = useLang()

  const goals = [
    { icon: '📚', title: t('aboutGoal1Title'), desc: t('aboutGoal1Desc') },
    { icon: '🎯', title: t('aboutGoal2Title'), desc: t('aboutGoal2Desc') },
    { icon: '📈', title: t('aboutGoal3Title'), desc: t('aboutGoal3Desc') },
    { icon: '🌍', title: t('aboutGoal4Title'), desc: t('aboutGoal4Desc') },
  ]

  return (
    <div>
      <div className="page-header">
        <div className="container">
          <h1>{t('aboutTitle')}</h1>
          <p>{t('aboutSub')}</p>
        </div>
      </div>

      <div className="container" style={{ paddingTop: '2.5rem', paddingBottom: '3rem', maxWidth: '800px' }}>

        {/* Mission */}
        <section style={{ marginBottom: '3rem' }}>
          <div className="section-label" style={{ marginBottom: '.5rem' }}>{t('aboutMissionLabel')}</div>
          <h2 style={{ fontSize: '1.6rem', marginBottom: '1rem' }}>{t('aboutMissionTitle')}</h2>
          <p style={{ color: 'var(--ink-soft)', lineHeight: 1.8, fontSize: '.97rem' }}>
            {t('aboutMissionDesc')}
          </p>
        </section>

        {/* Goals */}
        <section style={{ marginBottom: '3rem' }}>
          <div className="section-label" style={{ marginBottom: '.5rem' }}>{t('aboutGoalsLabel')}</div>
          <h2 style={{ fontSize: '1.6rem', marginBottom: '1.5rem' }}>{t('aboutGoalsTitle')}</h2>
          <div className="cards-grid">
            {goals.map((g, i) => (
              <div className="feature-card" key={i}>
                <div className="feature-icon">{g.icon}</div>
                <h3>{g.title}</h3>
                <p>{g.desc}</p>
              </div>
            ))}
          </div>
        </section>

        {/* Project info */}
        <section style={{
          background: 'var(--white)',
          border: '1px solid rgba(44,188,253,.15)',
          borderRadius: 'var(--radius-md)',
          padding: '2rem',
          marginBottom: '3rem',
        }}>
          <div className="section-label" style={{ marginBottom: '.5rem' }}>{t('aboutProjectLabel')}</div>
          <h2 style={{ fontSize: '1.4rem', marginBottom: '1rem' }}>{t('aboutProjectTitle')}</h2>
          <p style={{ color: 'var(--ink-soft)', lineHeight: 1.8, fontSize: '.95rem', marginBottom: '1rem' }}>
            {t('aboutProjectDesc')}
          </p>
          <ul style={{ color: 'var(--ink-soft)', lineHeight: 2, paddingLeft: '1.25rem', fontSize: '.95rem' }}>
            <li>{t('aboutTech1')}</li>
            <li>{t('aboutTech2')}</li>
            <li>{t('aboutTech3')}</li>
            <li>{t('aboutTech4')}</li>
          </ul>
        </section>

        {/* CTA */}
        <div style={{ textAlign: 'center', padding: '1rem 0' }}>
          <p style={{ color: 'var(--ink-soft)', marginBottom: '1.25rem' }}>{t('aboutCta')}</p>
          <div style={{ display: 'flex', gap: '1rem', justifyContent: 'center', flexWrap: 'wrap' }}>
            <Link to="/register" className="btn btn-primary">{t('getStarted')}</Link>
            <Link to="/contact" className="btn btn-ghost">{t('contact')}</Link>
          </div>
        </div>
      </div>
    </div>
  )
}
