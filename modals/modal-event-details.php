<?php
    debug_backtrace() || die (); 
?>
<!-- Event Details Modal -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailsTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img id="eventDetailsImage" class="img-fluid w-100 mb-3" alt="">
                <p id="eventDetailsText"></p>
                <p><strong>📍 Address:</strong> <span id="eventDetailsAddress"></span></p>
                <p><strong>🕒 Date & Time:</strong> <span id="eventDetailsDate"></span></p>
            </div>
        </div>
    </div>
</div>