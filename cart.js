// ALERT FUNCTION
function showStyledAlert(message, type = "success", duration = 2000) {
    const existing = document.querySelector('.custom-alert');
    if (existing) existing.remove();

    const alertDiv = document.createElement('div');
    alertDiv.className = `custom-alert ${type}`;
    alertDiv.innerHTML = `
        <div class="alert-content">
            <span class="alert-message">${message}</span>
        </div>
    `;

    document.body.appendChild(alertDiv);

    setTimeout(() => alertDiv.classList.add('show'), 50);

    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 400);
    }, duration);
}

document.addEventListener('DOMContentLoaded', () => {//Runs the code only after HTML is fully loaded

    // ADD TO CART
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); //Stops form submission / page reload

            const form = this.closest('form');

            showStyledAlert(` added successfully`, 'success');

            // submit AFTER alert appears
            setTimeout(() => form.submit(), 700);
        });
    });

    // REMOVE ITEM
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); 

            const form = this.closest('form');
            showStyledAlert(`removed from the cart`, 'error');

            setTimeout(() => form.submit(), 700);
        });
    });
});