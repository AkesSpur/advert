<script>
    // Make Pusher configuration available to JavaScript
    window.Laravel = window.Laravel || {};
    window.Laravel.pusherKey = '{{ env("PUSHER_APP_KEY") }}';
    window.Laravel.pusherCluster = '{{ env("PUSHER_APP_CLUSTER") }}';
    
    // Debug Pusher configuration
    // console.log('Pusher configuration loaded:', {
    //     key: window.Laravel.pusherKey,
    //     cluster: window.Laravel.pusherCluster
    // });
</script>