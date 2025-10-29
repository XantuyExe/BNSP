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
        brand: {
        base: "#1E2230",       // background utama (gelap)
        nav: "#2A3042",        // navbar (lebih terang)
        card: "#363D52",       // objek/card (lebih terang lagi)
        accent: "#4A5470",     // aksen
        text: "#E4E7EF",       // teks terang
        },
      },
      fontFamily: {
        ui: ["Inter", "ui-sans-serif", "system-ui", "Segoe UI", "Roboto", "Helvetica", "Arial", "sans-serif"],
      },
      boxShadow: {
        soft: "0 8px 24px rgba(0,0,0,.06)",
      },
      borderRadius: {
        xl2: "1.25rem",
      },
    },
  },
  plugins: [],
};
