import '../css/app.css';
import './bootstrap';

import App from '@/App';
import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createRoot(document.getElementById('root') as HTMLElement).render(
    <StrictMode>
        <title>{appName}</title>
        <App />
    </StrictMode>,
);
