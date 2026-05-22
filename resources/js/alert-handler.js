document.addEventListener("DOMContentLoaded", function () {
    const alertElement = document.getElementById("success-alert");

    if (alertElement) {
        // Otomatis sembunyikan setelah 4 detik
        setTimeout(function () {
            dismissAlert();
        }, 4000);
    }
});

// Daftarkan fungsi ke objek window agar bisa dipanggil oleh attribute onclick="" di HTML
window.dismissAlert = function () {
    const alertElement = document.getElementById("success-alert");
    if (alertElement) {
        alertElement.style.opacity = "0";
        alertElement.style.maxHeight = "0px";
        alertElement.style.padding = "0px";
        alertElement.style.marginTop = "0px";
        alertElement.style.marginBottom = "0px";
        alertElement.style.borderWidth = "0px";

        setTimeout(function () {
            alertElement.remove();
        }, 500);
    }
};
