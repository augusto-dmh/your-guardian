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
                'primary-bg': '#131b21',
                'secondary-bg': '#1c272f',
                'tertiary-bg': '#2a343c',
                'quinary-bg': '#e4aa70',
                'primary-txt': '#ffffff',
                'secondary-txt': '#fac189',
                'tertiary-txt': '#e4aa70',
            },

            boxShadow: {
                custom: 'rgb(250, 193, 137, 0.1) 3px 3px 3px 3px inset, rgb(250, 193, 137) 5px 5px, rgb(250, 193, 137, 0.3) 10px 10px, rgb(250, 193, 137, 0.2) 15px 15px, rgb(250, 193, 137, 0.1) 20px 20px, rgb(250, 193, 137, 0.05) 25px 25px',
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
}
