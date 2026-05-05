import { Link } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { useLang } from '../context/LanguageContext'

export default function Home() {
  const { user } = useAuth()
  const { t } = useLang()

  return (
    <div>
      {/* Hero */}
      <section className="hero">
        <div className="hero-content fade-up">
          <h1>{t('heroTitle')}</h1>
          <p>{t('heroSub')}</p>
          <div className="hero-buttons">
            {user ? (
              <Link to="/lessons" className="btn btn-lg btn-white">{t('goToLessons')}</Link>
            ) : (
              <>
                <Link to="/register" className="btn btn-lg btn-white">{t('getStarted')}</Link>
                <Link to="/login" className="btn btn-lg btn-outline"
                  style={{ borderColor: 'rgba(255,255,255,.6)', color: '#fff' }}>
                  {t('logIn')}
                </Link>
              </>
            )}
          </div>
        </div>
      </section>

      {/* Features */}
      <section className="section">
        <div className="container">
          <div className="section-label">Why EnglishEasy</div>
          <h2 className="section-title">{t('featuresTitle')}</h2>
          <p className="section-sub" style={{ marginBottom: '3rem' }}>{t('featuresSub')}</p>
          <div className="cards-grid">
            <div className="feature-card">
              <div className="feature-icon">📚</div>
              <h3>{t('feat1Title')}</h3>
              <p>{t('feat1Desc')}</p>
            </div>
            <div className="feature-card">
              <div className="feature-icon">🧠</div>
              <h3>{t('feat2Title')}</h3>
              <p>{t('feat2Desc')}</p>
            </div>
            <div className="feature-card">
              <div className="feature-icon">📈</div>
              <h3>{t('feat3Title')}</h3>
              <p>{t('feat3Desc')}</p>
            </div>
          </div>
        </div>
      </section>

      {/* How it works */}
      <section className="section section-alt">
        <div className="container">
          <div className="section-label">Simple process</div>
          <h2 className="section-title">{t('howTitle')}</h2>
          <p className="section-sub" style={{ marginBottom: '3rem' }}>
            {t('step1Desc')}
          </p>
          <div className="steps-grid">
            <div className="step">
              <div className="step-number">1</div>
              <h3>{t('step1Title')}</h3>
              <p style={{ color: 'var(--ink-soft)', lineHeight: 1.6 }}>{t('step1Desc')}</p>
            </div>
            <div className="step">
              <div className="step-number">2</div>
              <h3>{t('step2Title')}</h3>
              <p style={{ color: 'var(--ink-soft)', lineHeight: 1.6 }}>{t('step2Desc')}</p>
            </div>
            <div className="step">
              <div className="step-number">3</div>
              <h3>{t('step3Title')}</h3>
              <p style={{ color: 'var(--ink-soft)', lineHeight: 1.6 }}>{t('step3Desc')}</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  )
}
