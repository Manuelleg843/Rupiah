// const selectTable = document.getElementById("selectTable");
const selectKota = document.getElementById("selectKota");
const selectPutaran = document.getElementById("selectPutaran");
const judulTable = document.getElementById("judulTable");
const modalWilayah = document.getElementById("modalWilayah");
const judulTableADHB = document.getElementById("judulTableADHB");
const judulTableADHK = document.getElementById("judulTableADHK");
const submitPeriode = document.getElementById("simpan-periode");
const submitKomponen = document.getElementById("simpan-komponen");
var tableSelected;
var kotaSelected;
var putaran;
var jenisPDRB;
var kota;
var exportExcel = document.getElementById("exportExcel");

// munculin data on load
function loadData() {
  var tableHistory = document.getElementById("selectTableHistory");
  var tableRingkasan = document.getElementById("selectTableRingkasan");

  if (tableHistory) {
    tableSelected = tableHistory.options[tableHistory.selectedIndex].textContent;
    jenisPDRB = tableHistory.value;
  };
  if (tableRingkasan) {
    tableSelected = tableRingkasan.options[tableRingkasan.selectedIndex].textContent;
    jenisTable = tableRingkasan.options[tableRingkasan.selectedIndex].id;
  };
  if (selectKota) {
    kotaSelected = selectKota.options[selectKota.selectedIndex].textContent;
    kota = selectKota.value;
  }
  if (selectPutaran) {
    putaran = selectPutaran.value;
  }

  var selectedKomponen = [];
  var selectedPeriode = [];

  if (submitPeriode) {
    $('.checkboxes-periode input[type="checkbox"]:checked').each(function () {
      selectedPeriode.push($(this).attr('name'));
    });

  }

  if (submitKomponen) {
    $('#komponen-checkboxes-container input[type="checkbox"]:checked').each(function () {
      selectedKomponen.push($(this).attr('id'));
    });
  }

  // mengganti judul tabel
  switch (document.title) {
    case "Rupiah | Tabel History Putaran":
      judulTable.textContent = tableSelected + " - " + kotaSelected + " - Putaran " + putaran;
      kirimData(jenisPDRB, kota, putaran, selectedPeriode, selectedKomponen);
      break;
    case "Rupiah | Tabel Ringkasan":
      judulTable.textContent = tableSelected;
      kirimDataRingkasan(jenisTable, selectedPeriode, selectedKomponen);
      break;
  }
}

function kirimData(jenisPDRB, kota, putaran, selectedPeriode, selectedKomponen) {
  $.ajax({
    type: "POST",
    url: "/tabelPDRB/tabelHistoryPutaran/getData",
    data: {
      jenisPDRB: jenisPDRB,
      kota: kota,
      putaran: putaran,
      periode: selectedPeriode,
      komponen: selectedKomponen
    },
    dataType: 'json',
    success: function (data) {
      renderTable(data['dataPDRB'], data['selectedPeriode'], data['komponen']);
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error.message);
    }
  });
}

function kirimDataRingkasan(jenisTable, selectedPeriode) {
  $.ajax({
    type: "POST",
    url: "/tabelPDRB/tabelRingkasan/getData/",
    data: {
      jenisTable: jenisTable,
      periode: selectedPeriode
    },
    dataType: 'json',
    success: function (data) {
      switch (jenisTable) {
        case '11':
          console.log(data);
          break;
        case '13':
          console.log(data);
          // renderTable_ringkasan(data['dataRingkasan'], data['komponen'], data['selectedPeriode'], data['wilayah']);
          break;
        case '15':
          renderTable_ringkasan(data['dataRingkasan'], data['komponen'], data['selectedPeriode'], data['wilayah']);
          break;
        case '16':
           renderTable_ringkasan(data['dataRingkasan'], data['komponen'], data['selectedPeriode'], data['wilayah']);
          break;
        case '18':
          console.log(data);
          break; 
      }

    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error);
    }
  });
}

