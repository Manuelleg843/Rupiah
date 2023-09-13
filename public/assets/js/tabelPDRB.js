const selectTable       = document.getElementById("selectTable");
const selectKota        = document.getElementById("selectKota");
const selectPutaran     = document.getElementById("selectPutaran");
const judulTable        = document.getElementById("judulTable");
const modalWilayah      = document.getElementById("modalWilayah");
const judulTableADHB    = document.getElementById("judulTableADHB");
const judulTableADHK    = document.getElementById("judulTableADHK");

// dropdown jenis tabel
selectTable.addEventListener("change", function (){
    var tableSelected = selectTable.value;
    var kotaSelected = selectKota.value;

    // mengganti judul tabel
    judulTable.textContent = tableSelected + " - " + kotaSelected;
});

// dropdown jenis tabel khusus halaman Tabel Ringkasan
selectTable.addEventListener("change", function (){
  var tableSelected = selectTable.value;

  // mengganti judul tabel
  judulTableADHB.textContent = tableSelected + " (ADHB)";
  judulTableADHK.textContent = tableSelected + " (ADHK)";

});

// dropdown kota
selectKota.addEventListener("change", function () {
  var tableSelected = selectTable.value;
  var kotaSelected = selectKota.value;

  // mengganti judul tabel
  judulTable.textContent = tableSelected + " - " + kotaSelected;
});

// dropdown putaran
selectPutaran.addEventListener("change", function () {
  var tableSelected = selectTable.value;
  var kotaSelected = selectKota.value;
  var putaranSelected = selectPutaran.value;

  // mengganti judul tabel
  judulTable.textContent =
    tableSelected + " - " + kotaSelected + " - " + putaranSelected;
});

// modal untuk upload table
// modalWilayah.addEventListener("change", function(){
//     var kotaSelected  = selectKota.value;
//     var judulModal = modalWilayah;

//     // mengganti judul pop-up
//     judulModal.textContent = "Upload Data PDRB " + kotaSelected;
// });