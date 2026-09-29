import { createRoot } from 'react-dom/client'
import App from './App'
import './tailwind.css'
import './index.scss'

const rootElement = document.getElementById('root')
createRoot(rootElement).render(<App />)
