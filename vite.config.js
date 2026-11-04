import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // Keep /*! … */ license comments (Untree.co, Bootstrap, Bootstrap Icons) in the minified CSS/JS.
    esbuild: {
        legalComments: 'inline',
    },
    css: {
        preprocessorOptions: {
            scss: {
                // Bootstrap 5.3 and the Blogy partials still use @import and global colour functions.
                quietDeps: true,
                silenceDeprecations: ['import', 'global-builtin', 'color-functions'],
            },
        },
    },
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
