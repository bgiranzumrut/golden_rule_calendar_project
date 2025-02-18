<!-- views/modal_form.php -->

<div id="registrationModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Register for Event</h2>
        <form id="registrationForm" method="POST" action="../controllers/registrationController.php">
            <input type="hidden" name="action" value="register">
            <input type="hidden" name="event_id" id="eventId">
            
            <div class="form-group">
                <label for="name">Enter your name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="phone">Enter your phone number:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="notes">Type your notes:</label>
                <textarea id="notes" name="notes"></textarea>
            </div>

            <button type="submit" class="submit-btn">Click to Join</button>
        </form>
    </div>
</div>