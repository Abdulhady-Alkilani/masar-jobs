import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; // !!! استيراد إضافة Vue !!!

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', // قد يكون لديك ملف CSS بدلاً من Sass أو كلاهما
                'resources/sass/app.scss', // لـ Bootstrap
                'resources/js/app.js',     // لـ Bootstrap JS و Vue app initialization
            ],
            refresh: true,
        }),
        vue({ // !!! إضافة Vue plugin هنا !!!
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    // resolve: {
    //     alias: {
    //         //  '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
    //     }
    // },
});