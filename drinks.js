function changeValue(type, delta) {

    // SPECIAL CASE: SIZE (S / M / L)
    if (type === 'size') {
        const sizes = ['S', 'M', 'L'];
        const valueSpan = document.getElementById('sizeValue');
        const hiddenInput = document.getElementById('sizeInput');

        let currentIndex = sizes.indexOf(valueSpan.innerText);
        if (currentIndex === -1) currentIndex = 1; // default M

        let newIndex = currentIndex + delta;

        // clamp
        if (newIndex < 0) newIndex = 0;
        if (newIndex > sizes.length - 1) newIndex = sizes.length - 1;

        valueSpan.innerText = sizes[newIndex];
        hiddenInput.value = sizes[newIndex];
        return;
    }

    // DEFAULT CASE (numbers)
    const valueSpan = document.getElementById(type + 'Value');
    const hiddenInput = document.getElementById(type + 'Input');

    let currentValue = parseInt(valueSpan.innerText);
    let newValue = currentValue + delta;

    if (newValue < 1) newValue = 1;

    valueSpan.innerText = newValue;
    hiddenInput.value = newValue;
}


// Select flavour from grid
function selectFlavour(img, name, price, productId) {
    document.getElementById("mainImg").src = img;
    document.getElementById("productName").innerText = name;
    document.getElementById("productPrice").innerText = price;
    document.getElementById("flavourName").value = name;
    document.getElementById("priceInput").value = price;
    document.getElementById("imgPath").value = img;
    
    if (productId) {
        document.getElementById("productId").value = productId;
    }
}

// Scroll functionality
let currentIndex = 0;
const itemsPerView = 3;
let items = [];

function scrollUp() {
    if (currentIndex > 0) {
        currentIndex--;
        updateDisplay();
        updateButtonStates();
        localStorage.setItem('drinkScrollIndex', currentIndex);
    }
}

function scrollDown() {
    if (currentIndex < items.length - itemsPerView) {
        currentIndex++;
        updateDisplay();
        updateButtonStates();
        localStorage.setItem('drinkScrollIndex', currentIndex);
    }
}

function updateDisplay() {
    if (!items || items.length === 0) return;
    
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
    const upBtn = document.querySelector('.scroll-btn:first-of-type');
    const downBtn = document.querySelector('.scroll-btn.scroll-down');
    
    if (upBtn && items.length > 0) {
        upBtn.disabled = currentIndex === 0;
        upBtn.style.opacity = currentIndex === 0 ? '0.3' : '1';
    }
    
    if (downBtn && items.length > 0) {
        downBtn.disabled = currentIndex >= items.length - itemsPerView;
        downBtn.style.opacity = currentIndex >= items.length - itemsPerView ? '0.3' : '1';
    }
}

// Initialize everything
document.addEventListener('DOMContentLoaded', function() {
    // Restore scroll position
    const savedIndex = localStorage.getItem('drinkScrollIndex');
    if (savedIndex !== null) {
        currentIndex = parseInt(savedIndex);
    }
    
    // Setup scroll
    items = document.querySelectorAll('.item');
    updateDisplay();
    updateButtonStates();
    
    // Add scroll button listeners
    const scrollUpBtn = document.querySelector('.scroll-btn:first-of-type');
    const scrollDownBtn = document.querySelector('.scroll-btn.scroll-down');
    
    if (scrollUpBtn) scrollUpBtn.addEventListener('click', scrollUp);
    if (scrollDownBtn) scrollDownBtn.addEventListener('click', scrollDown);
    
    // Add to cart with alert
    document.querySelectorAll('.add-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const
            form = this.closest('form');
            showStyledAlert('Added successfully!', 'success');
            setTimeout(() => form.submit(), 700);
        });
    });
});

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
        document.getElementById('drinkform').addEventListener('submit', function (e) {
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