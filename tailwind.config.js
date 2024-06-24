const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                dark: {
                    'eval-0': '#131B21',
                    'eval-1': '#1C272F',
                    'eval-2': '#2A343C',
                    'eval-3': '#0369a1',
                },
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
}
