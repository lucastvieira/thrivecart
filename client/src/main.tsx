import { createRoot } from 'react-dom/client';
import App from './App';
import './tailwind.css';
import './index.scss';

const rootElement = document.getElementById('root') as HTMLElement;
createRoot(rootElement).render(<App />);
