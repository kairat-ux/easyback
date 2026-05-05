import { useState, useEffect, useRef } from 'react'
import { useAuth } from '../context/AuthContext'
import { useLang } from '../context/LanguageContext'
import client from '../api/client'

export default function FileUpload({ category, fileableType, fileableId, readOnly = false }) {
  const { user } = useAuth()
  const { t } = useLang()
  const [files, setFiles] = useState([])
  const [uploading, setUploading] = useState(false)
  const [successMsg, setSuccessMsg] = useState('')
  const [error, setError] = useState('')
  const fileInputRef = useRef(null)

  const fetchFiles = () => {
    const params = {}
    if (fileableType) params.fileable_type = fileableType
    if (fileableId) params.fileable_id = fileableId

    client
      .get('/files', { params })
      .then(res => setFiles(res.data.data ?? res.data))
      .catch(() => {})
  }

  useEffect(() => {
    fetchFiles()
  }, [fileableType, fileableId])

  const handleUpload = async () => {
    const file = fileInputRef.current?.files?.[0]
    if (!file) {
      setError('Please select a file first.')
      return
    }

    setError('')
    setSuccessMsg('')
    setUploading(true)

    const formData = new FormData()
    formData.append('file', file)
    if (category) formData.append('category', category)
    if (fileableType) formData.append('fileable_type', fileableType)
    if (fileableId) formData.append('fileable_id', fileableId)

    try {
      const res = await client.post('/files/upload', formData)
      const filename = res.data?.file?.original_name || res.data?.filename || file.name
      setSuccessMsg(`File "${filename}" uploaded successfully.`)
      if (fileInputRef.current) fileInputRef.current.value = ''
      fetchFiles()
    } catch (err) {
      const msg = err?.response?.data?.message || 'Upload failed. Please try again.'
      setError(msg)
    } finally {
      setUploading(false)
    }
  }

  const handleDelete = async (fileId) => {
    if (!window.confirm('Delete this file?')) return
    try {
      await client.delete(`/files/${fileId}`)
      setFiles(prev => prev.filter(f => f.id !== fileId))
    } catch {
      setError('Failed to delete file.')
    }
  }

  if (readOnly && files.length === 0) return null

  return (
    <div className="file-upload">
      <h3 style={{ marginBottom: '1rem', fontWeight: 600, fontSize: '1rem' }}>{t('files')}</h3>

      {!readOnly && (
        <>
          {error && <div className="alert alert-error" style={{ marginBottom: '.75rem' }}>{error}</div>}
          {successMsg && <div className="alert alert-success" style={{ marginBottom: '.75rem' }}>{successMsg}</div>}
          <div style={{ display: 'flex', gap: '.75rem', alignItems: 'center', justifyContent: 'center', flexWrap: 'wrap' }}>
            <input
              ref={fileInputRef}
              type="file"
              style={{ fontSize: '.9rem', maxWidth: '100%' }}
              disabled={uploading}
            />
            <button
              className="btn btn-primary"
              style={{ padding: '.5rem 1.25rem', fontSize: '.9rem' }}
              onClick={handleUpload}
              disabled={uploading}
            >
              {uploading ? t('uploading') : t('upload')}
            </button>
          </div>
        </>
      )}

      {files.length > 0 ? (
        <div className="file-list">
          {files.map(f => (
            <div className="file-item" key={f.id}>
              <span style={{ wordBreak: 'break-word', marginRight: '.5rem' }}>{f.original_name || f.filename || f.name}</span>
              <div style={{ display: 'flex', gap: '.5rem', alignItems: 'center', flexShrink: 0 }}>
                <a
                  href={`${import.meta.env.VITE_API_URL || '/api'}/files/${f.id}/download`}
                  target="_blank"
                  rel="noreferrer"
                  className="btn btn-ghost btn-sm"
                  style={{ padding: '.3rem .75rem', fontSize: '.82rem' }}
                >
                  {t('download')}
                </a>
                {!readOnly && (f.user_id === user?.id || user?.role === 'admin') && (
                  <button
                    className="btn btn-danger btn-sm"
                    style={{ padding: '.3rem .75rem', fontSize: '.82rem' }}
                    onClick={() => handleDelete(f.id)}
                  >
                    {t('delete')}
                  </button>
                )}
              </div>
            </div>
          ))}
        </div>
      ) : (
        !readOnly && (
          <p style={{ color: 'var(--ink-soft)', fontSize: '.88rem', marginTop: '.75rem' }}>
            No files uploaded yet.
          </p>
        )
      )}
    </div>
  )
}
