document.addEventListener("DOMContentLoaded", () => {
  const ShopBtn = document.querySelectorAll(".shop-btn, .shop-btn1, .user-icon");
  const template = document.getElementById("loginTemplate");

  ShopBtn.forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();

      // Prevent opening multiple overlays
      if (document.getElementById("loginOverlay")) return;

      const clone = template.content.cloneNode(true);
      const overlay = clone.querySelector("#loginOverlay");

      document.body.appendChild(clone);
      document.body.classList.add("no-scroll");
      
      // Initialize login logic AFTER it's added to DOM
      setTimeout(() => initLogin(), 10);

      // Setup close functionality
      setupCloseHandlers();
    });
  });
});

window.addEventListener("scroll", () => {
  document.querySelector(".navbar").classList.toggle("scrolled", window.scrollY > 50);
});

/*///////////////////*/

// SHARED CLOSE FUNCTION
function setupCloseHandlers() {
  const overlay = document.getElementById("loginOverlay");
  if (!overlay) return;

  const box = overlay.querySelector("#register-box");
  
  function closeLogin() {
    if (!box) return;

    box.style.transition = "all 0.5s cubic-bezier(0.4, 0, 0.2, 1)";
    box.style.opacity = "0";
    box.style.transform = "translateY(-30px) scale(0.95)";

    overlay.style.transition = "opacity 0.5s ease";
    overlay.style.opacity = "0";

    setTimeout(() => {
      if (overlay.parentNode) {
        overlay.remove();
      }
      document.body.classList.remove("no-scroll");
    }, 500);
  }

  // Close button click
  const closeBtn = overlay.querySelector("#closeBtn");
  if (closeBtn) {
    closeBtn.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      closeLogin();
    });
  }

  // Click outside the login box
  overlay.addEventListener("click", (e) => {
    const registerBox = overlay.querySelector("#register-box");
    if (registerBox && !registerBox.contains(e.target)) {
      closeLogin();
    }
  });

  // Prevent clicks inside the box from closing
  const registerBox = overlay.querySelector("#register-box");
  if (registerBox) {
    registerBox.addEventListener("click", (e) => {
      e.stopPropagation();
    });
  }
}

function showLogin() {
  if (isLoggedIn) return; 
  if (document.getElementById("loginOverlay")) return; // Don't open if already open

  const template = document.getElementById("loginTemplate");
  if (!template) return;

  const clone = template.content.cloneNode(true);
  document.body.appendChild(clone);
  document.body.classList.add("no-scroll");

  // Initialize login logic AND close handlers
  setTimeout(() => {
    initLogin();
    setupCloseHandlers(); // ← THIS WAS MISSING
  }, 10);
}

function handleShopNow(e) {
  e.preventDefault();

  if (!isLoggedIn) {
    showLogin();
  } else {
    window.location.href = "menu.html";
  }
}

function handleAddToCart() {
  if (!isLoggedIn) {
    showLogin();
  }
}