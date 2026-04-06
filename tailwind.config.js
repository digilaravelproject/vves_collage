import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue', // optional: if you use Vue
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Syne', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        primary: '#000165', 
        vves: {
          primary: '#000165',
          light: '#00014d',
        },
        accent: '#2563eb',
      },
    },
  },

  plugins: [forms],
};
