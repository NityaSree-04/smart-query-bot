/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./index.html",
        "./src/**/*.{js,ts,jsx,tsx}",
    ],
    theme: {
        extend: {
            colors: {
                'teal-deep': '#1a4d4d',
                'teal-dark': '#0a2828',
                'cyan-glow': '#00d9ff',
                'dark-blue': '#0a1628',
            },
            boxShadow: {
                'glass': '0 8px 32px 0 rgba(0, 217, 255, 0.1)',
                'glow-cyan': '0 0 20px rgba(0, 217, 255, 0.3)',
                'glow-cyan-strong': '0 0 30px rgba(0, 217, 255, 0.5)',
            },
        },
    },
    plugins: [],
}
