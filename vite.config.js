import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/frontend.css', 'resources/js/app.js',  'resources/css/filament/admin/theme.css'],
            refresh: true,
        }),
        // tailwindcss(),
    ],
});
