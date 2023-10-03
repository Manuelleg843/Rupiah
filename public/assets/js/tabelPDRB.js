const selectTable = document.getElementById("selectTable");
const selectKota = document.getElementById("selectKota");
const selectPutaran = document.getElementById("selectPutaran");
const judulTable = document.getElementById("judulTable");
const modalWilayah = document.getElementById("modalWilayah");
const judulTableADHB = document.getElementById("judulTableADHB");
const judulTableADHK = document.getElementById("judulTableADHK");
const submitPeriode = document.getElementById("simpan-periode");
const judulModal = document.getElementById("judulModal");
const kotaJudulModal = document.getElementById("kotaJudulModal");
const eksporButton = document.getElementById("export-button");
const currentQuarter = Math.floor((new Date().getMonth() + 3) / 3);
var tableSelected;
var kotaSelected;
var putaran;
var jenisPDRB;
var kota;

window.addEventListener("load", function () {
  loadData();
});

// munculin data on load
function loadData() {
  tableSelected = selectTable.options[selectTable.selectedIndex].textContent;
  kotaSelected = selectKota.options[selectKota.selectedIndex].textContent;
  jenisPDRB = selectTable.value;
  kota = selectKota.value;
  var selectedPeriode = [];

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
    judulTable.textContent = tableSelected + " - " + kotaSelected; // ganti judul tabel
  } else {
    putaran = selectPutaran.value;
    $('input[type="checkbox"]:checked').each(function () {
      // selectedPeriode[$(this).attr('name')] = $(this).name();
      selectedPeriode.push($(this).attr("name"));
    });
    judulTable.textContent =
      tableSelected + " - " + kotaSelected + " - Putaran " + putaran;
  }

  kirimData(jenisPDRB, kota, putaran, selectedPeriode);
}

function kirimData(jenisPDRB, kota, putaran, selectedPeriode) {
  if (document.title == "Rupiah | Upload Data") {
    link = "/uploadData/angkaPDRB/getData";
  } else {
    link = "/tabelPDRB/tabelHistoryPutaran/getData";
  }
  $.ajax({
    type: "POST",
    url: link,
    data: {
      jenisPDRB: jenisPDRB,
      kota: kota,
      putaran: putaran,
      periode: selectedPeriode,
    },
    dataType: "json",
    success: function (data) {
      renderTable(data["dataPDRB"], data["selectedPeriode"], data["komponen"]);
      // console.log("Sukses! Respons dari server:", data['dataPDRB']);
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error);
    },
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
// dropdown jenis tabel khusus halaman Tabel Ringkasan

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

// function exportData() {
//   putaran = selectPutaran.value;
//   jenisPDRB = selectTable.value;
//   kota = selectKota.value;
//   var selectedPeriode = [];

//   $('input[type="checkbox"]:checked').each(function () {
//     selectedPeriode.push($(this).attr("name"));
//   });

//   $.ajax({
//     type: "POST",
//     url: "/tabelPDRB/exportData",
//     data: {
//       judulTable: judulTable.textContent,
//       jenisPDRB: jenisPDRB,
//       kota: kota,
//       putaran: putaran,
//       periode: selectedPeriode,
//     },
//     dataType: "json",
//     success: function (data) {
//       // renderTable(data['dataPDRB'], data['selectedPeriode'], data['komponen']);
//       console.log("Sukses! Respons dari server:", data);
//     },
//     error: function (error) {
//       // Handle kesalahan jika ada
//       console.error("Terjadi kesalahan:", error.message);
//     },
//   });
// }

if (eksporButton != null) {
  eksporButton.addEventListener("click", function () {
    let table = document.getElementById("PDRBTable").outerHTML; // mengambil tabel yang sudah ter-generate
    let title = judulTable.outerHTML; // mengambil judul tabel
    console.log(table);

    $.ajax({
      type: "POST",
      url: "/uploadData/eksporPDF",
      data: { tableTitle: title, tableContent: table },
      xhrFields: {
        responseType: "blob", // to avoid binary data being mangled on charset conversion
      },
      success: function (blob, status, xhr) {
        var filename = "";
        var disposition = xhr.getResponseHeader("Content-Disposition");
        if (disposition && disposition.indexOf("attachment") !== -1) {
          var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
          var matches = filenameRegex.exec(disposition);
          if (matches != null && matches[1])
            filename = matches[1].replace(/['"]/g, "");
        }

        if (typeof window.navigator.msSaveBlob !== "undefined") {
          window.navigator.msSaveBlob(blob, filename); // IE workaround for "HTML7007:One or more blob URLs..."
        } else {
          var URL = window.URL || window.webkitURL;
          var downloadUrl = URL.createObjectURL(blob);

          if (filename) {
            var a = document.createElement("a");
            if (typeof a.download === "undefined") {
              window.location.href = downloadUrl;
            } else {
              a.href = downloadUrl;
              a.download = filename;
              document.body.appendChild(a);
              a.click();
            }
          } else {
            window.location.href = downloadUrl;
          }

          setTimeout(function () {
            URL.revokeObjectURL(downloadUrl);
          }, 100);
        }
      },
      error: function (error) {
        console.error("Terjadi kesalahan:", error);
      },
    });
  });
}

// dropdown kota
if (selectKota != null) {
  selectKota.addEventListener("change", function () {
    // mengganti judul dan isi tabel
    loadData();
  });
}

// Menampilkan judul modal sesuai wilayah yang terpilih
if (kotaJudulModal != null && judulModal != null) {
  kotaJudulModal.setAttribute("value", "3100");
  selectKota.addEventListener("change", function () {
    judulModal.textContent = "Upload PDRB - " + this.selectedOptions[0].text;
    kotaJudulModal.setAttribute("value", this.value);
  });
}
