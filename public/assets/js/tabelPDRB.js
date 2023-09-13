var selectTable = document.getElementById("selectTable");
var selectKota = document.getElementById("selectKota");
var selectPutaran = document.getElementById("selectPutaran");
var judulTable = document.getElementById("judulTable");
var modalWilayah = document.getElementById("modalWilayah");

// dropdown jenis tabel
selectTable.addEventListener("change", function () {
  var tableSelected = selectTable.value;
  var kotaSelected = selectKota.value;

  // mengganti judul tabel
  judulTable.textContent = tableSelected + " - " + kotaSelected;
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
