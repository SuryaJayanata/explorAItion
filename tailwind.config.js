/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#BD18EF',
        secondary: '#170F23',
        accent: '#AB1AD6',
      },
      fontFamily: {
        'anton': ['Anton', 'sans-serif'],
        'montserrat': ['Montserrat', 'sans-serif'],
        'poppins': ['Poppins', 'sans-serif'],
        'tilt-warp': ['Tilt Warp', 'cursive'],
      },
    },
  },
  plugins: [],
}