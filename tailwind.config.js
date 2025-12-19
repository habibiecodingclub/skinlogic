import preset from './vendor/filament/filament/tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
    // Preset untuk Filament
    presets: [preset],

    // Content untuk SEMUA bagian (frontend + admin)
    content: [
        // Frontend
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/**/*.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',

        // Admin (Filament)
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },

    plugins: [],
}
