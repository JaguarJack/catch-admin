/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: 'jit',
  darkMode: 'class',
  content: [
      './resources/admin/**/*.{vue,js,ts,jsx.tsx}',
      './modules/**/views/**/*.{vue,js,ts,jsx.tsx}'
  ],
  theme: {
    extend: {
      transitionProperty: {
        width: 'width',
        spacing: 'margin, padding',
      },
      colors: {
        'regal-dark': '#283046',
      },
    },
  },
  plugins: [],
}
