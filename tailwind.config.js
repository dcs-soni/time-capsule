/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/**/*.antlers.html", "./resources/**/*.js"],
    theme: {
        extend: {},
    },
    plugins: [require("@tailwindcss/typography")],
};
