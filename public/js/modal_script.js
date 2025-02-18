// In modal_script.js
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('registrationModal');
    const closeBtn = document.getElementsByClassName('close')[0];
    const form = document.getElementById('registrationForm');
    
    window.openRegistrationModal = function(eventId) {
        modal.style.display = 'block';
        document.getElementById('eventId').value = eventId;
    };
    
    closeBtn.onclick = function() {
        modal.style.display = 'none';
    };
    
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        console.log('Submitting to:', this.action);
        
        // Log form data for debugging
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        fetch('/golden_rule_calendar_project/controllers/registrationController.php', {  // Changed this line
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    console.log('Error response text:', text);
                    throw new Error('Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Registration successful!');
                modal.style.display = 'none';
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