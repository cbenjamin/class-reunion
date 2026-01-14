import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './app/Livewire/**/*.php',   // <-- important
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [ require('@tailwindcss/forms') ],
  safelist: [
    'bg-gradient-to-br',
    'from-pink-500/90','to-rose-500/90',
    'from-indigo-500/90','to-blue-500/90',
    'from-emerald-500/90','to-teal-500/90',
    'from-amber-500/90','to-orange-500/90',
    'from-fuchsia-500/90','to-purple-500/90',
  ],
};

