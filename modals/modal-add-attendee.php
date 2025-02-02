<?php
    debug_backtrace() || die (); 
?>
<!-- Attendee Add Modal -->
<div class="modal fade" id="joinEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="joinEventTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="joinEventForm">
                    <input type="hidden" id="eventId" name="event_id">
                    <input type="hidden" id="eventName" name="event_name">
                    <input type="hidden" id="eventAddress" name="event_address">
                    <input type="hidden" id="eventTime" name="event_time">
                    <div class="mb-3">
                        <label for="attendeeName" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="attendeeName" autocomplete = "off" required>
                    </div>
                    <div class="mb-3">
                        <label for="attendeeEmail" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="attendeeEmail" autocomplete = "off" required>
                    </div>
                    <button type="submit" class="btn btn-success">Join Event</button>
                </form>
            </div>
        </div>
    </div>
</div>