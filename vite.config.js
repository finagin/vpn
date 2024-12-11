import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

const host = 'vpn.test';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/main.tsx',
            refresh: true,
        }),
        react(),
    ],
    server: {
        host,
        hmr: { host },
    },
});
