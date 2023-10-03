// Ambil elemen-elemen yang diperlukan untuk mengubah role
const modal = document.getElementById("roleModal");
const showModalButtons = document.getElementsByClassName("show-role-modal");
const dropdownrole = document.getElementById("dropdown-role");
const modalForm = document.getElementById("modal-form");

// Fungsi untuk menampilkan modal dan mengisi dropdown
function showModal(role, niplama) {
    // Hapus semua opsi yang ada di dropdown
    dropdownrole.innerHTML = "";

    // Daftar opsi untuk dropdown
    const roleOption = ["1", "2", "3", "4"];

    // Buat opsi-opsi dropdown
    for (let i = 0; i < roleOption.length; i++) {
        const option = document.createElement("option");
        option.value = roleOption[i];
        switch (roleOption[i]) {
            case "1":
                option.text = "Super Admin";
                option.disabled = true;
                break;
            case "2":
                option.text = "Admin";
                break;
            case "3":
                option.text = "Operator";
                break;
            case "4":
                option.text = "Viewer";
                break;
        }
        if (roleOption[i] === role) {
            option.selected = true; // Pilih opsi sesuai dengan data baris tabel
        }
        dropdownrole.appendChild(option);
    }

    // Atur action pada form sesuai dengan data
    modalForm.action = modalForm.action + "update/" + niplama;

    // Tampilkan modal
    modal.style.display = "block";
}

// Tambahkan event listener pada tombol-tombol yang mengaktifkan modal
for (let i = 0; i < showModalButtons.length; i++) {
    showModalButtons[i].addEventListener("click", function () {
        const role = this.getAttribute("data-role");
        const niplama = this.getAttribute("data-niplama");
        showModal(role, niplama);
    });
}

// Event listener untuk menutup modal
const closeBtn = document.getElementsByClassName("close-ubah-role")[0];
closeBtn.addEventListener("click", function () {
    modal.style.display = "none";
});




