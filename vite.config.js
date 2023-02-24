import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path'

export default defineConfig({
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost'
        }
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue()
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
    },
});
