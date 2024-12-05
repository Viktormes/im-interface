import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        colors: {
            white: '#ffffff',
            black: '#000000',
            neutral: {
                '1': '#121212',
                '10': '#1B1B1B',
                '20': '#303030',
                '30': '#474747',
                '40': '#5E5E5E',
                '50': '#747474',
                '60': '#919191',
                '70': '#ABABAB',
                '80': '#C6C6C6',
                '90': '#E2E2E2',
                '95': '#F1F1F1',
                '99': '#F9F9F9',
            },
            healthcare: {
                DEFAULT: '#005B89',
                '10': '#021E2D',
                '20': '#04344D',
                '30': '#064B70',
                '40': '#005B89',
                '50': '#3A7B9E',
                '60': '#6497B3',
                '70': '#8AB1C5',
                '80': '#ADC8D6',
                '90': '#D7E4EC',
                '95': '#EBF2F5',
                '99': '#F7FAFB',
            },
            culture: {
                DEFAULT: '#C72B2A',
                '10': '#380C0C',
                '20': '#5F1414',
                '30': '#891D1D',
                '40': '#C72B2A',
                '50': '#CE4444',
                '60': '#DA7272',
                '70': '#E49797',
                '80': '#EDBABA',
                '90': '#F6DDDD',
                '95': '#FAEEEE',
                '99': '#FDF8F8',
            },
            green: {
                DEFAULT: '#43A447',
                '10': '#0D200E',
                '20': '#173718',
                '30': '#215023',
                '40': '#2C6B2E',
                '50': '#37873A',
                '60': '#43A447',
                '70': '#4FC254',
                '80': '#7ADC7E',
                '90': '#C0EEC2',
                '95': '#E0F7E1',
                '99': '#F3FCF3',
            },
            purple: {
                DEFAULT: '#9575CD',
                '10': '#22133C',
                '20': '#3A2168',
                '30': '#553097',
                '40': '#6F45BB',
                '50': '#8663C6',
                '60': '#9575CD',
                '70': '#B7A1DD',
                '80': '#D1C4E9',
                '90': '#E6DFF3',
                '95': '#F3EFF9',
                '99': '#FAF9FD',
            },
            orange: {
                DEFAULT: '#FD5930',
                '10': '#32120A',
                '20': '#561E10',
                '30': '#7D2C18',
                '40': '#A63A1F',
                '50': '#CE4927',
                '60': '#FD5930',
                '70': '#FE8B6F',
                '80': '#FEB5A2',
                '90': '#FECCBF',
                '95': '#FFEDE8',
                '99': '#FFF8F6',
            },
            yellow: {
                DEFAULT: '#FFC107',
                '10': '#2B1708',
                '20': '#4C270E',
                '30': '#6D3912',
                '40': '#8D4E18',
                '50': '#AC641C',
                '60': '#CF7E1F',
                '70': '#EF9820',
                '80': '#FFC107',
                '90': '#FFDD84',
                '95': '#FFECB3',
                '99': '#FFF9E7',
            },
        },
        extend: {
            fontFamily: {
                sans: ['Verdana', ...defaultTheme.fontFamily.sans],
                heading: ['VGRSans', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                '2xs': 10,
            },
        },
    },

    plugins: [forms, typography],
};
