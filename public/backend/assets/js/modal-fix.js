/**
 * Modal Fix - Prevents modal backdrop from blocking interaction with other elements
 */

"use strict";

// Fix for modal backdrop issue
document.addEventListener('DOMContentLoaded', function() {
    // When the modal is shown, we'll modify the backdrop
    $(document).on('shown.bs.modal', '#allUsersModal', function() {
        // Make the backdrop clickable by setting pointer-events to auto
        $('.modal-backdrop').css('pointer-events', 'none');
        
        // Ensure the modal itself still captures clicks
        $('.modal-dialog').css('pointer-events', 'auto');
    });
    
    // When the modal is hidden, reset the backdrop
    $(document).on('hidden.bs.modal', '#allUsersModal', function() {
        $('.modal-backdrop').css('pointer-events', 'auto');
    });
});