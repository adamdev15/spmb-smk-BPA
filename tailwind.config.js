import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                blue: {
                    50: '#E8F5FB',   // Primary Light
                    100: '#D1EBF7',
                    200: '#A3D7EF',
                    300: '#75C3E7',
                    400: '#47AFDF',
                    500: '#2A9DD8',
                    600: '#2491CA',   // Primary
                    700: '#1D80B5',   // Hover
                    800: '#1976A8',   // Primary Dark
                    900: '#125C84',
                    950: '#0C3D5A',
                }
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
