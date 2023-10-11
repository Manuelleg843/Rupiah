const selectKota = document.getElementById("selectKota");
const selectPutaran = document.getElementById("selectPutaran");
const judulTable = document.getElementById("judulTable");
const modalWilayah = document.getElementById("modalWilayah");
const judulTableADHB = document.getElementById("judulTableADHB");
const judulTableADHK = document.getElementById("judulTableADHK");
const submitPeriode = document.getElementById("simpan-periode");
const submitKomponen = document.getElementById("simpan-komponen");
const subJudul = document.createElement("p");
const judulModal = document.getElementById("judulModal");
const kotaJudulModal = document.getElementById("kotaJudulModal");
const eksporButton = document.getElementById("export-button");
const currentQuarter = Math.floor((new Date().getMonth() + 3) / 3);
var tableSelected;
var kotaSelected;
var putaran;
var jenisPDRB;
var kota;
var exportExcel = document.getElementById("exportExcel");

// munculin data on load
function loadData() {
  var tableJenisPDRB = document.getElementById("selectTableHistory");
  var tableRingkasan = document.getElementById("selectTableRingkasan");
  var tableUpload = document.getElementById("selectTableUpload");
  var tableArahRevisi = document.getElementById("selectTableArahRevisi");
  let tablePerkota = document.getElementById("selectTablePerkota");

  if (tableJenisPDRB) {
    tableSelected =
      tableJenisPDRB.options[tableJenisPDRB.selectedIndex].textContent;
    jenisPDRB = tableJenisPDRB.value;
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
    jenisTable = tableArahRevisi.options[tableArahRevisi.selectedIndex].id;
  }

  var selectedKomponen = [];
  var selectedPeriode = [];

  if (submitPeriode) {
    $('.checkboxes-periode input[type="checkbox"]:checked').each(function () {
      selectedPeriode.push($(this).attr("name"));
    });
  }

  // menampilkan periode default untuk tabel di halaman upload
  if (document.title == "Rupiah | Upload Data") {
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

  // mengganti judul tabel
  switch (document.title) {
    case "Rupiah | Tabel History Putaran":
      judulTable.textContent =
        tableSelected + " - " + kotaSelected + " - Putaran " + putaran;
      kirimData(jenisPDRB, kota, putaran, selectedPeriode, selectedKomponen);
      break;
    case "Rupiah | Tabel Ringkasan":
      judulTable.textContent = tableSelected;
      // create element subJudul untuk tabel 1.15, 1.16, 1.17
      if (subJudul) {
        subJudul.textContent = "";
      }
      if (jenisTable == "15" || jenisTable == "16" || jenisTable == "17") {
        // const subJudul = document.createElement("p");
        subJudul.textContent = "Keterangan warna merah = pertumbuhan > 5";
        const containerJudul = document.getElementById("judulTable-container");
        containerJudul.appendChild(subJudul);
      }
      kirimDataRingkasan(jenisTable, selectedPeriode, selectedKomponen);
      break;
    case "Rupiah | Upload Data":
      judulTable.textContent = tableSelected + " - " + kotaSelected;
      kirimDataTabelUpload(jenisPDRB, kota, selectedPeriode);
      break;
    case "Rupiah | Tabel Per Kota":
      judulTable.textContent = tableSelected + " - " + kotaSelected;
      kirmdataPerKota(jenisPDRB, kota, selectedPeriode);
      break;
    case "Rupiah | Arah Revisi":
      judulTable.textContent = tableSelected + " - " + kotaSelected;
      kirimDataArahRevisi(jenisTable, kota, selectedPeriode);
      break;
  }
}

function kirimData(
  jenisPDRB,
  kota,
  putaran,
  selectedPeriode,
  selectedKomponen
) {
  $.ajax({
    type: "POST",
    url: "/tabelPDRB/tabelHistoryPutaran/getData",
    data: {
      jenisPDRB: jenisPDRB,
      kota: kota,
      putaran: putaran,
      periode: selectedPeriode,
      komponen: selectedKomponen,
    },
    dataType: "json",
    success: function (data) {
      renderTable(data["dataPDRB"], data["selectedPeriode"], data["komponen"]);
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error.message);
    },
  });
}

