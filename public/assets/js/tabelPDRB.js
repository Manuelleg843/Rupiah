const selectKota = document.getElementById("selectKota");
const selectPutaran = document.getElementById("selectPutaran");
const judulTable = document.getElementById("judulTable");
const modalWilayah = document.getElementById("modalWilayah");
const judulTableADHB = document.getElementById("judulTableADHB");
const judulTableADHK = document.getElementById("judulTableADHK");
const submitPeriode = document.getElementById("simpan-periode");
const submitKomponen = document.getElementById("simpan-komponen");
const subJudulContainer = document.createElement("row");
const judulModal = document.getElementById("judulModal");
const kotaJudulModal = document.getElementById("kotaJudulModal");
const eksporButtonPDF = document.getElementById("export-button-pdf");
const eksporButtonExcelPerkota = document.getElementById("exportExcelPerKota");
const currentQuarter = Math.floor((new Date().getMonth() + 3) / 3);
const tableJenisPDRB = document.getElementById("selectTableHistory");
const tableRingkasan = document.getElementById("selectTableRingkasan");
const tableUpload = document.getElementById("selectTableUpload");
const tableArahRevisi = document.getElementById("selectTableArahRevisi");
const tablePerkota = document.getElementById("selectTablePerkota");
const selectPeriodeHistory = document.getElementById("selectPeriodeHistory");
const selectPutaranHistory = document.getElementById("selectPutaranHistory");
var selectedKomponen = [];
var selectedPeriode = [];
var selectedPutaran = [];
var tableSelected;
var kotaSelected;
var putaran;
var jenisPDRB;
var kota;

// munculin data on load
function loadData() {
  if (tableJenisPDRB) {
    tableSelected =
      tableJenisPDRB.options[tableJenisPDRB.selectedIndex].textContent;
    jenisPDRB = tableJenisPDRB.value;
    let periode = selectPeriodeHistory.value;
    getPutaranPeriode(periode);
  }
  if (tableRingkasan) {
    tableSelected =
      tableRingkasan.options[tableRingkasan.selectedIndex].textContent;
    jenisTable = tableRingkasan.options[tableRingkasan.selectedIndex].id;
    let checkboxContainer;
    if (["15", "17", "19", "21", "23"].includes(jenisTable)) {
      if (document.getElementById("checkboxes-container-year")) {
        checkboxContainer = document.getElementById(
          "checkboxes-container-year"
        );
        checkboxContainer.innerHTML = "";
        checkboxContainer.id = "checkboxes-container";
        generateCheckboxes();
        generateTahunDropdown();
      }
    } else {
      if (document.getElementById("checkboxes-container")) {
        checkboxContainer = document.getElementById("checkboxes-container");
        checkboxContainer.innerHTML = "";
        checkboxContainer.id = "checkboxes-container-year";
        generateCheckboxes();
        generateTahunDropdown();
      }
    }
  }
  if (tableUpload) {
    tableSelected = tableUpload.options[tableUpload.selectedIndex].textContent;
    jenisPDRB = tableUpload.options[tableUpload.selectedIndex].id;
    jenisPDRB = tableUpload.value;
  }
  if (selectKota) {
    kotaSelected = selectKota.options[selectKota.selectedIndex].textContent;
    kota = selectKota.value;
  }
  if (selectPutaran) {
    putaran = selectPutaran.value;
  }
  if (tablePerkota) {
    tableSelected =
      tablePerkota.options[tablePerkota.selectedIndex].textContent;
    jenisPDRB = tablePerkota.value;
  }
  if (tableArahRevisi) {
    tableSelected =
      tableArahRevisi.options[tableArahRevisi.selectedIndex].textContent;
    jenisPDRB = tableArahRevisi.options[tableArahRevisi.selectedIndex].id;
  }

  if (submitPeriode) {
    selectedPeriode.length = 0;
    $('.checkboxes-periode input[type="checkbox"]:checked').each(function () {
      selectedPeriode.push($(this).attr("name"));
    });
  }

  if (selectPeriodeHistory) {
    selectedPeriode = selectPeriodeHistory.value;
  }

  if (selectPutaranHistory) {
    selectedPutaran.length = 0;
    if ($('.putaran-checkboxes-container input[type="checkbox"]:checked')) {
      $('.putaran-checkboxes-container input[type="checkbox"]:checked').each(
        function () {
          selectedPutaran.push($(this).attr("name"));
        }
      );
    }
  }

  // menampilkan periode default untuk tabel di halaman upload
  if (document.title == "TEMPORAL | Upload Data") {
    switch (currentQuarter) {
      case 1:
        for (let i = currentYear - 2; i < currentYear; i++) {
          // currentYear sudah dideklarasi di file beranda.js
          selectedPeriode.push(i + "Q1", i + "Q2", i + "Q3", i + "Q4", i);
        }
        break;
      case 2:
        selectedPeriode = [currentYear + "Q1"];
        break;
      case 3:
        selectedPeriode = [currentYear + "Q1", currentYear + "Q2"];
        break;
      case 4:
        selectedPeriode = [
          currentYear + "Q1",
          currentYear + "Q2",
          currentYear + "Q3",
        ];
        break;
    }
  }

  // mengganti judul tabel dan kirim data buat generate tabel
  switch (document.title) {
    case "TEMPORAL | Tabel History Putaran":
      judulTable.textContent =
        tableSelected + " - " + kotaSelected + " - " + selectedPeriode;
      kirimData(jenisPDRB, kota, selectedPutaran, selectedPeriode, false);
      break;
    case "TEMPORAL | Tabel Ringkasan":
      judulTable.textContent = tableSelected;

      // create element subJudul untuk tabel 1.15, 1.16, 1.17, 1.19, 1.20, 1.21, 1.22, 1.23
      if (subJudulContainer) {
        subJudulContainer.id = "subJudul-container";
        subJudul = document.createElement("p");
        subJudul.textContent = "";
        subJudulContainer.textContent = "";
      }
      if (
        jenisTable == "15" ||
        jenisTable == "16" ||
        jenisTable == "17" ||
        jenisTable == "19" ||
        jenisTable == "20" ||
        jenisTable == "21" ||
        jenisTable == "22" ||
        jenisTable == "23"
      ) {
        subJudul.textContent = "Ket: *) pertumbuhan negatif";
        subJudulContainer.appendChild(subJudul);
        const containerTabel = document.getElementById("card-table");
        containerTabel.appendChild(subJudulContainer);
      }
      kirimDataRingkasan(jenisTable, selectedPeriode, selectedKomponen);
      break;
    case "TEMPORAL | Upload Data":
      judulTable.textContent = tableSelected + " - " + kotaSelected;
      kirimDataTabelUpload(jenisPDRB, kota, selectedPeriode);
      break;
    case "TEMPORAL | Tabel Per Kota":
      judulTable.textContent = tableSelected + " - " + kotaSelected;
      kirmdataPerKota(jenisPDRB, kota, selectedPeriode);
      break;
    case "TEMPORAL | Arah Revisi":
      judulTable.textContent = tableSelected + " - " + kotaSelected;
      kirimDataArahRevisi(jenisPDRB, kota, selectedPeriode);
      break;
  }
}

$("#simpan-periode").click();

