import {defineConfig, loadEnv} from 'vite';
import fs from 'fs';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({mode}) => {
    const env = loadEnv(mode, process.cwd(), '');
    const host = URL.parse(env.APP_URL)?.hostname;

    return {
        plugins: [
            laravel({
                input: 'resources/js/main.tsx',
                refresh: true,
            }),
            react(),
            tailwindcss(),
        ],
        server: fs.existsSync('./storage/tls-private.key') ? {
            hmr: {host},
            https: {
                key: fs.readFileSync('./storage/tls-private.key'),
                cert: fs.readFileSync('./storage/tls-public.key'),
            },
            origin: `https://${host}:5173`,
        } : {},
    };
});
