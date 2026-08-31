document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('userInfoForm');
    if (!form) return;

    // ---------------- HELPERS ----------------
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.style.display = 'none';
        });
    }

    function showError(id, message) {
        const el = document.getElementById(id + 'Error');
        if (el) {
            el.textContent = message;
            el.style.display = 'block';
        }
    }

    // Show styled alert function
    function showStyledAlert(message, type = "success") {
        let existingAlert = document.querySelector('.custom-alert');
        if (existingAlert) existingAlert.remove();
        
        let alertDiv = document.createElement('div');
        alertDiv.className = `custom-alert ${type}`;
        alertDiv.innerHTML = `
            <div class="alert-content">
                <span class="alert-message">${message}</span>
                <button class="alert-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        setTimeout(() => alertDiv.classList.add('show'), 10);
        setTimeout(() => {
            if (alertDiv.parentElement) {
                alertDiv.classList.remove('show');
                setTimeout(() => {
                    if (alertDiv.parentElement) alertDiv.remove();
                }, 300);
            }
        }, 5000);
    }

    function isNotEmpty(val) {
        return val && val.trim() !== '';
    }

    function isValidPhone(phone) {
        const cleaned = phone.replace(/[\s\-\(\)]/g, '');
        return /^[\+]?[1-9][\d]{9,15}$/.test(cleaned);
    }

    function isValidCreditCard(card) {
        const cleaned = card.replace(/\s/g, '');
        return /^\d{13,19}$/.test(cleaned);
    }

    // ---------------- SUBMIT ----------------
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Always prevent default first
        clearErrors();
        let isValid = true;

        const phone = document.getElementById('userPhone').value;
        const street = document.getElementById('street').value;
        const building = document.getElementById('userBuilding').value;
        const creditCard = document.getElementById('creditCard').value;

        if (!isNotEmpty(phone)) {
            showError('userPhone', 'Phone number is required');
            isValid = false;
        } else if (!isValidPhone(phone)) {
            showError('userPhone', 'Invalid phone number');
            isValid = false;
        }

        if (!isNotEmpty(street)) {
            showError('street', 'Street name is required');
            isValid = false;
        }

        if (!isNotEmpty(building)) {
            showError('userBuilding', 'Building number is required');
            isValid = false;
        }

        if (!isNotEmpty(creditCard)) {
            showError('creditCard', 'Credit card number is required');
            isValid = false;
        } else if (!isValidCreditCard(creditCard)) {
            showError('creditCard', 'Invalid card number');
            isValid = false;
        }

        // Check validation result
        if (!isValid) {
            // Show error alert if validation fails
            showStyledAlert('Please fill all required fields correctly.', 'error');
        } else {
            // If valid, show success and submit
            showStyledAlert('Your order is confirmed! Our delivery agent will call you soon.');
            setTimeout(() => {
                form.submit(); // Submit to server after showing message
            }, 1500);
        }
    });

    // ---------------- CANCEL ----------------
    const cancelBtn = document.getElementById('cancelBtn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function () {
            if (confirm('Are you sure you want to cancel?')) {
                window.location.href = 'menu.html';
            }
        });
    }
});