function kirimData(
  jenisPDRB,
  kota,
  putaran,
  selectedPeriode,
  allPutaran = false
) {
  if (allPutaran) {
    putaran = getPutaranPeriode(selectedPeriode);
    $.ajax({
      type: "POST",
      url: "/tabelPDRB/tabelHistoryPutaran/getDataHistory",
      data: {
        jenisPDRB: jenisPDRB,
        kota: kota,
        putaran: putaran,
        periode: selectedPeriode,
      },
      dataType: "json",
      success: function (data) {
        renderTable(
          data["dataHistory"],
          selectedPeriode,
          data["komponen"],
          putaran
        );
      },
      error: function (error) {
        // Handle kesalahan jika ada
        console.error("Terjadi kesalahan:", error);
      },
    });
  } else {
    $.ajax({
      type: "POST",
      url: "/tabelPDRB/tabelHistoryPutaran/getDataHistory",
      data: {
        jenisPDRB: jenisPDRB,
        kota: kota,
        putaran: putaran,
        periode: selectedPeriode,
      },
      dataType: "json",
      success: function (data) {
        renderTable(
          data["dataHistory"],
          selectedPeriode,
          data["komponen"],
          putaran
        );
      },
      error: function (error) {
        // Handle kesalahan jika ada
        console.error("Terjadi kesalahan:", error);
      },
    });
  }
}

function kirmdataPerKota(jenisPDRB, kota, selectedPeriode) {
  if (jenisPDRB == "13") {
    $.ajax({
      type: "POST",
      url: "/tabelPDRB/getDataPerKota",
      data: {
        jenisPDRB: jenisPDRB,
        kota: kota,
        selectedPeriode: selectedPeriode,
      },
      dataType: "json",
      success: function (data) {
        renderTablePerkotaEkstrem(
          data["dataPDRB"],
          data["komponen"],
          data["selectedPeriode"]
        );
      },
      error: function (error) {
        // Handle kesalahan jika ada
        console.error("Terjadi kesalahan:", error);
      },
    });
  } else {
    $.ajax({
      type: "POST",
      url: "/tabelPDRB/getDataPerKota",
      data: {
        jenisPDRB: jenisPDRB,
        kota: kota,
        selectedPeriode: selectedPeriode,
      },
      dataType: "json",
      success: function (data) {
        renderTablePerKota(
          data["dataPDRB"],
          data["selectedPeriode"],
          data["komponen"]
        );
      },
      error: function (error) {
        // Handle kesalahan jika ada
        console.error("Terjadi kesalahan:", error);
      },
    });
  }
}

function kirimDataRingkasan(jenisTable, selectedPeriode, selectedKomponen) {
  $.ajax({
    type: "POST",
    url: "/tabelPDRB/tabelRingkasan/getData/",
    data: {
      jenisTable: jenisTable,
      periode: selectedPeriode,
      komponen: selectedKomponen,
    },
    dataType: "json",
    success: function (data) {
      switch (jenisTable) {
        case "11":
          renderTable_diskrepansi(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
          break;
        case "12":
          renderTable_diskrepansi(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
          break;
        case "14":
          renderTable_ringkasan14(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"]
          );
          break;
        case "15":
        case "16":
        case "17":
        case "19":
        case "20":
          renderTable_ringkasanGrowth(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"]
          );
          break;
        default:
          renderTable_ringkasan(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
          break;
      }
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error);
    },
  });
}

function kirimDataTabelUpload(jenisPDRB, kota, selectedPeriode) {
  $.ajax({
    type: "POST",
    url: "/uploadData/angkaPDRB/getData",
    data: {
      jenisPDRB: jenisPDRB,
      kota: kota,
      periode: selectedPeriode,
    },
    dataType: "json",
    success: function (data) {
      renderTableUpload(
        data["dataPDRB"],
        data["selectedPeriode"],
        data["komponen"]
      );
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error);
    },
  });
}

function kirimDataArahRevisi(jenisTable, kota, selectedPeriode) {
  $.ajax({
    type: "POST",
    url: "/arahRevisi/getData",
    data: {
      jenisTable: jenisTable,
      kota: kota,
      periode: selectedPeriode,
    },
    dataType: "json",
    success: function (data) {
      renderTableArahRevisi(
        data["dataArahRevisi"],
        data["selectedPeriode"],
        data["komponen"]
      );
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error);
    },
  });
}

function renderTableArahRevisi(data, selectedPeriode, komponen) {
  // container table
  var container = document.getElementById("arah-revisi-container");

  // delete table if there is content inside it
  container.innerHTML = "";

  // create elemen tabel
  var table = document.createElement("table");
  table.id = "PDRBTable";
  table.classList.add("table", "table-bordered", "table-hover", "PDRBTable");

  // create table header
  var thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  var headerRow = document.createElement("tr");
  var headerRow2 = document.createElement("tr");

  var headerKomponen = document.createElement("th");
  headerKomponen.colSpan = "2";
  headerKomponen.rowSpan = "2";
  headerKomponen.innerHTML = "Komponen";
  headerRow.appendChild(headerKomponen);

  for (var i = 0; i < selectedPeriode.length; i++) {
    var columnName = selectedPeriode[i];
    var headerCell = document.createElement("th");
    headerCell.colSpan = "3";
    headerCell.innerHTML = columnName;

    var headerCell2 = document.createElement("th");
    headerCell2.classList.add("w-50");
    headerCell2.innerHTML = "Rilis";
    headerRow2.appendChild(headerCell2);

    var headerCell3 = document.createElement("th");
    headerCell3.classList.add("w-50");
    headerCell3.innerHTML = "Revisi";
    headerRow2.appendChild(headerCell3);

    var headerCell4 = document.createElement("th");
    headerCell4.classList.add("w-50");
    headerCell4.innerHTML = "Arah Revisi";
    headerRow2.appendChild(headerCell4);

    headerRow.appendChild(headerCell);
  }

  thead.appendChild(headerRow);
  thead.appendChild(headerRow2);
  table.appendChild(thead);

  // create table body
  var tbody = document.createElement("tbody");
  var totalColumn = headerRow.childElementCount;

  // loop through json to create tbody
  var idBold = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
  for (var i = 0; i < komponen.length; i++) {
    var id_komponen = komponen[i].id_komponen;

    // insert row
    var row = document.createElement("tr");
    if (idBold.includes(id_komponen)) {
      row.style = "font-weight: bold;";
    }

    subCol = 1;
    for (var col = 0; col < totalColumn; col++) {
      var cell = document.createElement("td");
      var cell_rilis = document.createElement("td");
      var cell_revisi = document.createElement("td");
      var cell_arah = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";

        if (!idBold.includes(id_komponen)) {
          cell.classList = "pl-5";
        }

        if (id_komponen == 9) {
          cell.innerHTML = komponen[i].komponen;
        } else {
          cell.innerHTML = id_komponen + ". " + komponen[i].komponen;
        }
        row.appendChild(cell);
      } else {
        cell_rilis.style = "text-align: right;";
        cell_rilis.innerHTML = data[subCol - 1][i]
          ? numberFormat(data[subCol - 1][i].nilai)
          : "";
        cell_revisi.style = "text-align: right;";
        cell_revisi.innerHTML = data[subCol][i]
          ? numberFormat(data[subCol][i].nilai)
          : "";
        cell_arah.style = "text-align: center;";
        let selisih =
          Math.round(data[subCol][i].nilai, 2) -
          Math.round(data[subCol - 1][i].nilai, 2);
        if (selisih > 0) {
          cell_arah.classList.add("text-success");
          cell_arah.innerHTML = "+";
        } else if (selisih < 0) {
          cell_arah.classList.add("text-danger");
          cell_arah.innerHTML = "-";
        } else {
          cell_arah.classList.add("text-warning");
          cell_arah.innerHTML = "=";
        }
        row.appendChild(cell_rilis);
        row.appendChild(cell_revisi);
        row.appendChild(cell_arah);
        subCol = subCol + 2;
      }
    }
    tbody.appendChild(row);
  }
  table.appendChild(tbody);
  // memasukkan tabel ke view
  container.appendChild(table);
}

