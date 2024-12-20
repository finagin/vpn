import '../css/app.css';
import './bootstrap';

import App from '@/App';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            refetchOnWindowFocus: false,
        },
    },
});

const enableMocking = async () => {
    if (!import.meta.env.DEV) {
        return;
    }

    const { worker } = await import('./mocks');
    return worker.start();
};

const root = createRoot(document.getElementById('root') as HTMLElement);

enableMocking().then(() => {
    root.render(
        <StrictMode>
            <QueryClientProvider client={queryClient}>
                <title>{appName}</title>
                <App />
            </QueryClientProvider>
        </StrictMode>,
    );
});
