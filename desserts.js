// Simple scroll functionality
let currentIndex = 0;
const itemsPerView = 3;
let items = [];

// Save scroll position before form submits
function saveScrollPosition() {
    localStorage.setItem('dessertScrollIndex', currentIndex);
    return true; // Allow form submission
}

// Save scroll position when clicking product links
function saveScrollBeforeLink() {
    localStorage.setItem('dessertScrollIndex', currentIndex);
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Try to restore saved scroll position
    const savedIndex = localStorage.getItem('dessertScrollIndex');
    if (savedIndex !== null) {
        currentIndex = parseInt(savedIndex);
    }
    
    // Get all items
    items = document.querySelectorAll('.item');
    
    // Update display with saved position
    updateDisplay();
    updateButtonStates();
    
    // Add click handlers to all product links
    document.querySelectorAll('.item a').forEach(link => {
        link.addEventListener('click', saveScrollBeforeLink);
    });
    
    // Add submit handler to form
    const cartForm = document.getElementById('cartForm');
    if (cartForm) {
        cartForm.addEventListener('submit', saveScrollPosition);
    }
});

function scrollUp() {
    if (currentIndex > 0) {
        currentIndex--;
        updateDisplay();
        updateButtonStates();
        localStorage.setItem('dessertScrollIndex', currentIndex);
    }
}

function scrollDown() {
    if (currentIndex < items.length - itemsPerView) {
        currentIndex++;
        updateDisplay();
        updateButtonStates();
        localStorage.setItem('dessertScrollIndex', currentIndex);
    }
}

function updateDisplay() {
    items.forEach(item => {
        item.style.display = 'none';
        item.style.opacity = '0';
    });
    
    for (let i = currentIndex; i < currentIndex + itemsPerView && i < items.length; i++) {
        items[i].style.display = 'flex';
        setTimeout(() => {
            items[i].style.opacity = '1';
        }, 10);
    }
}

function updateButtonStates() {
    const upBtn = document.querySelector('.scroll-up');
    const downBtn = document.querySelector('.scroll-down');
    
    if (upBtn) {
        upBtn.disabled = currentIndex === 0;
        upBtn.style.opacity = currentIndex === 0 ? '0.3' : '1';
        upBtn.style.cursor = currentIndex === 0 ? 'not-allowed' : 'pointer';
    }
    
    if (downBtn) {
        downBtn.disabled = currentIndex >= items.length - itemsPerView;
        downBtn.style.opacity = currentIndex >= items.length - itemsPerView ? '0.3' : '1';
        downBtn.style.cursor = currentIndex >= items.length - itemsPerView ? 'not-allowed' : 'pointer';
    }
}

function changeQty(change) {
    const qtyValue = document.getElementById('qtyValue');
    const quantityInput = document.getElementById('quantityInput');
    let currentQty = parseInt(qtyValue.textContent);
    currentQty += change;
    
    if (currentQty < 1) currentQty = 1;
    
    qtyValue.textContent = currentQty;
    quantityInput.value = currentQty;
}


function selectFlavour(img, name, price) {
    document.getElementById("mainProductImg").src = img;
    document.getElementById("productName").innerText = name;
    document.getElementById("productPrice").innerText = price;

    document.getElementById("flavourInput").value = name;
    document.getElementById("priceInput").value = price;
    document.getElementById("imgInput").value = img;
}

// ALERT FUNCTION - KEEP AS IS

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
        document.getElementById('cartForm').addEventListener('submit', function (e) {
        e.preventDefault(); // stop reload
        showStyledAlert("Added successfully");
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 400);
        }, duration);

        // submit after alert (optional)
        setTimeout(() => {
            this.submit();
        }, 1500);
    });
}