function renderTablePerKota(data, selectedPeriode, komponen) {
  // container table
  const container = document.getElementById("tabelPerkota");

  // delete table if there is content inside it
  container.innerHTML = "";

  // create elemen tabel
  const table = document.createElement("table");
  table.id = "tablePerkota";
  table.classList.add("table", "table-bordered", "table-hover", "PDRBTable");

  // create table header
  const thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  const headerRow = document.createElement("tr");

  const headerKomponen = document.createElement("th");
  headerKomponen.colSpan = "2";
  headerKomponen.rowSpan = "1";
  headerKomponen.innerHTML = "Komponen";
  headerRow.appendChild(headerKomponen);

  for (let i = 0; i < selectedPeriode.length; i++) {
    const columnName = selectedPeriode[i];
    const headerCell = document.createElement("th");
    headerCell.innerHTML = columnName;
    headerRow.appendChild(headerCell);
  }

  thead.appendChild(headerRow);
  table.appendChild(thead);

  // create table body
  const tbody = document.createElement("tbody");
  let temp = -1;
  const totalColumn = headerRow.childElementCount;

  // loop through json to create tbody
  var idBold = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
  for (var i = 0; i < komponen.length; i++) {
    // menghitung banyak kolom pada tabel

    const id_komponen = komponen[i].id_komponen;

    // insert row
    const row = document.createElement("tr");
    if (idBold.includes(id_komponen)) {
      row.style = "font-weight: bold;";
    }

    for (let col = 0; col < totalColumn; col++) {
      const cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        if (!idBold.includes(id_komponen)) {
          cell.classList = "pl-5";
        }
        if (id_komponen == 9) {
          cell.innerHTML = komponen[i].komponen;
        } else {
          cell.innerHTML = id_komponen + ". " + komponen[i].komponen;
        }
      } else {
        temp++;
        cell.style = "text-align: right;";
        if (totalColumn < 3) {
          cell.classList.add("w-50");
        }
        cell.innerHTML = data[col - 1][i]
          ? numberFormat(data[col - 1][i].nilai)
          : "";
      }
      row.appendChild(cell);
    }
    tbody.appendChild(row);
  }

  table.appendChild(tbody);
  // memasukkan tabel ke view
  container.appendChild(table);
}

function renderTablePerkotaEkstrem(data, komponen, selectedPeriode) {
  // container table
  const JENIS_PERTUMBUHAN = [
    "Pertumbuhan YoY",
    "Pertumbuhan QtQ",
    "Pertumbuhan CtC",
    "Implisit YoY",
    "Implisit QtQ",
  ];
  let container = document.getElementById("tabelPerkota");

  // delete table if there is content inside it
  container.innerHTML = "";

  // create elemen tabel
  let table = document.createElement("table");
  table.id = "tablePerkota";
  table.classList.add("table", "table-bordered", "table-hover", "PDRBTable");

  // create table header
  let thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  let headerRow = document.createElement("tr");
  let headerRow2 = document.createElement("tr");

  let headerKomponen = document.createElement("th");
  headerKomponen.colSpan = "2";
  headerKomponen.rowSpan = "2";
  headerKomponen.style =
    "vertical-align: middle;  position: sticky; left: 0; z-index: 1; background-color: #B8DAFF";
  headerKomponen.innerHTML = "Komponen";
  headerRow.appendChild(headerKomponen);

  for (let i = 0; i < selectedPeriode.length; i++) {
    let columnName = selectedPeriode[i];
    let headerCell = document.createElement("th");
    headerCell.colSpan = "5";
    headerCell.innerHTML = columnName;

    JENIS_PERTUMBUHAN.forEach((columns) => {
      let headerCell2 = document.createElement("th");
      headerCell2.style =
        "max-width: 105px; overflow: auto; white-space: normal;vertical-align: middle;";
      headerCell2.innerHTML = columns;
      headerRow2.appendChild(headerCell2);
    });
    headerRow.appendChild(headerCell);
  }

  thead.appendChild(headerRow);
  thead.appendChild(headerRow2);
  table.appendChild(thead);

  // create table body
  let tbody = document.createElement("tbody");
  let temp = -1;
  let totalColumn = headerRow2.childElementCount + 1;

  // loop through json to create tbody
  var idBold = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
  for (let i = 0; i < komponen.length; i++) {
    let periode = 0;
    let pertumbuhan = 0;
    let id_komponen = komponen[i].id_komponen;

    // insert row
    let row = document.createElement("tr");
    if (idBold.includes(id_komponen)) {
      row.style = "font-weight: bold;";
    }

    for (let col = 0; col < totalColumn; col++) {
      let cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        cell.style =
          "position: sticky; left: 0; z-index: 1; background-color: #fff;";
        if (!idBold.includes(id_komponen)) {
          cell.classList = "pl-5";
        }
        if (id_komponen == 9) {
          cell.innerHTML = komponen[i].komponen;
        } else {
          cell.innerHTML = id_komponen + ". " + komponen[i].komponen;
        }
      } else {
        temp++;
        cell.style = "text-align: right;";
        // cell.classList.add("col-6");
        cell.innerHTML = data[pertumbuhan][periode][i]
          ? numberFormat(data[pertumbuhan][periode][i].nilai)
          : "";
        pertumbuhan++;
        if (pertumbuhan == JENIS_PERTUMBUHAN.length) {
          periode++;
          if (periode == selectedPeriode.length) {
            periode = 0;
          }
          pertumbuhan = 0;
        }
      }
      row.appendChild(cell);
    }
    tbody.appendChild(row);
  }

  table.appendChild(tbody);
  // memasukkan tabel ke view
  container.appendChild(table);
}

// render table
function renderTable(data, selectedPeriode, komponen, putaran) {
  // container table
  var container = document.getElementById("PDRBTableContainer");

  // delete table if there is content inside it
  container.innerHTML = "";

  // create elemen tabel
  var table = document.createElement("table");
  table.id = "PDRBTable";
  table.classList.add("table", "table-bordered", "table-hover", "PDRBTable");

  // create table header
  var thead = document.createElement("thead");
  thead.classList.add(
    "text-center",
    "table-primary",
    "sticky-top",
    "PDRBTable"
  );
  var headerRow = document.createElement("tr");
  var headerRow2 = document.createElement("tr");

  // header kolom pertama
  var headerKomponen = document.createElement("th");
  headerKomponen.colSpan = "2";
  headerKomponen.rowSpan = "2";
  headerKomponen.style =
    "vertical-align: middle;  position: sticky; left: 0; z-index: 1; background-color: #B8DAFF";
  headerKomponen.innerHTML = "Komponen";
  headerRow.appendChild(headerKomponen);

  // header di atas header kolom putaran
  var headerPeriode = document.createElement("th");
  headerPeriode.colSpan = putaran.length;
  headerPeriode.innerHTML = selectedPeriode;
  headerRow.appendChild(headerPeriode);

  for (var i = 0; i < putaran.length; i++) {
    var columnName = `putaran ${putaran[i]}`;
    var headerCell2 = document.createElement("th");
    headerCell2.style =
      "max-width: 105px; overflow: auto; white-space: normal;vertical-align: middle;";
    headerCell2.innerHTML = columnName;
    headerRow2.appendChild(headerCell2);
  }

  thead.appendChild(headerRow);
  thead.appendChild(headerRow2);
  table.appendChild(thead);

  var temp = -1;
  var tbody = document.createElement("tbody");
  var totalColumn = headerRow2.childElementCount + 1;

  // loop through json to create tbody
  var idBold = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
  for (var i = 0; i < komponen.length; i++) {
    var id_komponen = komponen[i].id_komponen;

    // insert row
    var row = document.createElement("tr");
    if (idBold.includes(id_komponen)) {
      row.style = "font-weight: bold;";
    }

    for (var col = 0; col < totalColumn; col++) {
      var cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        cell.style =
          "position: sticky; left: 0; z-index: 1; background-color: #fff;";
        if (!idBold.includes(id_komponen)) {
          cell.classList = "pl-5";
        }

        if (id_komponen == 9) {
          cell.innerHTML = komponen[i].komponen;
        } else {
          cell.innerHTML = id_komponen + ". " + komponen[i].komponen;
        }
      } else {
        temp++;
        cell.style = "text-align: right;";
        if (document.title == "TEMPORAL | Upload Data") {
          cell.innerHTML = data[col - 1][i]
            ? numberFormat(data[col - 1][i].nilai)
            : "";
        } else {
          cell.innerHTML = data[temp].nilai
            ? numberFormat(data[temp].nilai)
            : "";
        }
      }
      row.appendChild(cell);
    }
    tbody.appendChild(row);
  }

  table.appendChild(tbody);
  // memasukkan tabel ke view
  container.appendChild(table);
}

