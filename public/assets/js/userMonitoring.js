// Ambil elemen-elemen yang diperlukan untuk mengubah role
const closeBtn = document.getElementsByClassName("close-ubah-role")[0];
const modal = document.getElementById("roleModal");
const showModalButtons = document.getElementsByClassName("show-role-modal");
const dropdownrole = document.getElementById("dropdown-role");
const modalForm = document.getElementById("modal-form");
let modalFormLama;
if (modalForm) {
  modalFormLama = modalForm.action;
}

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
if (closeBtn) {
  closeBtn.addEventListener("click", function () {
    modalForm.action = modalFormLama;
    modal.style.display = "none";
  });
}

// Ambil elemen-elemen yang diperlukan
const modalPermission = document.getElementById("myPermissionModal");
const showPermissionModalButtons = document.getElementsByClassName(
  "show-permission-modal"
);
const checkboxContainer = document.getElementById("checkbox-container");
const modalPermissionForm = document.getElementById("modal-permission-form");
let modalPermissionFormLama;
if (modalPermissionForm) {
  modalPermissionFormLama = modalPermissionForm.action;
}

// Fungsi untuk menampilkan modal dengan checkbox
function showPermissionModal(dataArray, role) {
  // Hapus semua checkbox yang ada dalam modal
  checkboxContainer.innerHTML = "";

  arrayPermission = [
    "View",
    "upload-tabel",
    "assign-pegawai",
    "monitoring-putaran",
  ];

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
if (closePermissionBtn) {
  closePermissionBtn.addEventListener("click", function () {
    modalPermissionForm.action = modalPermissionFormLama;
    modalPermission.style.display = "none";
  });
}
