const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', 'sans-serif'],
            },
            colors: {
                // PALET BARU (COTTON CANDY LIME)
                'pop-white': '#FFFFFF', // Cotton
                'pop-lime': '#CDFF30', // Lime (Highlight/Accent)
                'pop-candy': '#FFD9F8', // Cotton Candy (Background lembut)
                'pop-gum': '#FF7ABF', // Gum (Tombol/Border lembut)
                'pop-hibiscus': '#EE2A7B', // Hibiscus (Teks Penting/Judul)
                'pop-dark': '#2D2D2D', // Dark Grey (Untuk Teks Bacaan biar jelas)
            },
            borderRadius: {
                '4xl': '2rem', // Super bulat
            },
            boxShadow: {
                'soft': '0 10px 25px -5px rgba(255, 122, 191, 0.3)', // Bayangan Pink lembut
                'glow': '0 0 15px rgba(205, 255, 48, 0.7)', // Glow Lime
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};