function renderTableUpload(data, selectedPeriode, komponen) {
  // container table
  var container = document.getElementById("PDRBTableContainer");

  // delete table if there is content inside it
  container.innerHTML = "";

  // create elemen tabel
  var table = document.createElement("table");
  table.id = "PDRBTable";
  table.classList.add("table", "table-bordered", "table-hover", "PDRBTable");

  // create table header
  var thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  var headerRow = document.createElement("tr");

  // var headerRow = table.insertRow();
  var headerKomponen = document.createElement("th");
  headerKomponen.colSpan = "2";
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
  var idBold = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
  for (var i = 0; i < komponen.length; i++) {
    // menghitung banyak kolom pada tabel
    var totalColumn = headerRow.childElementCount;

    var id_komponen = komponen[i].id_komponen;

    // insert row
    var row = document.createElement("tr");
    if (idBold.includes(id_komponen)) {
      row.style = "font-weight: bold;";
    }

    for (var col = 0; col < totalColumn; col++) {
      var cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        if (!idBold.includes(id_komponen)) {
          cell.classList = "pl-5";
        }

        if (id_komponen == 9) {
          cell.innerHTML = komponen[i].komponen;
        } else {
          cell.innerHTML = id_komponen + ". " + komponen[i].komponen;
        }
      } else {
        temp++;
        cell.style = "text-align: right;";
        if (document.title == "TEMPORAL | Upload Data") {
          cell.innerHTML = data[col - 1][i]
            ? numberFormat(data[col - 1][i].nilai)
            : "";
        } else {
          cell.innerHTML = numberFormat(data[temp].nilai);
        }
      }

      row.appendChild(cell);
    }
    tbody.appendChild(row);
  }

  table.appendChild(tbody);
  // memasukkan tabel ke view
  container.appendChild(table);
}

