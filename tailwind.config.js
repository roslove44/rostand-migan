/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./build/*.html", "./build/js/*.js"],
  theme: {
    extend: {
      screens: {
        widescreen: { raw: "(min-aspect-ratio: 3/2)" },
        tallscreen: { raw: "(min-aspect-ratio: 13/20)" },
      },
      keyframes: {
        "open-menu": {
          "0%": { transform: "scaleY(0)" },
          "80%": { transform: "scaleY(1.2)" },
          "100%": { transform: "scaleY(1)" },
        },
        "portfolio-action": {
          "0%": { transform: "scaleX(0)", opacity: 0.0 },
          "20%": { transform: "scaleX(0.2)", opacity: 0.8 },
          "40%": { transform: "scaleX(0.4)", opacity: 0.9 },
          "60%": { transform: "scaleX(0.6)", opacity: 0.93 },
          "80%": { transform: "scaleX(0.8)", opacity: 0.95 },
          "100%": { transform: "scaleX(1)", opacity: 1 },
        },
      },
      animation: {
        "open-menu": "open-menu 0.5s ease-in-out forwards",
        "portfolio-action": "portfolio-action 0.3s ease-in-out forwards",
      },
    },
  },
  plugins: [],
};
