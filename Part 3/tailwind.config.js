/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./pages/**/*.{js,ts,jsx,tsx}",
    "./components/**/*.{js,ts,jsx,tsx}",
  ],
  plugins: [require("daisyui")],
  daisyui: {
    themes: [
      {
        light: {
          primary: "#E0A82E",
          secondary: "#F9D72F",
          accent: "#181830",
          neutral: "#181830",
          "base-100": "#FFFFFF",
          info: "#3ABFF8",
          success: "#36D399",
          warning: "#FBBD23",
          error: "#F87272",
        },
      },
      {
        dark: {
          primary: "#E0A82E",
          secondary: "#F9D72F",
          accent: "#EA6947",
          neutral: "#23282E",
          "base-100": "#202020",
          info: "#0091D5",
          success: "#6BB187",
          warning: "#DBAE59",
          error: "#AC3E31",
        },
      },
    ],
  },
};
