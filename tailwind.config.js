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
        "say-hi": {
          "0%": { transform: "translateZ(0) scale(1)" },
          "15%": {
            transform: "translate3d(-25%, 0,0) rotate(-5deg) scale(1.1)",
          },
          "30%": {
            transform: "translate3d(20%, 0,0) rotate(3deg) scale(1.2)",
          },
          "45%": {
            transform: "translate3d(-15%, 0,0) rotate(-3deg) scale(1.3)",
          },
          "60%": {
            transform: "translate3d(10%, 0,0) rotate(2deg) scale(1.2)",
          },
          "75%": {
            transform: "translate3d(-5%, 0,0) rotate(-1deg) scale(1.1)",
          },
          "100%": { transform: "translateZ(0) scale(1)" },
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
        "say-hi": "say-hi 2.5s infinite",
        "portfolio-action": "portfolio-action 0.3s ease-in-out forwards",
      },
    },
  },
  plugins: [],
};
