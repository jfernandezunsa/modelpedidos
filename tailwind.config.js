/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.{php,html,js}",
  ],
  theme: {
    extend: {
      colors: {
        'principal': '#007D95',
        'secundario': '#184389',
        'destacado': '#05B9DB',
        'complemento': '#0D72C4',
        'error': '#B927AA',
        'advertencia': '#FDB623',
      },
      fontFamily: {
        'roboto': ['Roboto', 'sans-serif'],
      },
      fontSize: {
        xs: ['0.75rem', { lineHeight: '1rem' }],    // 12px
        sm: ['0.875rem', { lineHeight: '1.25rem' }], // 14px
        base: ['1rem', { lineHeight: '1.5rem' }],    // 16px (predeterminado)
        // ... otros tamaños
      },
    },
  },
  variants: {},
  plugins: [],
}