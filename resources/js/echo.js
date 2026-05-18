import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const rc = window.__REVERB_CONFIG__ ?? {};

window.Echo = new Echo({
    broadcaster:       'reverb',
    key:               rc.key      ?? import.meta.env.VITE_REVERB_APP_KEY,
    wsHost:            rc.host     ?? import.meta.env.VITE_REVERB_HOST,
    wsPort:            rc.port     ?? import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort:           rc.port     ?? import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS:          (rc.scheme  ?? import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
    authEndpoint:      (rc.appBase ?? '') + '/broadcasting/auth',
});
