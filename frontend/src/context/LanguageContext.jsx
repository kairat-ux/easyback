import { createContext, useContext, useState, useEffect, useCallback } from 'react'
import en from '../i18n/en'
import ru from '../i18n/ru'
import kz from '../i18n/kz'
import client from '../api/client'

const translations = { en, ru, kz }

const LanguageContext = createContext(null)

export function LanguageProvider({ children }) {
  const [lang, setLang] = useState(() => localStorage.getItem('lang') || 'en')

  const changeLang = useCallback(async (newLang) => {
    setLang(newLang)
    localStorage.setItem('lang', newLang)
    try {
      await client.patch('/auth/language', { language: newLang })
    } catch {
      // not logged in — that's fine
    }
  }, [])

  const t = useCallback((key) => {
    return translations[lang]?.[key] ?? translations['en']?.[key] ?? key
  }, [lang])

  return (
    <LanguageContext.Provider value={{ lang, changeLang, t }}>
      {children}
    </LanguageContext.Provider>
  )
}

export function useLang() {
  return useContext(LanguageContext)
}
