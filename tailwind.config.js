/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./build/*.html", "./build/js/*.js"],
  theme: {
    extend: {
      screens: {
        widescreen: { raw: "(min-aspect-ratio: 3/2)" },
        tallscreen: { raw: "(min-aspect-ratio: 13/20)" },
      },
      colors: {
        rostBlue: "#4831d4",
        bodyColor: "#474747",
        bgColor: "#f5f4fc",
      },
      keyframes: {
        "open-menu": {
          "0%": { transform: "scale(0)", opacity: 0.0 },
          "20%": { transform: "scale(0.2)", opacity: 0.2 },
          "40%": { transform: "scale(0.4)", opacity: 0.4 },
          "60%": { transform: "scale(0.6)", opacity: 0.6 },
          "80%": { transform: "scale(0.8)", opacity: 0.8 },
          "100%": { transform: "scale(1)", opacity: 1 },
        },
        "close-menu": {
          "0%": { transform: "scale(1)", opacity: 1 },
          "20%": { transform: "scale(0.8)", opacity: 0.8 },
          "40%": { transform: "scale(0.6)", opacity: 0.6 },
          "60%": { transform: "scale(0.4)", opacity: 0.4 },
          "80%": { transform: "scale(0.2)", opacity: 0.2 },
          "100%": { transform: "scale(0)", opacity: 0.0 },
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
        "open-menu": "open-menu 0.2s ease-in-out forwards",
        "close-menu": "close-menu 0.2s ease-in-out forwards",
        "portfolio-action": "portfolio-action 0.3s ease-in-out forwards",
      },
    },
  },
  plugins: [],
};
