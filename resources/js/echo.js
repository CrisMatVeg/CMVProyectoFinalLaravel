import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const cfg      = window.__BROADCAST_CONFIG__ ?? {};
const provider = cfg.provider ?? import.meta.env.VITE_CHANNELS_PROVIDER ?? 'reverb';

if (provider === 'pusher') {
    window.Echo = new Echo({
        broadcaster:  'pusher',
        key:          cfg.key     ?? import.meta.env.VITE_PUSHER_APP_KEY,
        cluster:      cfg.cluster ?? import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS:     true,
        authEndpoint: (cfg.appBase ?? '') + '/broadcasting/auth',
    });
} else {
    window.Echo = new Echo({
        broadcaster:       'reverb',
        key:               cfg.key    ?? import.meta.env.VITE_REVERB_APP_KEY,
        wsHost:            cfg.host   ?? import.meta.env.VITE_REVERB_HOST,
        wsPort:            cfg.port   ?? import.meta.env.VITE_REVERB_PORT ?? 8080,
        wssPort:           cfg.port   ?? import.meta.env.VITE_REVERB_PORT ?? 8080,
        forceTLS:          (cfg.scheme ?? import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint:      (cfg.appBase ?? '') + '/broadcasting/auth',
    });
}
