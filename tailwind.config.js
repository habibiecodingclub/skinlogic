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
        "./resources/**/*.vue", // (Boleh dihapus, boleh juga tidak)
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                skinlogic: "#1A2636", // contoh warna utama
                "skinlogic-accent": "#FBBF24", // contoh warna aksen
            },
            fontFamily: {
                sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                poppins: ["Poppins", "sans-serif"],
            },
        },
    },

    plugins: [],
}