function renderTable_ringkasan(
  data,
  komponen,
  selectedPeriode,
  wilayah,
  jenisTabel
) {
  // container table
  var container = document.getElementById("ringkasan-container");

  // delete table if there is content inside it
  container.innerHTML = "";

  // create elemen tabel
  var table = document.createElement("table");
  table.id = "tabelRingkasan";
  table.classList.add("table", "table-bordered", "table-hover", "PDRBTable");

  // create table header
  var thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  var headerRow = document.createElement("tr");
  var headerRow2 = document.createElement("tr");

  var headerKomponen = document.createElement("th");
  headerKomponen.colSpan = "2";
  headerKomponen.rowSpan = "2";
  headerKomponen.style =
    "vertical-align: middle;  position: sticky; left: 0; z-index: 1; background-color: #B8DAFF";
  headerKomponen.innerHTML = "Komponen";
  headerRow.appendChild(headerKomponen);

  for (var i = 0; i < selectedPeriode.length; i++) {
    var columnName = selectedPeriode[i];
    var headerCell = document.createElement("th");
    headerCell.colSpan = "7";
    headerCell.innerHTML = columnName;

    wilayah.forEach((columns) => {
      var headerCell2 = document.createElement("th");
      headerCell2.style =
        "max-width: 105px; overflow: auto; white-space: normal;vertical-align: middle;";
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
  var idBold = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
  var idTabel = ["15", "16", "17", "19", "20", "21", "22", "23"];
  for (var i = 0; i < komponen.length; i++) {
    var id_komponen = komponen[i].id_komponen;

    // insert row
    var row = document.createElement("tr");
    if (idBold.includes(id_komponen)) {
      row.style = "font-weight: bold;";
    }

    for (var col = 0; col < totalColumn; col++) {
      var cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        cell.style =
          "position: sticky; left: 0; z-index: 1; background-color: #fff;";
        if (!idBold.includes(id_komponen)) {
          cell.classList = "pl-5";
        }
        if (id_komponen == 9) {
          cell.innerHTML = komponen[i].komponen;
        } else {
          cell.innerHTML = id_komponen + ". " + komponen[i].komponen;
        }
      } else {
        temp++;
        cell.style = "text-align: right;";
        if (document.title == "TEMPORAL | Upload Data") {
          cell.innerHTML = data[col - 1][i]
            ? numberFormat(data[col - 1][i].nilai)
            : "";
        } else {
          if (jenisTabel.includes(idTabel)) {
            // ini kasih if data kosong
            if (data[temp].nilai) {
              if (data[temp].nilai < 0) {
                cell.classList.add("text-danger");
                cell.innerHTML = `*${numberFormat(data[temp].nilai)}`;
              } else {
                cell.innerHTML = numberFormat(data[temp].nilai);
              }
            } else {
              cell.innerHTML = "";
            }
          } else {
            cell.innerHTML = data[temp].nilai
              ? numberFormat(data[temp].nilai)
              : "";
          }
        }
      }
      row.appendChild(cell);
    }
    tbody.appendChild(row);
  }
  table.appendChild(tbody);
  // memasukkan tabel ke view
  container.appendChild(table);
}

function renderTable_ringkasanGrowth(data, komponen, selectedPeriode, wilayah) {
  // container table
  var container = document.getElementById("ringkasan-container");

  // delete table if there is content inside it
  container.innerHTML = "";

  // create elemen tabel
  var table = document.createElement("table");
  table.id = "tabelRingkasan";
  table.classList.add("table", "table-bordered", "table-hover", "PDRBTable");

  // create table header
  var thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  var headerRow = document.createElement("tr");
  var headerRow2 = document.createElement("tr");

  var headerKomponen = document.createElement("th");
  headerKomponen.colSpan = "2";
  headerKomponen.rowSpan = "2";
  headerKomponen.style =
    "vertical-align: middle;  position: sticky; left: 0; z-index: 1; background-color: #B8DAFF";
  headerKomponen.innerHTML = "Komponen";
  headerRow.appendChild(headerKomponen);

  for (var i = 0; i < selectedPeriode.length; i++) {
    var columnName = selectedPeriode[i];
    var headerCell = document.createElement("th");
    headerCell.colSpan = "8";
    headerCell.innerHTML = columnName;

    var headercell2 = document.createElement("th");
    headercell2.style =
      "max-width: 105px; overflow: auto; white-space: normal;vertical-align: middle;";
    headercell2.innerHTML = "Provinsi DKI Jakarta";

    var headercell3 = document.createElement("th");
    headercell3.style =
      "max-width: 105px; overflow: auto; white-space: normal;vertical-align: middle;";
    headercell3.innerHTML = "Total Kab/Kota";

    headerRow2.appendChild(headercell2);
    headerRow2.appendChild(headercell3);

    wilayah.forEach((columns) => {
      if (columns.id_wilayah != "3100") {
        var headerCell4 = document.createElement("th");
        headerCell4.style =
          "max-width: 105px; overflow: auto; white-space: normal;vertical-align: middle;";
        headerCell4.innerHTML = columns.wilayah;
        headerRow2.appendChild(headerCell4);
      }
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
  var idBold = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
  for (var i = 0; i < komponen.length; i++) {
    var id_komponen = komponen[i].id_komponen;

    // insert row
    var row = document.createElement("tr");
    if (idBold.includes(id_komponen)) {
      row.style = "font-weight: bold;";
    }

    for (var col = 0; col < totalColumn; col++) {
      var cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        cell.style =
          "position: sticky; left: 0; z-index: 1; background-color: #fff;";
        if (!idBold.includes(id_komponen)) {
          cell.classList = "pl-5";
        }
        if (id_komponen == 9) {
          cell.innerHTML = komponen[i].komponen;
        } else {
          cell.innerHTML = id_komponen + ". " + komponen[i].komponen;
        }
      } else {
        temp++;
        cell.style = "text-align: right;";
        cell.classList.add("col-6");
        if (document.title == "TEMPORAL | Upload Data") {
          cell.innerHTML = data[col - 1][i]
            ? numberFormat(data[col - 1][i].nilai)
            : "";
        } else {
          // ini kasih if data kosong
          if (data[temp].nilai) {
            if (data[temp].nilai < 0) {
              cell.classList.add("text-danger");
              cell.innerHTML = `*${numberFormat(data[temp].nilai)}`;
            } else {
              cell.innerHTML = numberFormat(data[temp].nilai);
            }
          } else {
            cell.innerHTML = "";
          }
        }
      }
      row.appendChild(cell);
    }
    tbody.appendChild(row);
  }
  table.appendChild(tbody);

  // memasukkan tabel ke view
  container.appendChild(table);
}

function renderTable_ringkasan14(data, komponen, selectedPeriode, wilayah) {
  wilayah.sort((a, b) => {
    // Gunakan a.id dan b.id jika Anda ingin mengurutkan berdasarkan properti "id"
    if (a.id_wilayah < b.id_wilayah) {
      return -1; // a harus ditempatkan sebelum b
    }
    if (a.id_wilayah > b.id_wilayah) {
      return 1; // b harus ditempatkan sebelum a
    }
    return 0; // a dan b setara
  });

  // container table
  var container = document.getElementById("ringkasan-container");

  // delete table if there is content inside it
  container.innerHTML = "";

  // create elemen tabel
  var table = document.createElement("table");
  table.id = "tabelRingkasan";
  table.classList.add("table", "table-bordered", "table-hover", "PDRBTable");

  // create table header
  var thead = document.createElement("thead");
  thead.classList.add(
    "text-center",
    "table-primary",
    "sticky-top",
    "PDRBTable"
  );
  var headerRow = document.createElement("tr");
  var headerRow2 = document.createElement("tr");

  var headerKomponen = document.createElement("th");
  headerKomponen.colSpan = "2";
  headerKomponen.rowSpan = "2";
  headerKomponen.style =
    "vertical-align: middle;  position: sticky; left: 0; z-index: 1; background-color: #B8DAFF";
  headerKomponen.innerHTML = "Komponen";
  headerRow.appendChild(headerKomponen);

  for (var i = 0; i < selectedPeriode.length; i++) {
    var columnName = selectedPeriode[i];
    var headerCell = document.createElement("th");
    headerCell.colSpan = "6";
    headerCell.innerHTML = columnName;

    wilayah.forEach((columns) => {
      var headerCell2 = document.createElement("th");
      headerCell2.style =
        "max-width: 105px; overflow: auto; white-space: normal;vertical-align: middle;";
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
  var idBold = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
  for (var i = 0; i < komponen.length; i++) {
    var id_komponen = komponen[i].id_komponen;

    // insert row
    var row = document.createElement("tr");
    if (idBold.includes(id_komponen)) {
      row.style = "font-weight: bold;";
    }

    for (var col = 0; col < totalColumn; col++) {
      var cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        cell.style =
          "position: sticky; left: 0; z-index: 1; background-color: #fff;";
        if (!idBold.includes(id_komponen)) {
          cell.classList = "pl-5";
        }
        if (id_komponen == 9) {
          cell.innerHTML = komponen[i].komponen;
        } else {
          cell.innerHTML = id_komponen + ". " + komponen[i].komponen;
        }
      } else {
        temp++;
        cell.style = "text-align: right;";
        cell.classList.add("col-6");
        if (document.title == "TEMPORAL | Upload Data") {
          cell.innerHTML = data[col - 1][i]
            ? numberFormat(data[col - 1][i].nilai)
            : "";
        } else {
          cell.innerHTML = data[temp].nilai
            ? numberFormat(data[temp].nilai)
            : "";
          // numberFormat(data[temp].nilai);
        }
      }
      row.appendChild(cell);
    }
    tbody.appendChild(row);
  }

  table.appendChild(tbody);
  // memasukkan tabel ke view
  container.appendChild(table);
}

function renderTable_diskrepansi(data, komponen, selectedPeriode, wilayah) {
  // container table
  var container = document.getElementById("ringkasan-container");

  // delete table if there is content inside it
  container.innerHTML = "";

  // create elemen tabel
  var table = document.createElement("table");
  table.id = "tabelRingkasan";
  table.classList.add("table", "table-bordered", "table-hover", "PDRBTable");

  // create table header
  var thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  var headerRow = document.createElement("tr");
  var headerRow2 = document.createElement("tr");

  var headerKomponen = document.createElement("th");
  headerKomponen.colSpan = "2";
  headerKomponen.rowSpan = "2";
  headerKomponen.style =
    "vertical-align: middle;  position: sticky; left: 0; z-index: 1; background-color: #B8DAFF";
  headerKomponen.innerHTML = "Komponen";
  headerRow.appendChild(headerKomponen);

  for (var i = 0; i < selectedPeriode.length; i++) {
    var columnName = selectedPeriode[i];
    var headerCell = document.createElement("th");
    headerCell.colSpan = "9";
    headerCell.innerHTML = columnName;

    var headerCell2 = document.createElement("th");
    headerCell2.style =
      "max-width: 110px; overflow: auto; white-space: normal;vertical-align: middle;";
    headerCell2.innerHTML = "Diskrepansi";
    var headerCell3 = document.createElement("th");
    headerCell3.style =
      "max-width: 105px; overflow: auto; white-space: normal;vertical-align: middle;";
    headerCell3.innerHTML = "Provinsi DKI Jakarta";
    var headerCell4 = document.createElement("th");
    headerCell4.style =
      "max-width: 105px; overflow: auto; white-space: normal;vertical-align: middle;";
    headerCell4.innerHTML = "Total Kab/Kota";

    headerRow2.appendChild(headerCell2);
    headerRow2.appendChild(headerCell3);
    headerRow2.appendChild(headerCell4);

    wilayah.forEach((columns) => {
      if (columns.id_wilayah != "3100") {
        var headerCell5 = document.createElement("th");
        headerCell5.style =
          "max-width: 105px; overflow: auto; white-space: normal;vertical-align: middle;";
        headerCell5.innerHTML = columns.wilayah;
        headerRow2.appendChild(headerCell5);
      }
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
  var idBold = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
  for (var i = 0; i < komponen.length; i++) {
    var id_komponen = komponen[i].id_komponen;

    // insert row
    var row = document.createElement("tr");
    if (idBold.includes(id_komponen)) {
      row.style = "font-weight: bold;";
    }

    for (var col = 0; col < totalColumn; col++) {
      var cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        cell.style =
          "position: sticky; left: 0; z-index: 1; background-color: #fff;";
        if (!idBold.includes(id_komponen)) {
          cell.classList = "pl-5";
        }
        if (id_komponen == 9) {
          cell.innerHTML = komponen[i].komponen;
        } else {
          cell.innerHTML = id_komponen + ". " + komponen[i].komponen;
        }
      } else {
        temp++;
        cell.style = "text-align: right;";
        cell.classList.add("col-6");
        if (document.title == "TEMPORAL | Upload Data") {
          cell.innerHTML = data[col - 1][i]
            ? numberFormat(data[col - 1][i].nilai)
            : "";
        } else {
          cell.innerHTML = data[temp].nilai
            ? numberFormat(data[temp].nilai)
            : "";
        }
      }
      row.appendChild(cell);
    }
    tbody.appendChild(row);
  }

  table.appendChild(tbody);

  // memasukkan tabel ke view
  container.appendChild(table);
}

function getPutaranPeriode(periode) {
  let putaran = [];
  $.ajax({
    type: "POST",
    url: "/tabelPDRB/tabelHistoryPutaran/getPutaranPeriode/" + periode,
    dataType: "json",
    success: function (data) {
      data.forEach((item) => {
        putaran.push(item);
      });
      generateCheckboxesPutaran(data);
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error);
    },
  });
  return putaran;
}

// dropdown putaran
function createDropdownPutaran(data) {
  const selectElement = document.createElement("select");
  selectElement.classList.add("form-control");
  selectElement.id.add("selectPutaran");

  data.forEach((item) => {
    const option = document.createElement("option");
    option.value = item.value; // Set the value attribute
    option.textContent = item.label; // Set the visible text
    selectElement.appendChild(option);
  });

  // Append the select element to the desired location in the DOM
  document.getElementById("selectPutaranContainer").appendChild(selectElement);
}

function numberFormat(
  number,
  decimals = 2,
  decimalSeparator = ",",
  thousandsSeparator = "."
) {
  number = parseFloat(number).toFixed(decimals);
  number = number.toString().replace(".", decimalSeparator);

  var parts = number.split(decimalSeparator);
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);

  return parts.join(decimalSeparator);
}

function fungsieksporButtonExcelPerkota() {
  let tableToExport;
  if (document.title == "TEMPORAL | Tabel Per Kota") {
    // export excel untuk halaman tabel perkota
    tableToExport = document.getElementById("tablePerkota");
  } else if (document.title == "TEMPORAL | Tabel Ringkasan") {
    // export excel untuk halaman tabel ringkasan
    tableToExport = document.getElementById("tabelRingkasan");
  } else {
    tableToExport = document.getElementById("PDRBTable");
  }
  // Ambil referensi tabel
  let workbook = new ExcelJS.Workbook();
  let worksheet = workbook.addWorksheet("Sheet1");
  // Membaca elemen thead
  let thead = tableToExport.querySelector("thead");
  // Menambahkan judul tabel di cell A1
  worksheet.getCell("A1").value = judulTable.textContent;
  worksheet.getCell("A1").font = { bold: true, size: 11 };
  if (document.title == "TEMPORAL | Tabel Per Kota" && jenisPDRB != 13) {
    // Menambahkan baris header di cell A3
    let headerRow = worksheet.getRow(3);
    let headerCells = thead.querySelectorAll("th");
    headerCells.forEach(function (headerCell, index) {
      headerRow.getCell(index + 1).value = headerCell.innerText;
      headerRow.getCell(index + 1).font = { bold: true };
      headerRow.getCell(index + 1).alignment = { horizontal: "center" };
    });

    // Membaca elemen tbody
    let tbody = tableToExport.querySelector("tbody");

    // Menambahkan baris data mulai dari cell A4
    let dataRows = tbody.querySelectorAll("tr");
    dataRows.forEach(function (dataRow, rowIndex) {
      let row = worksheet.addRow([]);
      let dataCells = dataRow.querySelectorAll("td");
      dataCells.forEach(function (dataCell, cellIndex) {
        row.getCell(cellIndex + 1).value = dataCell.innerText;

        // Mengatur alignment ke kanan untuk sel data (kolom selain A)
        if (cellIndex > 0) {
          row.getCell(cellIndex + 1).alignment = { horizontal: "right" };
          row.getCell(cellIndex + 1).value = row
            .getCell(cellIndex + 1)
            .value.replace(/\./g, "")
            .replace(",", ".");
        }
      });
      row.number = rowIndex + 4; // Mulai dari baris ke-4
    });

    // Menambahkan spasi pada cell subkomponen
    // Array subkomponen
    let subkomponens = [5, 6, 7, 8, 9, 10, 11, 15, 16];

    subkomponens.forEach(function (subkomponent) {
      worksheet.getCell(`A${subkomponent}`).value =
        "    " + worksheet.getCell(`A${subkomponent}`).value;
    });
  } else {
    // Membaca baris header
    let headerRows = thead.querySelectorAll("tr");
    let rowIndex = 3; // Mulai dari baris ke-2 dalam worksheet

    headerRows.forEach(function (headerRow) {
      let cells = headerRow.querySelectorAll("th");

      let columnIndex = 1; // Indeks kolom dalam Excel
      if (rowIndex == 4) {
        columnIndex = 2;
      }

      cells.forEach(function (headerCell) {
        let cell = worksheet.getCell(rowIndex, columnIndex);
        let colspan = headerCell.hasAttribute("colspan")
          ? parseInt(headerCell.getAttribute("colspan"))
          : 1;
        let rowspan = headerCell.hasAttribute("rowspan")
          ? parseInt(headerCell.getAttribute("rowspan"))
          : 1;

        // Set nilai sel header
        cell.value = headerCell.innerText;
        cell.font = { bold: true };
        cell.alignment = { horizontal: "center", vertical: "middle" };

        // Gabung sel-sel jika diperlukan
        if (colspan > 1 || rowspan > 1) {
          if (cell.value == "Komponen") {
            worksheet.mergeCells(
              rowIndex,
              columnIndex,
              rowIndex + rowspan - 1,
              columnIndex + colspan - 2
            );
          } else {
            worksheet.mergeCells(
              rowIndex,
              columnIndex,
              rowIndex + rowspan - 1,
              columnIndex + colspan - 1
            );
          }
        }
        if (cell.value == "Komponen") {
          columnIndex += colspan - 3;
        }
        columnIndex += colspan;
      });

      rowIndex += 1;
    });

    // Membaca elemen tbody
    let tbody = tableToExport.querySelector("tbody");

    // Menambahkan baris data mulai dari cell A3
    let dataRows = tbody.querySelectorAll("tr");
    dataRows.forEach(function (dataRow, rowIndex) {
      let row = worksheet.addRow([]);
      let dataCells = dataRow.querySelectorAll("td");
      dataCells.forEach(function (dataCell, cellIndex) {
        row.getCell(cellIndex + 1).value = dataCell.innerText;

        // Mengatur alignment ke kanan untuk sel data (kolom selain A)
        if (cellIndex > 0) {
          row.getCell(cellIndex + 1).alignment = { horizontal: "right" };
          row.getCell(cellIndex + 1).value = row
            .getCell(cellIndex + 1)
            .value.replace(/\./g, "")
            .replace(",", ".");
        }
      });
      row.number = rowIndex + rowIndex + 4; // Mulai dari baris ke-3
    });

    // Menambahkan spasi pada cell subkomponen
    // Array subkomponen
    let subkomponens = [6, 7, 8, 9, 10, 11, 12, 16, 17];

    subkomponens.forEach(function (subkomponent) {
      worksheet.getCell(`A${subkomponent}`).value =
        "    " + worksheet.getCell(`A${subkomponent}`).value;
    });
  }
  // Mengatur border untuk seluruh tabel
  worksheet.eachRow(function (row) {
    row.eachCell(function (cell) {
      cell.border = {
        top: { style: "thin" },
        left: { style: "thin" },
        bottom: { style: "thin" },
        right: { style: "thin" },
      };
    });
  });

  // Mengatur lebar kolom secara otomatis
  worksheet.columns.forEach((column) => {
    let maxCellLength = 0;
    column.eachCell({ includeEmpty: true }, (cell) => {
      maxCellLength = Math.max(
        maxCellLength,
        cell.value ? cell.value.toString().length : 0
      );
    });
    column.width = maxCellLength + 2;
  });

  // Membuat tautan unduhan
  workbook.xlsx.writeBuffer().then(function (data) {
    let blob = new Blob([data], {
      type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    });
    let url = window.URL.createObjectURL(blob);
    let a = document.createElement("a");
    a.href = url;
    a.download = judulTable.textContent + ".xlsx";
    a.click();
    window.URL.revokeObjectURL(url);
  });
}

function fungsieksporButtonExcelAllPutaran() {
  kirimData(jenisPDRB, kota, selectedPutaran, selectedPeriode, true);

  let tableToExport;
  tableToExport = document.getElementById("PDRBTable");

  // Ambil referensi tabel
  let workbook = new ExcelJS.Workbook();
  let worksheet = workbook.addWorksheet("Sheet1");

  // Membaca elemen thead
  let thead = tableToExport.querySelector("thead");

  // Menambahkan judul tabel di cell A1
  worksheet.getCell("A1").value = judulTable.textContent;
  worksheet.getCell("A1").font = { bold: true, size: 11 };

  // Membaca baris header
  let headerRows = thead.querySelectorAll("tr");
  let rowIndex = 3; // Mulai dari baris ke-2 dalam worksheet

  headerRows.forEach(function (headerRow) {
    let cells = headerRow.querySelectorAll("th");

    let columnIndex = 1; // Indeks kolom dalam Excel
    if (rowIndex == 4) {
      columnIndex = 2;
    }

    cells.forEach(function (headerCell) {
      let cell = worksheet.getCell(rowIndex, columnIndex);
      let colspan = headerCell.hasAttribute("colspan")
        ? parseInt(headerCell.getAttribute("colspan"))
        : 1;
      let rowspan = headerCell.hasAttribute("rowspan")
        ? parseInt(headerCell.getAttribute("rowspan"))
        : 1;

      // Set nilai sel header
      cell.value = headerCell.innerText;
      cell.font = { bold: true };
      cell.alignment = { horizontal: "center", vertical: "middle" };

      // Gabung sel-sel jika diperlukan
      if (colspan > 1 || rowspan > 1) {
        if (cell.value == "Komponen") {
          worksheet.mergeCells(
            rowIndex,
            columnIndex,
            rowIndex + rowspan - 1,
            columnIndex + colspan - 2
          );
        } else {
          worksheet.mergeCells(
            rowIndex,
            columnIndex,
            rowIndex + rowspan - 1,
            columnIndex + colspan - 1
          );
        }
      }
      if (cell.value == "Komponen") {
        columnIndex += colspan - 3;
      }
      columnIndex += colspan;
    });

    rowIndex += 1;
  });

  // Membaca elemen tbody
  let tbody = tableToExport.querySelector("tbody");

  // Menambahkan baris data mulai dari cell A3
  let dataRows = tbody.querySelectorAll("tr");
  dataRows.forEach(function (dataRow, rowIndex) {
    let row = worksheet.addRow([]);
    let dataCells = dataRow.querySelectorAll("td");
    dataCells.forEach(function (dataCell, cellIndex) {
      row.getCell(cellIndex + 1).value = dataCell.innerText;

      // Mengatur alignment ke kanan untuk sel data (kolom selain A)
      if (cellIndex > 0) {
        row.getCell(cellIndex + 1).alignment = { horizontal: "right" };
        row.getCell(cellIndex + 1).value = row
          .getCell(cellIndex + 1)
          .value.replace(/\./g, "")
          .replace(",", ".");
      }
    });
    row.number = rowIndex + rowIndex + 4; // Mulai dari baris ke-3
  });

  // Menambahkan spasi pada cell subkomponen
  // Array subkomponen
  let subkomponens = [6, 7, 8, 9, 10, 11, 12, 16, 17];

  subkomponens.forEach(function (subkomponent) {
    worksheet.getCell(`A${subkomponent}`).value =
      "    " + worksheet.getCell(`A${subkomponent}`).value;
  });

  // Mengatur border untuk seluruh tabel
  worksheet.eachRow(function (row) {
    row.eachCell(function (cell) {
      cell.border = {
        top: { style: "thin" },
        left: { style: "thin" },
        bottom: { style: "thin" },
        right: { style: "thin" },
      };
    });
  });

  // Mengatur lebar kolom secara otomatis
  worksheet.columns.forEach((column) => {
    let maxCellLength = 0;
    column.eachCell({ includeEmpty: true }, (cell) => {
      maxCellLength = Math.max(
        maxCellLength,
        cell.value ? cell.value.toString().length : 0
      );
    });
    column.width = maxCellLength + 2;
  });

  // Membuat tautan unduhan
  workbook.xlsx.writeBuffer().then(function (data) {
    let blob = new Blob([data], {
      type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    });
    let url = window.URL.createObjectURL(blob);
    let a = document.createElement("a");
    a.href = url;
    a.download = judulTable.textContent + ".xlsx";
    a.click();
    window.URL.revokeObjectURL(url);
  });
}

// ekspor pdf
if (eksporButtonPDF != null) {
  eksporButtonPDF.addEventListener("click", function () {
    let komponenIndex = [0, 8, 9, 10, 13, 14, 15, 16, 17];
    const htmlElement = document.querySelector(".PDRBTable");

    // manual workaround untuk ekspor pdf tabel yang headernya memiliki colspan
    let headerColSpan = 0;
    if (tableRingkasan) {
      let jenisTable = tableRingkasan.options[tableRingkasan.selectedIndex].id;
      switch (jenisTable) {
        case "11":
        case "12":
          headerColSpan = 9;
          break;
        case "14":
          headerColSpan = 6;
          break;
        case "15":
        case "16":
        case "17":
        case "19":
        case "20":
          headerColSpan = 8;
          break;
        default:
          headerColSpan = 7;
          break;
      }
    }
    if (tableToExport) {
      let jenisTable = tableToExport.options[tableToExport.selectedIndex].id;
      if (jenisTable == "13") headerColSpan = 5;
    }
    if (tableArahRevisi) {
      headerColSpan = 3;
    }

    if (headerColSpan != 0) {
      const rows = htmlElement.getElementsByTagName("tr");
      var cells = rows[0].getElementsByTagName("th");
      for (let i = 0; i < cells.length; i++) {
        if (i > 0) {
          cells[i].colSpan = 1;
          for (let j = 0; j < headerColSpan - 1; j++) {
            const newCell = document.createElement("td");
            cells[i].after(newCell);
          }
        }
      }
    }

    // ekspor
    let doc = new jspdf.jsPDF("landscape");
    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    docTitle = doc.splitTextToSize(judulTable.textContent, 180);
    doc.text(docTitle, 14, 10);
    doc.autoTable({
      startY: 20,
      html: htmlElement,
      columnStyles: {
        0: { cellWidth: 100 },
      },
      theme: "grid",
      didParseCell: function (hookData) {
        if (komponenIndex.includes(hookData.row.index)) {
          hookData.cell.styles.fontStyle = "bold";
        }
      },
      horizontalPageBreak: true,
      horizontalPageBreakRepeat: 0,
    });
    let filename = judulTable.textContent + ".pdf";
    doc.save(filename);

    // mengembalikan struktur tabel yang telah dimodifikasi di 'manual workaround' di atas
    if (headerColSpan != 0) {
      for (let i = 0; i < cells.length; i++) {
        if (i > 0) {
          const cell = cells[i];
          for (let j = 0; j < headerColSpan - 1; j++) {
            cell.parentElement.removeChild(cell.nextSibling);
          }
          cell.colSpan = headerColSpan;
        }
      }
    }
  });
}

// generate dropdown jenis tabel halaman tabel ringkasan
function generateDropdownTabelRingkasan() {
  const dropdownContainer = document.getElementById("dropdownTabelRingkasan");

  // generate dropdown
  const select = document.createElement("select");
  select.classList.add("form-control");
  select.style = "width: 100%; max-width: 600px";
  select.id = "selectTable";

  var options = [
    { value: "Pilih Jenis Tabel", text: "Pilih Jenis Tabel" },
    {
      value: "dikrepansi-ADHB",
      text: "Tabel 1.11. Diskrepansi PDRB ADHB Menurut Pengeluaran Provinsi dan 6 Kabupaten/Kota (Juta Rupiah)",
    },
    {
      value: "dikrepansi-ADHK",
      text: "Tabel 1.12. Diskrepansi PDRB ADHK Menurut Pengeluaran Provinsi dan 6 Kabupaten/Kota (Juta Rupiah)",
    },
    {
      value: "distribusi-persentase-PDRB-ADHB",
      text: "Tabel 1.13. Distribusi Persentase PDRB ADHB Provinsi dan 6 Kabupaten/Kota",
    },
    {
      value: "distribusi-persentase-PDRB-Total",
      text: "Tabel 1.14. Distribusi Persentase PDRB Kabupaten Kota Terhadap Total Provinsi",
    },
    {
      value: "perbandingan-pertumbuhan-Q-TO-Q",
      text: "Tabel 1.15. Perbandingan Pertumbuhan Ekonomi Provinsi DKI Jakarta dan 6 Kabupaten/Kota (Q-TO-Q)",
    },
    {
      value: "perbandingan-pertumbuhan-Y-ON-Y",
      text: "Tabel 1.16. Perbandingan Pertumbuhan Ekonomi Provinsi DKI Jakarta dan 6 Kabupaten/Kota (Y-ON-Y)",
    },
    {
      value: "perbandingan-pertumbuhan-C-TO-C",
      text: "Tabel 1.17. Perbandingan Pertumbuhan Ekonomi Provinsi DKI Jakarta dan 6 Kabupaten/Kota (C-TO-C)",
    },
    {
      value: "indeks-implisit",
      text: "Tabel 1.18. Indeks Implisit PDRB Provinsi dan Kabupaten/Kota",
    },
    {
      value: "pertumbuhan-indeks-implisit-Q-TO-Q",
      text: "Tabel 1.19. Pertumbuhan Indeks Implisit Provinsi dan Kabupaten/Kota (Q-TO-Q)",
    },
    {
      value: "pertumbuhan-indeks-implisit-Y-ON-Y",
      text: "Tabel 1.20. Pertumbuhan Indeks Implisit Provinsi dan Kabupaten/Kota (Y-ON-Y)",
    },
    {
      value: "sumber-pertumbuhan-Q-TO-Q",
      text: "Tabel 1.23. Sumber Pertumbuhan Ekonomi Provinsi dan 6 Kabupaten/Kota (Q-TO-Q)",
    },
    {
      value: "sumber-pertumbuhan-Y-ON-Y",
      text: "Tabel 1.24. Sumber Pertumbuhan Ekonomi Provinsi dan 6 Kabupaten/Kota (Y-ON-Y)",
    },
    {
      value: "sumber-pertumbuhan-C-TO-C",
      text: "Tabel 1.25. Sumber Pertumbuhan Ekonomi Provinsi dan 6 Kabupaten/Kota (C-TO-C)",
    },
    {
      value: "ringkasan-pertumbuhan-ekstrem",
      text: "Tabel 1.26. Ringkasan Pertumbuhan Ekstrim Provinsi dan 6 Kabupaten Kota",
    },
  ];

  generateOptions(options);
}

// Loop untuk membuat elemen-elemen option
function generateOptions(options) {
  var option;
  for (var i = 0; i < options.length; i++) {
    option = document.createElement("option");
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

if (document.getElementById("selectTableArahRevisi") != null) {
  document
    .getElementById("selectTableArahRevisi")
    .addEventListener("change", function () {
      loadData();
    });
}

if (document.getElementById("selectTableRingkasan") != null) {
  document
    .getElementById("selectTableRingkasan")
    .addEventListener("change", function () {
      loadData();
    });
}

if (document.getElementById("selectTableHistory") != null) {
  document
    .getElementById("selectTableHistory")
    .addEventListener("change", function () {
      loadData();
    });
}

const loadOnTitle = [
  "TEMPORAL | Upload Data",
  "TEMPORAL | Tabel Per Kota",
  "TEMPORAL | Tabel Ringkasan",
  "TEMPORAL | Arah Revisi",
  "TEMPORAL | Tabel History Putaran",
];

if (loadOnTitle.includes(document.title)) {
  window.addEventListener("load", function () {
    loadData();
  });
}

// dropdown kota
if (selectKota != null) {
  // Menampilkan judul modal sesuai wilayah yang terpilih
  if (kotaJudulModal != null && judulModal != null) {
    kotaJudulModal.setAttribute("value", "3100");
    selectKota.addEventListener("change", function () {
      judulModal.textContent = "Upload PDRB - " + this.selectedOptions[0].text;
      kotaJudulModal.setAttribute("value", this.value);
    });
  }
  selectKota.addEventListener("change", function () {
    loadData();
  });
}

// get current date time
function getCurrentDateTime() {
  const now = new Date();

  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, "0"); // Tambah 1 karena bulan dimulai dari 0
  const day = String(now.getDate()).padStart(2, "0");

  const hours = String(now.getHours()).padStart(2, "0");
  const minutes = String(now.getMinutes()).padStart(2, "0");
  const seconds = String(now.getSeconds()).padStart(2, "0");

  const formattedDateTime = `${year}-${month}-${day} ${hours}_${minutes}_${seconds}`;

  return formattedDateTime;
}
