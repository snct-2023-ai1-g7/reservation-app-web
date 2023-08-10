import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify';

export default defineConfig({
    server: {
        host: true,
        hmr: {
            host: 'localhost'
        }
    }, 
    plugins: [
        vue(),
        vuetify({ autoImport: true }),
        laravel({
            input: ['resources/js/app.ts'],
            refresh: true,
        }),
    ],
});
