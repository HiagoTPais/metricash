import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.ts',
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    define: {
        global: 'window',
        'process.env': {},
    },
    optimizeDeps: {
        include: [
            'buffer',
            'process',
            'bip39',
            'bip32',
            'bitcoinjs-lib',
            'ethereumjs-util',
            '@ethersproject/address',
        ], // 👈 inclui dependências críticas no pré-bundle
    },
});
