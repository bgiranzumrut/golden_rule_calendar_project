document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('registrationModal');
    const closeBtn = document.getElementsByClassName('close')[0];
    const form = document.getElementById('registrationForm');
    
    function loadParticipants(eventId) {
        fetch(`/golden_rule_calendar_project/controllers/registrationController.php?action=getParticipants&event_id=${eventId}`)
            .then(response => response.json())
            .then(data => {
                const participantsList = document.getElementById('participantsList');
                const totalCount = document.getElementById('totalCount');
                participantsList.innerHTML = ''; // Clear existing list
                `<h1> ${data.participants.length} </h1>`
                if (data.participants && data.participants.length > 0) {
                    totalCount.textContent = data.participants.length;

                    data.participants.forEach(participant => {
                        const participantDiv = document.createElement('div');
                        participantDiv.className = 'participant-item';
                        participantDiv.innerHTML = `
                            <div class="participant-name">${participant.participant_name}</div>
                        `;
                        participantsList.appendChild(participantDiv);
                    });
                } else {
                    totalCount.textContent = '0';
                    participantsList.innerHTML = '<p>No participants registered yet.</p>';
                }
            })
            .catch(error => {
                console.error('Error loading participants:', error);
                document.getElementById('participantsList').innerHTML = 
                    '<p>Error loading participants. Please try again later.</p>';
            });
    }
    
    window.openRegistrationModal = function(eventId) {
        modal.style.display = 'block';
        document.getElementById('eventId').value = eventId;
        loadParticipants(eventId); // Load participants when opening modal
    };
    // Close button functionality
    closeBtn.onclick = function() {
        modal.style.display = 'none';
    };
    
    // Close when clicking outside
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const eventId = document.getElementById('eventId').value;
        
        fetch('/golden_rule_calendar_project/controllers/registrationController.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Registration successful!');
                loadParticipants(eventId); // Reload participants after successful registration
                this.reset(); // Clear the form
            } else {
                alert('Registration failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred during registration');
        });
    });
});