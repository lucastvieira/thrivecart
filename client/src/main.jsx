import { createRoot } from 'react-dom/client'
import App from './App.jsx'
import './tailwind.css'
import './index.scss'

const rootElement = document.getElementById('root')
createRoot(rootElement).render(<App />)
