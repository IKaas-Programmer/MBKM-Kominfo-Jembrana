// resources/js/pegawai-search.js

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const searchForm = document.getElementById("searchForm");
    let timeout = null;

    // Proteksi: Pastikan kode hanya berjalan jika elemen form pencarian ada di halaman tersebut
    if (searchInput && searchForm) {
        // Dengarkan setiap ketukan keyboard/input dari admin
        searchInput.addEventListener("input", function () {
            // Bersihkan jeda waktu sebelumnya jika admin masih mengetik huruf baru
            clearTimeout(timeout);

            // Tunda pengiriman form selama 500 milidetik setelah ketikan terakhir
            timeout = setTimeout(function () {
                searchForm.submit();
            }, 500);
        });

        // Trik Kenyamanan: Letakkan kursor otomatis di akhir teks setelah halaman memuat (reload)
        const valueLength = searchInput.value.length;
        searchInput.focus();
        searchInput.setSelectionRange(valueLength, valueLength);
    }
});
