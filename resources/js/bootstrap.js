import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Import Echo and Pusher
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Initialize Pusher
window.Pusher = Pusher;

// Initialize Laravel Echo if Pusher key is available
// First check if process.env is defined
if (typeof process !== 'undefined' && process.env && process.env.MIX_PUSHER_APP_KEY) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        forceTLS: true
    });
} else {
    // Try to get values from window.Laravel if defined
    const pusherKey = window.Laravel?.pusherKey || '{{ env("PUSHER_APP_KEY") }}';
    const pusherCluster = window.Laravel?.pusherCluster || '{{ env("PUSHER_APP_CLUSTER") }}';
    
    if (pusherKey && pusherKey !== '{{ env("PUSHER_APP_KEY") }}') {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: pusherKey,
            cluster: pusherCluster,
            forceTLS: true
        });
    } else {
        console.warn('Pusher configuration is missing. Real-time messaging will not work.');
    }
}
