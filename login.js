
function initLogin() {
  const namefeild = document.getElementById("namefeild");
  const passwdfeild = document.getElementById("passwdfeild");
  const emailField = document.getElementById("emailField");
  const title = document.getElementById("Title");
  const parseText = document.getElementById("parse");
  const registration = document.getElementById("go-register");
  const forgetpswd = document.getElementById("forget");
  const submitBtn = document.getElementById("submitBtn");
  const closebtn = document.getElementById("closeBtn");
  const authForm = document.getElementById("authForm");
  const errorDiv = document.getElementById("error-message");

  const username = document.getElementById("username");
  const password = document.getElementById("password");
  const email = document.getElementById("email");

  let isLogin = false;
  let isForget = false;

  // Check for error message in URL
  const urlParams = new URLSearchParams(window.location.search);
  const errorMsg = urlParams.get('error');
  if (errorMsg) {
    errorDiv.textContent = decodeURIComponent(errorMsg);
    errorDiv.style.display = 'block';
    
    // Remove error from URL after 5 seconds
    setTimeout(() => {
      errorDiv.style.display = 'none';
      window.history.replaceState({}, document.title, window.location.pathname);
    }, 5000);
  }

  // Toggle between Login and Signup
  registration.onclick = (e) => {
    e.preventDefault();
    errorDiv.style.display = 'none'; // Hide any error messages
    
    if (isForget) {
      restore();
      return;
    }

    if (!isLogin) {
      // Switch to Login Mode
      namefeild.style.maxHeight = "0";
      namefeild.style.opacity = "0";
      namefeild.style.overflow = "hidden";
      namefeild.style.marginBottom = "0";
      
      title.textContent = "Login";
      registration.textContent = "Sign Up";
      parseText.textContent = "Create account?";
      isLogin = true;

      // Remove required and disable username field
      username.removeAttribute('required');
      username.disabled = true;
      username.value = '';
      email.focus();

      // Change form action and button
      authForm.action = "signup.php";
      submitBtn.name = "login";
      submitBtn.textContent = "Login";
      
    } else {
      // Switch back to Sign Up Mode
      restore();
    }
  };

  // Forgot Password
  forgetpswd.onclick = (e) => {
    e.preventDefault();
    errorDiv.style.display = 'none';
    
    namefeild.style.maxHeight = "0";
    namefeild.style.opacity = "0";
    namefeild.style.overflow = "hidden";
    passwdfeild.style.maxHeight = "0";
    passwdfeild.style.opacity = "0";
    passwdfeild.style.overflow = "hidden";
    
    title.textContent = "Reset Password";
    registration.textContent = "Back";
    parseText.textContent = "";
    isForget = true;

    username.removeAttribute('required');
    password.removeAttribute('required');
    username.disabled = true;
    password.disabled = true;
    username.value = '';
    password.value = '';
    email.focus();

    // Hide submit button for password reset
    submitBtn.style.display = 'none';
  };

  // Restore to Sign Up form
  function restore() {
    namefeild.style.maxHeight = "65px";
    namefeild.style.opacity = "1";
    namefeild.style.overflow = "visible";
    namefeild.style.marginBottom = "";
    
    passwdfeild.style.maxHeight = "65px";
    passwdfeild.style.opacity = "1";
    passwdfeild.style.overflow = "visible";
    
    title.textContent = "Sign Up";
    registration.textContent = "Login";
    parseText.textContent = "Already have an account?";
    isLogin = false;
    isForget = false;

    username.setAttribute('required', 'required');
    username.disabled = false;
    password.setAttribute('required', 'required');
    password.disabled = false;
    email.setAttribute('required', 'required');

    authForm.action = "signup.php";
    submitBtn.name = "signup";
    submitBtn.textContent = "Sign Up";
    submitBtn.style.display = 'block';
    
    errorDiv.style.display = 'none';
  }

  // Close button
  closebtn.onclick = () => {
    document.getElementById("register-box").style.display = "none";
    restore();
  };

  // Form submission handler
  authForm.onsubmit = (e) => {
    // If in forgot password mode, prevent submission
    if (isForget) {
      e.preventDefault();
      errorDiv.textContent = 'Password reset functionality coming soon!';
      errorDiv.style.background = '#ff9800';
      errorDiv.style.display = 'block';
      setTimeout(() => {
        errorDiv.style.display = 'none';
        errorDiv.style.background = '#ff4444';
      }, 3000);
      return false;
    }
    
    // Clear username value if in login mode (so it's not sent to server)
    if (isLogin) {
      username.value = '';
    }
    
    // Form will submit normally
    return true;
  };
}