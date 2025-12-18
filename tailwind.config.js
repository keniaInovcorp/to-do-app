import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    safelist: [
        // Background colors for user initials
        'bg-blue-800',
        'bg-blue-900',
        'bg-purple-800',
        'bg-purple-900',
        'bg-green-800',
        'bg-green-900',
        'bg-yellow-800',
        'bg-orange-800',
        'bg-red-800',
        'bg-red-900',
        'bg-indigo-800',
        'bg-indigo-900',
        'bg-teal-800',
        'bg-teal-900',
        'bg-pink-800',
        'bg-pink-900',
        'bg-violet-800',
        'bg-violet-900',
        'bg-amber-800',
        'bg-amber-900',
        'bg-cyan-800',
        'bg-emerald-800',
        'bg-rose-800',
        'bg-gray-800',
        'bg-gray-900',
        // Priority badge colors
        'bg-red-100',
        'text-red-800',
        'bg-yellow-100',
        'text-yellow-800',
        'bg-blue-100',
        'text-blue-800',
        'bg-gray-100',
        'text-gray-800',
        // Status badge colors
        'bg-green-100',
        'text-green-800',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
