import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                "custom-yellow": "#FFC600",
                "custom-grey": "#6E6E6E",
                "custom-dark-grey": "#1b1b1b",
            },
            height: {
                640: "640px",
            },
            gridTemplateColumns: {
                14: "repeat(14, minmax(0, 1fr))",
                15: "repeat(15, minmax(0, 1fr))",
            },
        },
    },
    plugins: [],
};
