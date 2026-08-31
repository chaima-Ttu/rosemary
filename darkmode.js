  const modeBtn = document.getElementById("mobtn");

  // Apply saved mode on page load
  const savedMode = localStorage.getItem("mode");
  if (savedMode === "dark") {
    document.body.classList.add("dark");
  }

  // Toggle mode on click
  modeBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark");

    if (document.body.classList.contains("dark")) {
      localStorage.setItem("mode", "dark");
    } else {
      localStorage.setItem("mode", "light");
    }
  });