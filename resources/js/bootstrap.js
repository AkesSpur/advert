import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Import Echo and Pusher
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Initialize Pusher
window.Pusher = Pusher;

// Initialize Laravel Echo
// First check if window.Laravel is defined (from pusher-config.blade.php)
if (window.Laravel?.pusherKey) {
    console.log('Initializing Echo with Laravel config');
    
    // Enable Pusher logging for debugging
    Pusher.logToConsole = true;
    
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: window.Laravel.pusherKey,
        cluster: window.Laravel.pusherCluster,
        forceTLS: true,
        enabledTransports: ['ws', 'wss'],
        disableStats: true
    });
    
    // Add connection event handlers
    const pusher = window.Echo.connector.pusher;
    pusher.connection.bind('connected', () => {
        console.log('Successfully connected to Pusher');
    });
    pusher.connection.bind('error', (err) => {
        console.error('Pusher connection error:', err);
    });
} 
// Fallback to Vite env variables if window.Laravel is not available
else if (import.meta.env.VITE_PUSHER_APP_KEY) {
    console.log('Initializing Echo with Vite env variables');
    
    // Enable Pusher logging for debugging
    Pusher.logToConsole = true;
    
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
        enabledTransports: ['ws', 'wss'],
        disableStats: true
    });
    
    // Add connection event handlers
    const pusher = window.Echo.connector.pusher;
    pusher.connection.bind('connected', () => {
        console.log('Successfully connected to Pusher (via Vite env)');
    });
    pusher.connection.bind('error', (err) => {
        console.error('Pusher connection error (Vite env):', err);
    });
} else {
    console.warn('Pusher configuration is missing. Real-time messaging will not work.');
}
