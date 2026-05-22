// resources/js/auth/login-toggle.js

document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("password");
    const toggleButton = document.getElementById("togglePassword");
    const eyeOpenIcon = document.getElementById("eyeOpenIcon");
    const eyeCloseIcon = document.getElementById("eyeCloseIcon");

    // Pastikan elemen ada di halaman sebelum memasang event listener
    if (toggleButton && passwordInput) {
        toggleButton.addEventListener("click", function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeOpenIcon.classList.add("hidden");
                eyeCloseIcon.classList.remove("hidden");
            } else {
                passwordInput.type = "password";
                eyeOpenIcon.classList.remove("hidden");
                eyeCloseIcon.classList.add("hidden");
            }
        });
    }
});
