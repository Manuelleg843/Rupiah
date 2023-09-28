const selectTable = document.getElementById("selectTable");
const selectKota = document.getElementById("selectKota");
const selectPutaran = document.getElementById("selectPutaran");
const judulTable = document.getElementById("judulTable");
const modalWilayah = document.getElementById("modalWilayah");
const judulTableADHB = document.getElementById("judulTableADHB");
const judulTableADHK = document.getElementById("judulTableADHK");
const tableSelected = selectTable.options[selectTable.selectedIndex].textContent;
const kotaSelected = selectKota.options[selectKota.selectedIndex].textContent;
const putaranSelected = selectPutaran.value;
const submitPeriode = document.getElementById("simpan-periode");

window.addEventListener('load', function () {
  loadData();
});

// munculin data on load
function loadData() {
  const tableSelected = selectTable.options[selectTable.selectedIndex].textContent;
  const kotaSelected = selectKota.options[selectKota.selectedIndex].textContent;
  const putaran = selectPutaran.value;
  const jenisPDRB = selectTable.value;
  const kota = selectKota.value;
  var selectedPeriode = [];

  $('input[type="checkbox"]:checked').each(function () {
    // selectedPeriode[$(this).attr('name')] = $(this).name();
    selectedPeriode.push($(this).attr('name'));
  });

  // mengganti judul tabel
  judulTable.textContent = tableSelected + " - " + kotaSelected + " - Putaran " + putaran;

  kirimData(jenisPDRB, kota, putaran, selectedPeriode);

}

function kirimData(jenisPDRB, kota, putaran, selectedPeriode) {
  $.ajax({
    type: "POST",
    url: "/tabelPDRB/tabelHistoryPutaran/getData",
    data: {
      jenisPDRB: jenisPDRB,
      kota: kota,
      putaran: putaran,
      periode: selectedPeriode
    },
    dataType: 'json',
    success: function (data) {

      renderTable(data['dataPDRB'], data['selectedPeriode'], data['komponen']);
      // console.log("Sukses! Respons dari server:", data['dataPDRB']);
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error.message);
    }
  });
}

// render table
function renderTable(data, selectedPeriode, komponen) {
  // container table  
  // var tableContainer = $('#PDRBTable');
  var container = document.getElementById("PDRBTableContainer");

  // delete table if there is content inside it 
  container.innerHTML = "";

  // create elemen tabel 
  var table = document.createElement("table");
  table.id = "PDRBTable";
  table.classList.add('table', 'table-bordered', 'table-hover');

  // create table header
  var thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  var headerRow = document.createElement("tr");

  // var headerRow = table.insertRow();
  var headerKomponen = document.createElement('th');
  headerKomponen.colSpan = '2';
  headerKomponen.innerHTML = "Komponen";
  headerRow.appendChild(headerKomponen);

  for (var i = 0; i < selectedPeriode.length; i++) {
    var columnName = selectedPeriode[i];
    var headerCell = document.createElement("th");
    headerCell.innerHTML = columnName;
    headerRow.appendChild(headerCell);
  }

  thead.appendChild(headerRow);
  table.appendChild(thead);

  var temp = -1;
  var tbody = document.createElement("tbody");
  // loop through json to create tbody
  for (var i = 0; i < komponen.length; i++) {
    // menghitung banyak kolom pada tabel 
    var totalColumn = headerRow.childElementCount;

    var id_komponen = komponen[i].id_komponen;

    // insert row 
    var row = document.createElement('tr');
    if (id_komponen == 1 || id_komponen == 2 || id_komponen == 3 || id_komponen == 4 || id_komponen == 5 || id_komponen == 6 || id_komponen == 7 || id_komponen == 8 || id_komponen == 9) {
      row.style = 'font-weight: bold;';
    }

    for (var col = 0; col < totalColumn; col++) {
      var cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = '2';
        if (id_komponen != 1 && id_komponen != 2 && id_komponen != 3 && id_komponen != 4 && id_komponen != 5 && id_komponen != 6 && id_komponen != 7 && id_komponen != 8 && id_komponen != 9) {
          cell.classList = 'pl-5';
        }

        if (id_komponen == 9) {
          cell.innerHTML = komponen[i].komponen;
        } else {
          cell.innerHTML = id_komponen + ". " + komponen[i].komponen;
        }
      } else {
        temp++;
        cell.style = "text-align: right;";
        cell.innerHTML = numberFormat(data[temp].nilai);
      }

      row.appendChild(cell);
    }
    tbody.appendChild(row);
  }

  table.appendChild(tbody);
  // memasukkan tabel ke view 
  container.appendChild(table);
}
// dropdown jenis tabel khusus halaman Tabel Ringkasan


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

function numberFormat(number, decimals = 2, decimalSeparator = ',', thousandsSeparator = '.') {
  number = parseFloat(number).toFixed(decimals);
  number = number.toString().replace('.', decimalSeparator);

  var parts = number.split(decimalSeparator);
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);

  return parts.join(decimalSeparator);
}