function kirmdataPerKota(jenisPDRB, kota, selectedPeriode) {
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
      console.log(data);
      renderTablePerKota(data);
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error);
    },
  });
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
          // console.log(data);
          break;
        case "13":
          console.log(data);
          renderTable_ringkasan(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
          break;
        case "14":
          // console.log(data);
          renderTable_ringkasan14(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"]
          );
          break;
        case "15":
          // console.log(data);
          renderTable_ringkasan(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
          break;
        case "16":
          // console.log(data['dataRingkasan']);
          renderTable_ringkasan(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
          break;
        case "17":
          console.log(data);
          renderTable_ringkasan(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
          break;
        case "18":
          // console.log(data);
          renderTable_ringkasan(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
          break;
        case "19":
          // console.log(data);
          renderTable_ringkasan(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
          break;
        case "20":
          // console.log(data);
          renderTable_ringkasan(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
          break;
        case "21":
          // console.log(data);
          renderTable_ringkasan(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
          break;
        case "22":
          // console.log(data);
          renderTable_ringkasan(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
          break;
        case "23":
          renderTable_ringkasan(
            data["dataRingkasan"],
            data["komponen"],
            data["selectedPeriode"],
            data["wilayah"],
            data["jenisTabel"]
          );
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
      renderTable(data["dataPDRB"], data["selectedPeriode"], data["komponen"]);
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
      console.log(data);
      renderTableArahRevisi(
        data["dataarahrevisi"]["array_Rilis"],
        data["dataarahrevisi"]["array_Revisi"],
        data["dataarahrevisi"]["array_Arah"],
        data["komponen"],
        selectedPeriode
      );
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error);
    },
  });
}

function renderTableArahRevisi(
  data_Rilis,
  data_Revisi,
  data_Arah,
  komponen,
  selectedPeriode
) {
  // container table
  console.log("ini jalan");
  var container = document.getElementById("arah-revisi-container");

  // delete table if there is content inside it
  container.innerHTML = "";

  // create elemen tabel
  var table = document.createElement("table");
  table.id = "tableArahRevisi";
  table.classList.add("table", "table-bordered", "table-hover");

  // create table header
  var thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  var headerRow = document.createElement("tr");
  var headerRow2 = document.createElement("tr");

  // var headerRow = table.insertRow();
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
  var temp = -1;
  var totalColumn = headerRow2.childElementCount + 1;

  // loop through json to create tbody
  for (var i = 0; i < komponen.length; i++) {
    var id_komponen = komponen[i].id_komponen;

    // insert row
    var row = document.createElement("tr");
    if (
      id_komponen == 1 ||
      id_komponen == 2 ||
      id_komponen == 3 ||
      id_komponen == 4 ||
      id_komponen == 5 ||
      id_komponen == 6 ||
      id_komponen == 7 ||
      id_komponen == 8 ||
      id_komponen == 9
    ) {
      row.style = "font-weight: bold;";
    }

    for (var col = 0; col < totalColumn; col++) {
      var cell = document.createElement("td");
      var cell_rilis = document.createElement("td");
      var cell_revisi = document.createElement("td");
      var cell_arah = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        if (
          id_komponen != 1 &&
          id_komponen != 2 &&
          id_komponen != 3 &&
          id_komponen != 4 &&
          id_komponen != 5 &&
          id_komponen != 6 &&
          id_komponen != 7 &&
          id_komponen != 8 &&
          id_komponen != 9
        ) {
          cell.classList = "pl-5";
        }
        if (id_komponen == 9) {
          cell.innerHTML = komponen[i].komponen;
        } else {
          cell.innerHTML = id_komponen + ". " + komponen[i].komponen;
        }
      } else {
        cell_rilis.style = "text-align: right;";
        cell_rilis.classList.add("col-6");
        cell_rilis.innerHTML = data_Rilis[temp].nilai
          ? numberFormat(data_Rilis[temp].nilai)
          : "";
        cell_revisi.style = "text-align: right;";
        cell_revisi.classList.add("col-6");
        cell_revisi.innerHTML = data_Revisi[temp].nilai
          ? numberFormat(data_Revisi[temp].nilai)
          : "";
        cell_arah.style = "text-align: center;";
        cell_arah.classList.add("col-3");
        if (data_Arah[temp].nilai > 0) {
          cell_arah.classList.add("text-success");
          cell_arah.innerHTML =
            "+" + data_Arah[temp].nilai
              ? numberFormat(data_Arah[temp].nilai)
              : "";
        } else if (data_Arah[temp].nilai < 0) {
          cell_arah.classList.add("text-danger");
          cell_arah.innerHTML =
            "-" + data_Arah[temp].nilai
              ? numberFormat(data_Arah[temp].nilai)
              : "";
        } else {
          cell_arah.innerHTML =
            "=" + data_Arah[temp].nilai
              ? numberFormat(data_Arah[temp].nilai)
              : "";
        }
      }
      row.appendChild(cell);
      row.appendChild(cell_rilis);
      row.appendChild(cell_revisi);
      row.appendChild(cell_arah);
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
  table.id = "tabelPerkota";
  table.classList.add("table", "table-bordered", "table-hover");

  // create table header
  const thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  const headerRow = document.createElement("tr");

  // var headerRow = table.insertRow();
  const headerKomponen = document.createElement("th");
  headerKomponen.colSpan = "2";
  headerKomponen.rowSpan = "2";
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
  for (var i = 0; i < komponen.length; i++) {
    // menghitung banyak kolom pada tabel

    const id_komponen = komponen[i].id_komponen;

    // insert row
    const row = document.createElement("tr");
    if (
      id_komponen == 1 ||
      id_komponen == 2 ||
      id_komponen == 3 ||
      id_komponen == 4 ||
      id_komponen == 5 ||
      id_komponen == 6 ||
      id_komponen == 7 ||
      id_komponen == 8 ||
      id_komponen == 9
    ) {
      row.style = "font-weight: bold;";
    }

    for (let col = 0; col < totalColumn; col++) {
      const cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        if (
          id_komponen != 1 &&
          id_komponen != 2 &&
          id_komponen != 3 &&
          id_komponen != 4 &&
          id_komponen != 5 &&
          id_komponen != 6 &&
          id_komponen != 7 &&
          id_komponen != 8 &&
          id_komponen != 9
        ) {
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
        cell.classList.add("w-50");
        // cell.innerHTML = numberFormat(data[col - 1][i].nilai);
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

// render table
function renderTable(data, selectedPeriode, komponen) {
  // container table
  var container = document.getElementById("PDRBTableContainer");

  // delete table if there is content inside it
  container.innerHTML = "";

  // create elemen tabel
  var table = document.createElement("table");
  table.id = "PDRBTable";
  table.classList.add("table", "table-bordered", "table-hover");

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
  for (var i = 0; i < komponen.length; i++) {
    // menghitung banyak kolom pada tabel
    var totalColumn = headerRow.childElementCount;

    var id_komponen = komponen[i].id_komponen;

    // insert row
    var row = document.createElement("tr");
    if (
      id_komponen == 1 ||
      id_komponen == 2 ||
      id_komponen == 3 ||
      id_komponen == 4 ||
      id_komponen == 5 ||
      id_komponen == 6 ||
      id_komponen == 7 ||
      id_komponen == 8 ||
      id_komponen == 9
    ) {
      row.style = "font-weight: bold;";
    }

    for (var col = 0; col < totalColumn; col++) {
      var cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        if (
          id_komponen != 1 &&
          id_komponen != 2 &&
          id_komponen != 3 &&
          id_komponen != 4 &&
          id_komponen != 5 &&
          id_komponen != 6 &&
          id_komponen != 7 &&
          id_komponen != 8 &&
          id_komponen != 9
        ) {
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
        if (document.title == "Rupiah | Upload Data") {
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
  table.classList.add("table", "table-bordered", "table-hover");

  // create table header
  var thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  var headerRow = document.createElement("tr");
  var headerRow2 = document.createElement("tr");

  // var headerRow = table.insertRow();
  var headerKomponen = document.createElement("th");
  headerKomponen.colSpan = "2";
  headerKomponen.rowSpan = "2";
  headerKomponen.innerHTML = "Komponen";
  headerRow.appendChild(headerKomponen);

  for (var i = 0; i < selectedPeriode.length; i++) {
    var columnName = selectedPeriode[i];
    var headerCell = document.createElement("th");
    headerCell.colSpan = "7";
    headerCell.innerHTML = columnName;

    wilayah.forEach((columns) => {
      var headerCell2 = document.createElement("th");
      headerCell2.classList.add("w-50");
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
  for (var i = 0; i < komponen.length; i++) {
    var id_komponen = komponen[i].id_komponen;

    // insert row
    var row = document.createElement("tr");
    if (
      id_komponen == 1 ||
      id_komponen == 2 ||
      id_komponen == 3 ||
      id_komponen == 4 ||
      id_komponen == 5 ||
      id_komponen == 6 ||
      id_komponen == 7 ||
      id_komponen == 8 ||
      id_komponen == 9
    ) {
      row.style = "font-weight: bold;";
    }

    for (var col = 0; col < totalColumn; col++) {
      var cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        if (
          id_komponen != 1 &&
          id_komponen != 2 &&
          id_komponen != 3 &&
          id_komponen != 4 &&
          id_komponen != 5 &&
          id_komponen != 6 &&
          id_komponen != 7 &&
          id_komponen != 8 &&
          id_komponen != 9
        ) {
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
        if (document.title == "Rupiah | Upload Data") {
          cell.innerHTML = data[col - 1][i]
            ? numberFormat(data[col - 1][i].nilai)
            : "";
        } else {
          if (jenisTabel == "15" || jenisTabel == "16" || jenisTabel == "17") {
            if (data[temp].nilai > 5 || data[temp].nilai < -5) {
              cell.classList.add("text-danger");
              cell.innerHTML = data[temp].nilai
                ? `*${numberFormat(data[temp].nilai)}`
                : "";
            } else {
              cell.innerHTML = data[temp].nilai
                ? numberFormat(data[temp].nilai)
                : "";
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

function renderTable_ringkasan14(data, komponen, selectedPeriode, wilayah) {
  // container table
  var container = document.getElementById("ringkasan-container");

  // delete table if there is content inside it
  container.innerHTML = "";

  // create elemen tabel
  var table = document.createElement("table");
  table.id = "tabelRingkasan";
  table.classList.add("table", "table-bordered", "table-hover");

  // create table header
  var thead = document.createElement("thead");
  thead.classList.add("text-center", "table-primary", "sticky-top");
  var headerRow = document.createElement("tr");
  var headerRow2 = document.createElement("tr");

  // var headerRow = table.insertRow();
  var headerKomponen = document.createElement("th");
  headerKomponen.colSpan = "2";
  headerKomponen.rowSpan = "2";
  headerKomponen.innerHTML = "Komponen";
  headerRow.appendChild(headerKomponen);

  for (var i = 0; i < selectedPeriode.length; i++) {
    var columnName = selectedPeriode[i];
    var headerCell = document.createElement("th");
    headerCell.colSpan = "7";
    headerCell.innerHTML = columnName;

    wilayah.forEach((columns) => {
      var headerCell2 = document.createElement("th");
      headerCell2.classList.add("w-50");
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
  for (var i = 0; i < komponen.length; i++) {
    var id_komponen = komponen[i].id_komponen;

    // insert row
    var row = document.createElement("tr");
    if (
      id_komponen == 1 ||
      id_komponen == 2 ||
      id_komponen == 3 ||
      id_komponen == 4 ||
      id_komponen == 5 ||
      id_komponen == 6 ||
      id_komponen == 7 ||
      id_komponen == 8 ||
      id_komponen == 9
    ) {
      row.style = "font-weight: bold;";
    }

    for (var col = 0; col < totalColumn; col++) {
      var cell = document.createElement("td");

      if (col == 0) {
        cell.colSpan = "2";
        if (
          id_komponen != 1 &&
          id_komponen != 2 &&
          id_komponen != 3 &&
          id_komponen != 4 &&
          id_komponen != 5 &&
          id_komponen != 6 &&
          id_komponen != 7 &&
          id_komponen != 8 &&
          id_komponen != 9
        ) {
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
        if (document.title == "Rupiah | Upload Data") {
          cell.innerHTML = data[col - 1][i]
            ? numberFormat(data[col - 1][i].nilai)
            : "";
        } else {
          if (jenisTabel == "15" || jenisTabel == "16" || jenisTabel == "17") {
            if (data[temp].nilai > 5 || data[temp].nilai < -5) {
              cell.classList.add("text-danger");
              cell.innerHTML = data[temp].nilai
                ? `*${numberFormat(data[temp].nilai)}`
                : "";
            } else {
              cell.innerHTML = data[temp].nilai
                ? numberFormat(data[temp].nilai)
                : "";
            }
          }
        }

        console.log(typeof data[temp].nilai);
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

// ekspor excel
function exportData(fileType) {
  tableSelected = selectTable.options[selectTable.selectedIndex].textContent;
  putaran = selectPutaran.value;
  jenisPDRB = selectTable.value;
  kota = selectKota.value;
  var selectedPeriode = [];

  $('input[type="checkbox"]:checked').each(function () {
    selectedPeriode.push($(this).attr("name"));
  });

  switch (fileType) {
    case "excel":
      var url =
        "/tabelPDRB/exportExcel/" +
        tableSelected +
        "/" +
        jenisPDRB +
        "/" +
        kota +
        "/" +
        putaran +
        "/" +
        selectedPeriode;
      break;
    case "pdf":
      var url =
        "/tabelPDRB/exportPDF/" +
        tableSelected +
        "/" +
        jenisPDRB +
        "/" +
        kota +
        "/" +
        putaran +
        "/" +
        selectedPeriode;
      break;
    case "excelAllPutaran":
      var url =
        "/tabelPDRB/excelAllPutaran/" +
        tableSelected +
        "/" +
        jenisPDRB +
        "/" +
        kota +
        "/null" +
        "/" +
        selectedPeriode;
      break;
  }

  window.location.href = url;
}

// ekspor pdf
if (eksporButton != null) {
  eksporButton.addEventListener("click", function () {
    let komponenIndex = [0, 8, 9, 10, 13, 14, 15, 16, 17];

    let doc = new jspdf.jsPDF();
    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.text(judulTable.textContent, 14, 10);
    doc.autoTable({
      html: "#PDRBTable",
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

  // Loop untuk membuat elemen-elemen option
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

function generateDropdownTabelArahRevisi() {
  const dropdownContainer = document.getElementById("dropdownTableArahRevisi");

  // generate dropdown
  const select = document.createElement("select");
  select.classList.add("form-control");
  select.style = "width: 100%; max-width: 600px";
  select.id = "selectTable";

  var options = [
    { value: "Pilih Jenis Tabel", text: "Pilih Jenis Tabel" },
    {
      // id 1
      value: "PDRB-ADHB",
      text: "Tabel 301. PDRB ADHB Menurut Pengeluaran (Juta Rupiah)",
    },
    {
      // id 2
      value: "PDRB-ADHK",
      text: "Tabel 302. PDRB ADHK Menurut Pengeluaran (Juta Rupiah)",
    },
    {
      // id 3
      value: "Pertumbuhan-Y-ON-Y",
      text: "Tabel 303. Pertumbuhan PDRB (Y-ON-Y)",
    },
    {
      // id 4
      value: "Pertumbuhan-Q-TO-Q",
      text: "Tabel 304. Pertumbuhan PDRB (Q-TO-Q)",
    },
    {
      // id 5
      value: "Pertumbuhan-C-TO-C",
      text: "Tabel 305. Pertumbuhan PDRB (C-TO-C)",
    },
    {
      // id 6
      value: "indeks-implisit",
      text: "Tabel 306. Indeks Implisit",
    },
    {
      // id 7
      value: "pertumbuhan-indeks-implisit-Y-ON-Y",
      text: "Tabel 307. Pertumbuhan Indeks Implisit (Y-ON-Y)",
    },
    {
      // id 8
      value: "pertumbuhan-indeks-implisit-Q-TO-Q",
      text: "Tabel 308. Pertumbuhan Indeks Implisit (Q-TO-Q)",
    },
    {
      // id 9
      value: "sumber-pertumbuhan-Y-ON-Y",
      text: "Tabel 309. Sumber Pertumbuhan (Y-ON-Y)",
    },
    {
      // id 10
      value: "sumber-pertumbuhan-Q-TO-Q",
      text: "Tabel 310. Sumber Pertumbuhan (Q-TO-Q)",
    },
    {
      // id 11
      value: "sumber-pertumbuhan-C-TO-C",
      text: "Tabel 311. Sumber Pertumbuhan (C-TO-C)",
    },
  ];

  // Loop untuk membuat elemen-elemen option
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

if (document.title == "Rupiah | Upload Data") {
  window.addEventListener("load", function () {
    loadData();
  });
}

if (document.title == "Rupiah | Tabel Per Kota") {
  window.addEventListener("load", function () {
    loadData();
  });
}

if (document.title == "Rupiah | Tabel Ringkasan") {
  window.addEventListener("load", function () {
    loadData();
  });
}

if (document.title == "Rupiah | Arah Revisi") {
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
