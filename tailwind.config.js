/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.{vue,js,ts,blade.php}',
    './resources/js/components/**/*.vue',
    './resources/js/views/**/*.vue',
  ],
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
  theme: {
    extend: {
      fontFamily: { sans: ['Inter','Cairo','sans-serif'] },
      colors: {
        brand: { 50:'#fdf8f0', 100:'#faefd9', 200:'#f4dba4',
                 300:'#ecc56e', 400:'#dea33c', 500:'#c9933a',
                 600:'#a87030', 700:'#8a5e1e', 800:'#6b4614',
                 900:'#4d3210' }
      }
    }
  }
}
