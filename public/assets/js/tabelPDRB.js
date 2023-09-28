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
  console.log(selectedPeriode);
  // mengganti judul tabel
  judulTable.textContent = tableSelected + " - " + kotaSelected + " - Putaran " + putaran;

  kirimData(jenisPDRB, kota, putaran, selectedPeriode);

}

function kirimData(jenisPDRB, kota, putaran, selectedPeriode) {
  //  sampe sini tan: lo mau coba dari sini di get mana aja yang checked; Visible ? kirim ke model buat di get : yaudah

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
      // renderTable(data);
      console.log("Sukses! Respons dari server:", data);
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error);
    }
  });
}

// render table
function renderTable(data) {
  var table = $('#PDRBTable tbody');

  // delete table if there is content inside it 
  table.empty();

  // loop through json to create rows
  for (var i = 0; i < data.length; i++) {
    var rowData = data[i];
    var id_komponen = rowData.id_komponen;

    // styling table
    var row = '<tr';
    if (id_komponen == 1 || id_komponen == 2 || id_komponen == 3 || id_komponen == 4 || id_komponen == 5 || id_komponen == 6 || id_komponen == 7 || id_komponen == 8 || id_komponen == 9) {
      row = row + " style='font-weight: bold;'>"
    } else {
      row = row + ">"
    }

    row = row + '<td colspan="2"';

    if (id_komponen != 1 && id_komponen != 2 && id_komponen != 3 && id_komponen != 4 && id_komponen != 5 && id_komponen != 6 && id_komponen != 7 && id_komponen != 8 && id_komponen != 9) {
      row = row + "class='pl-5'>"
    } else {
      row = row + ">"
    }

    if (id_komponen == 9) {
      row = row + rowData.komponen
    } else {
      row = row + id_komponen + ". " + rowData.komponen;
    }

    row = row + '</td>' +
      '<td style="text-align: right;">' + numberFormat(rowData.nilai) + '</td>' +
      '</tr>';
    table.append(row);
  }

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
// show or hide column 
function toggleColumns() {
  $('.checkbox-periode').each(function () {
    var columnPeriode = $(this).attr('name');
    var isVisible = $(this).is(':checked');

    if (isVisible) {
      $('#PDRBTable th.' + columnPeriode + ', #PDRBTable td.' + columnPeriode).show();
    } else {
      $('#PDRBTable th.' + columnPeriode + ', #PDRBTable td.' + columnPeriode).hide();
    }
  })
}

