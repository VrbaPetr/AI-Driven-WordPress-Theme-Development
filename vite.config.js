import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [tailwindcss()],
    build: {
        outDir: 'assets',
        assetsDir: '',
        emptyOutDir: false,
        manifest: true,
        rollupOptions: {
            input: [
                'src/css/main.css',
                'src/js/critical.js',
                'src/js/main.js',
            ],
            output: {
                assetFileNames: 'css/[name]-[hash][extname]',
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
            },
        },
    },
});
