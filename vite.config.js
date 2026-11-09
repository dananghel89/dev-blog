import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

/**
 * Vite 8 minifies CSS with Lightning CSS, which keeps only a /*! … *\/ comment at the very top of a file.
 * That would drop the license banners of Bootstrap, Bootstrap Icons and the Blogy (Untree.co) partials,
 * so collect each CSS module's banners and put them back at the top of the CSS file it ends up in.
 */
function keepCssLicenseComments() {
    const banners = new Map();

    return {
        name: 'keep-css-license-comments',
        apply: 'build',
        transform(code, id) {
            if (/\.s?css(\?|$)/.test(id)) {
                const found = code.match(/\/\*![\s\S]*?\*\//g);
                if (found) banners.set(id, found);
            }
        },
        generateBundle(options, bundle) {
            for (const chunk of Object.values(bundle)) {
                const cssFiles = chunk.type === 'chunk' ? chunk.viteMetadata?.importedCss : null;
                if (!cssFiles?.size) continue;

                const header = [...new Set(chunk.moduleIds.flatMap((id) => banners.get(id) ?? []))].join('\n');
                for (const file of cssFiles) {
                    const asset = bundle[file];
                    if (header && asset?.type === 'asset') {
                        // Drop the one top banner Lightning CSS kept, so it isn't repeated.
                        asset.source = `${header}\n${String(asset.source).replace(/^\/\*![\s\S]*?\*\/\n?/, '')}`;
                    }
                }
            }
        },
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        keepCssLicenseComments(),
    ],
    build: {
        rolldownOptions: {
            output: {
                // Keep /*! … */ license comments (Untree.co, Bootstrap) in the minified JS.
                comments: { legal: true },
            },
        },
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
