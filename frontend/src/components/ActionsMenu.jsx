import { useState, useRef, useEffect } from 'react'

export default function ActionsMenu({ actions }) {
  const [open, setOpen] = useState(false)
  const ref = useRef(null)

  useEffect(() => {
    if (!open) return
    const handle = (e) => {
      if (!ref.current?.contains(e.target)) setOpen(false)
    }
    document.addEventListener('mousedown', handle)
    return () => document.removeEventListener('mousedown', handle)
  }, [open])

  const visible = actions.filter(a => a !== null && a !== undefined)
  if (!visible.length) return null

  return (
    <div className="actions-menu" ref={ref}>
      <button
        className={`actions-trigger${open ? ' open' : ''}`}
        onClick={() => setOpen(o => !o)}
        title="Actions"
      >
        ⋮
      </button>
      {open && (
        <div className="actions-dropdown">
          {visible.map((action, i) => (
            <button
              key={i}
              className={`actions-item${action.danger ? ' danger' : ''}`}
              onClick={() => { setOpen(false); action.onClick() }}
            >
              {action.label}
            </button>
          ))}
        </div>
      )}
    </div>
  )
}
