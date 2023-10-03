// Ambil elemen-elemen yang diperlukan
const modalPermission = document.getElementById("myPermissionModal");
const showPermissionModalButtons = document.getElementsByClassName("show-permission-modal");
const checkboxContainer = document.getElementById("checkbox-container");
const modalPermissionForm = document.getElementById("modal-permission-form");

// Fungsi untuk menampilkan modal dengan checkbox
function showPermissionModal(dataArray, role) {
    // Hapus semua checkbox yang ada dalam modal
    checkboxContainer.innerHTML = "";

    arrayPermission = ["View", "upload-tabel", "assign-pegawai", "monitoring-putaran"];

    arrayPermission.forEach(function (value) {
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.className = "form-check-input";
        checkboxContainer.appendChild(checkbox);
        checkbox.value = arrayPermission.indexOf(value) + 1;
        checkbox.name = value;

        dataArray.forEach(function (data) {
            if (arrayPermission[data - 1] === value) {
                checkbox.checked = true;
            }
        });

        const label = document.createElement("label");
        label.innerHTML = value;
        label.className = "form-check-label";
        label.setAttribute("for", "checkbox" + value);
        checkboxContainer.appendChild(label);

        const brrr = document.createElement("br");
        checkboxContainer.appendChild(brrr);
    });

    modalPermissionForm.action = modalPermissionForm.action + "update/" + role;

    // Tampilkan modal
    modalPermission.style.display = "block";
}

// Tambahkan event listener pada tombol-tombol yang mengaktifkan modal
for (let i = 0; i < showPermissionModalButtons.length; i++) {
    showPermissionModalButtons[i].addEventListener("click", function () {
        const role = this.getAttribute("data-role");
        const dataAttribute = JSON.parse(this.getAttribute("data-arrayPermission"));
        showPermissionModal(dataAttribute, role);
    });
}

// Event listener untuk menutup modal
const closePermissionBtn = document.getElementsByClassName("closeModal")[0];
closePermissionBtn.addEventListener("click", function () {
    modalPermission.style.display = "none";
});