// render table
function renderTable(data, selectedPeriode, komponen) {
  // container table  
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

function renderTable_ringkasan(data, komponen, selectedPeriode, wilayah) {
  // container table  
  var container = document.getElementById("ringkasan-container");

  // delete table if there is content inside it 
  container.innerHTML = "";

  // create elemen tabel 
  var table = document.createElement("table");
  table.id = "tabelRingkasan";
  table.classList.add('table', 'table-bordered', 'table-hover');

  // create table header
  var thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  var headerRow = document.createElement("tr");
  var headerRow2 = document.createElement("tr");

  // var headerRow = table.insertRow();
  var headerKomponen = document.createElement('th');
  headerKomponen.colSpan = '2';
  headerKomponen.rowSpan = '2';
  headerKomponen.innerHTML = "Komponen";
  headerRow.appendChild(headerKomponen);

  for (var i = 0; i < selectedPeriode.length; i++) {
    var columnName = selectedPeriode[i];
    var headerCell = document.createElement("th");
    headerCell.colSpan = '7';
    headerCell.innerHTML = columnName;

    wilayah.forEach(columns => {
      var headerCell2 = document.createElement("th");
      headerCell2.classList.add('w-50');
      headerCell2.innerHTML = columns.wilayah;
      headerRow2.appendChild(headerCell2);
    });
    headerRow.appendChild(headerCell);
  }

  thead.appendChild(headerRow);
  thead.appendChild(headerRow2);
  table.appendChild(thead);

  // create table body
  var tbody = document.createElement("tbody");
  var temp = -1;
  var totalColumn = headerRow2.childElementCount + 1;

  // loop through json to create tbody
  // loop through json to create tbody
  for (var i = 0; i < komponen.length; i++) {
    // menghitung banyak kolom pada tabel 

    var id_komponen = komponen[i].id_komponen;

    // insert row 
    var row = document.createElement('tr');
    if (id_komponen == 1 || id_komponen == 2 || id_komponen == 3 || id_komponen == 4 || id_komponen == 5 || id_komponen == 6 || id_komponen == 7 || id_komponen == 8 || id_komponen == 9) {
      row.style = 'font-weight: bold;';
    }

    for (var col = 0; col < totalColumn; col++) {
      console.log(totalColumn);
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
        cell.classList.add('w-50');
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


function exportData(fileType) {
  tableSelected = selectTable.options[selectTable.selectedIndex].textContent;
  putaran = selectPutaran.value;
  jenisPDRB = selectTable.value;
  kota = selectKota.value;
  var selectedPeriode = [];

  $('input[type="checkbox"]:checked').each(function () {
    selectedPeriode.push($(this).attr('name'));
  });

  switch (fileType) {
    case 'excel':
      var url = "/tabelPDRB/exportExcel/" + tableSelected + "/" + jenisPDRB + "/" + kota + "/" + putaran + "/" + selectedPeriode;
      break;
    case 'pdf':
      var url = "/tabelPDRB/exportPDF/" + tableSelected + "/" + jenisPDRB + "/" + kota + "/" + putaran + "/" + selectedPeriode;
      break;
    case 'excelAllPutaran':
      var url = "/tabelPDRB/excelAllPutaran/" + tableSelected + "/" + jenisPDRB + "/" + kota + "/null" + "/" + selectedPeriode;
      break;

  }

  window.location.href = url;

}

// generate dropdown jenis tabel halaman tabel ringkasan
function generateDropdownTabelRingkasan() {
  const dropdownContainer = document.getElementById("dropdownTabelRingkasan");

  // generate dropdown
  const select = document.createElement("select");
  select.classList.add("form-control");
  select.style = ("width: 100%; max-width: 600px");
  select.id = "selectTable";

  var options = [
    { value: "Pilih Jenis Tabel", text: "Pilih Jenis Tabel" },
    { value: "dikrepansi-ADHB", text: "Tabel 1.11. Diskrepansi PDRB ADHB Menurut Pengeluaran Provinsi dan 6 Kabupaten/Kota (Juta Rupiah)" },
    { value: "dikrepansi-ADHK", text: "Tabel 1.12. Diskrepansi PDRB ADHK Menurut Pengeluaran Provinsi dan 6 Kabupaten/Kota (Juta Rupiah)" },
    { value: "distribusi-persentase-PDRB-ADHB", text: "Tabel 1.13. Distribusi Persentase PDRB ADHB Provinsi dan 6 Kabupaten/Kota" },
    { value: "distribusi-persentase-PDRB-Total", text: "Tabel 1.14. Distribusi Persentase PDRB Kabupaten Kota Terhadap Total Provinsi" },
    { value: "perbandingan-pertumbuhan-Q-TO-Q", text: "Tabel 1.15. Perbandingan Pertumbuhan Ekonomi Provinsi DKI Jakarta dan 6 Kabupaten/Kota (Q-TO-Q)" },
    { value: "perbandingan-pertumbuhan-Y-ON-Y", text: "Tabel 1.16. Perbandingan Pertumbuhan Ekonomi Provinsi DKI Jakarta dan 6 Kabupaten/Kota (Y-ON-Y)" },
    { value: "perbandingan-pertumbuhan-C-TO-C", text: "Tabel 1.17. Perbandingan Pertumbuhan Ekonomi Provinsi DKI Jakarta dan 6 Kabupaten/Kota (C-TO-C)" },
    { value: "indeks-implisit", text: "Tabel 1.18. Indeks Implisit PDRB Provinsi dan Kabupaten/Kota" },
    { value: "pertumbuhan-indeks-implisit-Q-TO-Q", text: "Tabel 1.19. Pertumbuhan Indeks Implisit Provinsi dan Kabupaten/Kota (Q-TO-Q)" },
    { value: "pertumbuhan-indeks-implisit-Y-ON-Y", text: "Tabel 1.20. Pertumbuhan Indeks Implisit Provinsi dan Kabupaten/Kota (Y-ON-Y)" },
    { value: "sumber-pertumbuhan-Q-TO-Q", text: "Tabel 1.23. Sumber Pertumbuhan Ekonomi Provinsi dan 6 Kabupaten/Kota (Q-TO-Q)" },
    { value: "sumber-pertumbuhan-Y-ON-Y", text: "Tabel 1.24. Sumber Pertumbuhan Ekonomi Provinsi dan 6 Kabupaten/Kota (Y-ON-Y)" },
    { value: "sumber-pertumbuhan-C-TO-C", text: "Tabel 1.25. Sumber Pertumbuhan Ekonomi Provinsi dan 6 Kabupaten/Kota (C-TO-C)" },
    { value: "ringkasan-pertumbuhan-ekstrem", text: "Tabel 1.26. Ringkasan Pertumbuhan Ekstrim Provinsi dan 6 Kabupaten Kota" },
  ]

  // Loop untuk membuat elemen-elemen option
  var option;
  for (var i = 0; i < options.length; i++) {
    option = document.createElement('option');
    option.value = options[i].value;
    option.innerHTML = options[i].text;
    if (i == 0) {
      option.hidden = true;
    }
    if (i == 1) {
      option.selected = true;
    }
    select.appendChild(option);
  }

  dropdownContainer.appendChild(select);

}

if (document.getElementById('selectTableRingkasan') != null) {
  document.getElementById('selectTableRingkasan').addEventListener('change', function () {
    // var tableSelected = this.value;
    loadData();
    // tableSelected == 'diskrepansi-ADHB' ? window.location.href = "/tabelPDRB/tabelRingkasan/" : window.location.href = "/tabelPDRB/tabelRingkasan/" + tableSelected;
  });
}

if (document.getElementById('selectTableHistory') != null) {
  document.getElementById('selectTableHistory').addEventListener('change', function () {
    loadData();
  });
}



