/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php", // <-- INI YANG PALING PENTING SEKARANG
        "./resources/**/*.js",
        "./resources/**/*.vue", // (Boleh dihapus, boleh juga tidak)
    ],
    theme: {
        extend: {
            colors: {
                skinlogic: "#1A2636", // contoh warna utama
                "skinlogic-accent": "#FBBF24", // contoh warna aksen
            },
            fontFamily: {
                poppins: ["Poppins", "sans-serif"],
            },
        },
    },
    plugins: [],
};
