const selectTable       = document.getElementById("selectTable");
const selectKota        = document.getElementById("selectKota");
const selectPutaran     = document.getElementById("selectPutaran");
const judulTable        = document.getElementById("judulTable");
const modalWilayah      = document.getElementById("modalWilayah");
const judulTableADHB    = document.getElementById("judulTableADHB");
const judulTableADHK    = document.getElementById("judulTableADHK");
const tableSelected     = selectTable.options[selectTable.selectedIndex].textContent;
const kotaSelected      = selectKota.options[selectKota.selectedIndex].textContent;
const putaranSelected   = selectPutaran.value;

// judul tabel default with putaran 
judulTable.textContent = tableSelected + " - " + kotaSelected + " - Putaran " + putaranSelected;

// dropdown jenis tabel
selectTable.addEventListener("change", function (){
  var jenisPDRB = selectTable.value;
  var tableSelected = selectTable.options[selectTable.selectedIndex].textContent;
  var kotaSelected = selectKota.options[selectKota.selectedIndex].textContent;
  var putaranSelected = selectPutaran.value;

  // mengganti judul tabel
  judulTable.textContent = tableSelected + " - " + kotaSelected + " - Putaran " + putaranSelected;
});


// dropdown jenis tabel khusus halaman Tabel Ringkasan
selectTable.addEventListener("change", function (){
  var tableSelected = selectTable.options[selectTable.selectedIndex].textContent;

  // console.log(tableSelected);

  // mengganti judul tabel
  judulTableADHB.textContent = tableSelected + " (ADHB)";
  judulTableADHK.textContent = tableSelected + " (ADHK)";

});

// dropdown kota
selectKota.addEventListener("change", function () {
  var tableSelected = selectTable.options[selectTable.selectedIndex].textContent;
  var kotaSelected = selectKota.options[selectKota.selectedIndex].textContent;
  var putaranSelected = selectPutaran.value;

  // mengganti judul tabel
  judulTable.textContent = tableSelected + " - " + kotaSelected + " - Putaran " + putaranSelected;
});

// dropdown putaran
selectPutaran.addEventListener("change", function () {
  var tableSelected = selectTable.options[selectTable.selectedIndex].textContent;
  var kotaSelected = selectKota.options[selectKota.selectedIndex].textContent;
  var putaranSelected = selectPutaran.value;

  // mengganti judul tabel
  judulTable.textContent =
    tableSelected + " - " + kotaSelected + " - Putaran " + putaranSelected;
});

// dropdown putaran
function createDropdownPutaran(data) {
    const selectElement = document.createElement('select');
    selectElement.classList.add("form-control");
    selectElement.id.add("selectPutaran");
    
    data.forEach(item => {
      const option = document.createElement('option');
      option.value = item.value; // Set the value attribute
      option.textContent = item.label; // Set the visible text
      selectElement.appendChild(option);
    });
  
    // Append the select element to the desired location in the DOM
    document.getElementById('selectPutaranContainer').appendChild(selectElement);
  }

  function kirimData(){
    var jenisPDRB = $("#selectTable").val();
    var data = {
      jenisPDRB : jenisPDRB,
    }

    // mengirim dengan ajax
    $.ajax({
      type:"POST",
      url: "tabelHistoryPutaran",
      data: data,
      success: function(response){
        console.log(url)
      }, 
      error: function(error){
        console.log("error : ", error)
      }
    })
  }
