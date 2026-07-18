import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import os from 'os'; // Import modul OS dari Node.js

// Fungsi buat nyari IP lokal otomatis
function getLocalIP() {
    const interfaces = os.networkInterfaces();
    for (const name of Object.keys(interfaces)) {
        for (const iface of interfaces[name]) {
            // Ambil IP versi 4 dan pastiin bukan localhost (127.0.0.1)
            if (iface.family === 'IPv4' && !iface.internal) {
                return iface.address;
            }
        }
    }
    return 'localhost';
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5174,
        cors: true,
        hmr: {
            host: getLocalIP(), // Sekarang IP-nya otomatis ngikutin IP laptop lo
        },
    },
});