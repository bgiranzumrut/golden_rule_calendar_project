<!-- views/modal_form.php -->
<div id="registrationModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <!-- Add event title section -->
        <div id="eventTitleSection" class="event-title-section">
            <h2 id="eventTitle">Loading event...。。。</h2>
            <div id="eventTime" class="event-time"></div>
        </div>

        <div class="modal-grid">
            <!-- Left side - Registration Form -->
            <div class="form-section">
                <h2>Register for Event</h2>
                <form id="registrationForm" method="POST">
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
            
            <!-- Right side - Participants List -->
            <div class="participants-section">
                <h3>Registered Participants</h3>
                <div id="participantsCount" class="participants-count">
                    Total Participants: <span id="totalCount">0</span>
                </div>
                <div id="participantsList">
                    <!-- Participants will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>