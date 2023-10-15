// Filter Line Chart
const BerandaPeriode = document.getElementById("periode-beranda");
const jenisperhitungan = document.getElementById("Jenis");
const Komponen = document.getElementById("Grafik");

function loadDataLine() {
  rumus = jenisperhitungan.options[jenisperhitungan.selectedIndex].id;
  jenisKomponen = Komponen.options[Komponen.selectedIndex].id;

  var selectedPeriode = [];
  if (BerandaPeriode) {
    $('.checkboxes-periode input[type="checkbox"]:checked').each(function () {
      selectedPeriode.push($(this).attr("name"));
    });
  }
  KirimDataLine(rumus, selectedPeriode, jenisKomponen);
}

// MODAL PILIH PERIODE (BERANDA, UPLOAD ANGKA PDRB, DAN TABEL-TABEL)
let quarters = ["Q1", "Q2", "Q3", "Q4"];
const today = new Date();
const currentYear = today.getFullYear();
const currentMonth = today.getMonth() + 1;

// Menambahkan fungsi yg telah dibuat pada halaman agar bisa dipakai,...
if (document.getElementById("checkboxes-container") != null) {
  generateCheckboxes();
  generateTahunDropdown();
}

if (document.getElementById("checkboxes-container-year") != null) {
  generateCheckboxes();
  generateTahunDropdown();
}

if (
  document.getElementById("checkboxes-container-current-year-min2kuartal") !=
  null
) {
  generateTahunDropdownCurrentYear();
  generateCheckboxesCurrentYearMin2Kuartal();
}

if (document.getElementById("checkboxes-container-3-years") != null) {
  generateCheckboxes3Year();
}
window.addEventListener("load", checkboxQuarter);
window.addEventListener("load", checkboxYear);
window.addEventListener("load", checkboxOnlyYear);
// ... sampe sini.

// Fungsi generate checkbox untuk tiap tahun dan kuartal,...
function generateCheckboxes() {
  const currentQuarter = Math.floor((new Date().getMonth() + 3) / 3);
  const checkboxesContainer = document.getElementById("checkboxes-container");
  const checkboxesContainerYear = document.getElementById(
    "checkboxes-container-year"
  );

  // Generate checkboxes for each year and quarter
  for (let year = currentYear; year >= 2010; year--) {
    var i = 1;
    if (!(checkboxesContainerYear == null)) {
      quarters.push(year);
    }
    const row = document.createElement("div");
    row.classList.add("row");

    quarters.forEach((quarter) => {
      const col = document.createElement("div");
      col.classList.add("col");
      col.classList.add("form-check", "form-check-inline");

      const checkboxLabel = document.createElement("label");
      checkboxLabel.classList.add("form-check-label");

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.classList.add("form-check-input");

      if (isNaN(quarter)) {
        checkboxLabel.textContent = `${year}${quarter}`;
        checkboxLabel.setAttribute("for", `checkbox${year}${quarter}`);

        checkbox.name = `${year}${quarter}`;
        checkbox.id = `checkbox${year}${quarter}`;
        checkbox.value = `option${year}${quarter}`;

        if (
          document.title == "Rupiah | Tabel Ringkasan" ||
          document.title == "Rupiah | Tabel Per Kota"
        ) {
          if (year == currentYear) {
            if (i == currentQuarter - 1) {
              checkbox.checked = true;
            }
          }
        }
        if (document.title == "Beranda") {
          if (year > currentYear - 3) {
            checkbox.checked = true;
          }
        }
      } else {
        checkboxLabel.textContent = `${quarter}`;
        checkboxLabel.setAttribute("for", `checkbox${quarter}`);

        checkbox.name = `${quarter}`;
        checkbox.id = `checkbox${quarter}`;
        checkbox.value = `option${quarter}`;
      }

      col.appendChild(checkbox);
      col.appendChild(checkboxLabel);
      row.appendChild(col);

      i++;
    });

    if (currentQuarter == 1) {
      var Q4yearBefore = document.getElementById(`checkbox${currentYear - 1}4`);
      var yearBefore = document.getElementById(`checkbox${currentYear - 1}`);
      if (Q4yearBefore && yearBefore) {
        Q4yearBefore.checked = true;
        yearBefore.checked = true;
      }
    }

    if (checkboxesContainerYear == null) {
      checkboxesContainer.appendChild(row);
    } else {
      checkboxesContainerYear.appendChild(row);
      quarters.pop();
    }
  }
}

function generateCheckboxesCurrentYearMin2Kuartal() {
  const year = currentYear;
  popCount = 4 - Math.ceil(currentMonth / 3) + 2;
  quarters.splice(-popCount, popCount);
  const checkboxesContainerCurrentYear = document.getElementById(
    "checkboxes-container-current-year-min2kuartal"
  );

  if (quarters.length == 4) {
    quarters.push(year);
  }

  const row = document.createElement("div");
  row.classList.add("row");

  quarters.forEach((quarter) => {
    const col = document.createElement("div");
    col.classList.add("col");
    col.classList.add("form-check", "form-check-inline");

    const checkboxLabel = document.createElement("label");
    checkboxLabel.classList.add("form-check-label");

    const checkbox = document.createElement("input");
    checkbox.type = "checkbox";
    checkbox.checked = true;
    checkbox.set;
    checkbox.classList.add("form-check-input");

    checkbox;

    if (isNaN(quarter)) {
      checkboxLabel.textContent = `${year}${quarter}`;
      checkboxLabel.setAttribute("for", `checkbox${year}${quarter}`);

      checkbox.name = `${year}${quarter}`;
      checkbox.id = `checkbox${year}${quarter}`;
      checkbox.value = `option${year}${quarter}`;
      if (document.title == "Rupiah | Tabel Ringkasan") {
        if (year == currentYear) {
          if (i < currentQuarter) {
            checkbox.checked = true;
          }
        }
      }
    } else {
      checkboxLabel.textContent = `${quarter}`;
      checkboxLabel.setAttribute("for", `checkbox${quarter}`);

      checkbox.name = `${quarter}`;
      checkbox.id = `checkbox${quarter}`;
      checkbox.value = `option${quarter}`;
      // checkbox.checked = true;
    }

    col.appendChild(checkbox);
    col.appendChild(checkboxLabel);
    row.appendChild(col);
  });

  checkboxesContainerCurrentYear.appendChild(row);
}

function generateCheckboxes3Year() {
  const currentQuarter = Math.floor((new Date().getMonth() + 3) / 3);
  const checkboxesContainer = document.getElementById(
    "checkboxes-container-3-years"
  );

  // Generate checkboxes for each year and quarter
  for (let year = currentYear - 2; year <= currentYear; year++) {
    if (year == currentYear) {
      popCount = 4 - Math.ceil(currentMonth / 3) + 1;
      quarters.splice(-popCount, popCount);
    }
    if (quarters.length == 4) {
      quarters.push(year);
    }
    const row = document.createElement("div");
    row.classList.add("row");

    quarters.forEach((quarter) => {
      const col = document.createElement("div");
      col.classList.add("col");
      col.classList.add("form-check", "form-check-inline");

      const checkboxLabel = document.createElement("label");
      checkboxLabel.classList.add("form-check-label");

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.classList.add("form-check-input");

      if (isNaN(quarter)) {
        checkboxLabel.textContent = `${year}${quarter}`;
        checkboxLabel.setAttribute("for", `checkbox${year}${quarter}`);

        checkbox.name = `${year}${quarter}`;
        checkbox.id = `checkbox${year}${quarter}`;
        checkbox.value = `option${year}${quarter}`;
      } else {
        checkboxLabel.textContent = `${quarter}`;
        checkboxLabel.setAttribute("for", `checkbox${quarter}`);

        checkbox.name = `${quarter}`;
        checkbox.id = `checkbox${quarter}`;
        checkbox.value = `option${quarter}`;
      }

      col.appendChild(checkbox);
      col.appendChild(checkboxLabel);
      row.appendChild(col);
    });

    checkboxesContainer.appendChild(row);
    quarters.pop();
  }

  // default checked
  let checkedBoxes = [];
  switch (currentQuarter) {
    case 1:
      for (let i = 1; i <= 2; i++) {
        for (let j = 1; j <= 4; j++) {
          checkedBoxes.push(`checkbox${currentYear - i}Q${j}`);
          checkedBoxes.push(`checkbox${currentYear - i}`);
        }
      }
      break;
    case 2:
      checkedBoxes = [`checkbox${currentYear}Q1`];
      break;
    case 3:
      checkedBoxes = [`checkbox${currentYear}Q1`, `checkbox${currentYear}Q2`];
      break;
    case 4:
      checkedBoxes = [
        `checkbox${currentYear}Q1`,
        `checkbox${currentYear}Q2`,
        `checkbox${currentYear}Q3`,
      ];
      break;
  }
  checkedBoxes.forEach((checkedBox) => {
    document.getElementById(checkedBox).checked = true;
  });
}

function generateCheckboxesYearOnly() {
  const checkboxesContainerYearOnly = document.getElementById(
    "checkboxes-container-year-only"
  );

  // Generate checkboxes for each year and quarter
  const row = document.createElement("div");
  row.classList.add("row");
  for (let year = currentYear; year >= 2010; year--) {
    var i = 1;
    const col = document.createElement("div");
    col.classList.add("col");
    col.classList.add("form-check", "form-check-inline");

    const checkboxLabel = document.createElement("label");
    checkboxLabel.classList.add("form-check-label");

    const checkbox = document.createElement("input");
    checkbox.type = "checkbox";
    checkbox.classList.add("form-check-input");

    checkboxLabel.textContent = `${year}`;
    checkboxLabel.setAttribute("for", `checkbox${year}`);

    checkbox.name = `${year}`;
    checkbox.id = `checkbox${year}`;
    checkbox.value = `option${year}`;

    if (document.title == "Beranda") {
      if (year > currentYear - 5) {
        checkbox.checked = true;
      }
    }

    col.appendChild(checkbox);
    col.appendChild(checkboxLabel);
    row.appendChild(col);
    checkboxesContainerYearOnly.appendChild(row);
  }
}
// ...sampe sini.

// Fungsi generate menu dropdown untuk tiap tahun,...
function generateTahunDropdown() {
  const tahunDropdown = document.getElementById("tahunDropdown");

  for (let year = currentYear; year >= 2010; year--) {
    const dropdownItemList = document.createElement("li");

    const dropdownItem = document.createElement("a");
    dropdownItem.classList.add("dropdown-item");
    dropdownItem.textContent = year;
    dropdownItem.href = "#";
    dropdownItem.id = `all${year}`;

    dropdownItemList.appendChild(dropdownItem);
    tahunDropdown.appendChild(dropdownItemList);
  }
}

function generateTahunDropdownCurrentYear() {
  const tahunDropdownCurrentYear = document.getElementById(
    "tahunDropdownCurrentYear"
  );

  year = currentYear;
  const dropdownItemList = document.createElement("li");

  const dropdownItem = document.createElement("a");
  dropdownItem.classList.add("dropdown-item");
  dropdownItem.textContent = year;
  dropdownItem.href = "#";
  dropdownItem.id = `all${year}`;

  dropdownItemList.appendChild(dropdownItem);

  if (!(tahunDropdownCurrentYear == null)) {
    tahunDropdownCurrentYear.appendChild(dropdownItemList);
  }
}
// ..., sampe sini.

// Fungsi untuk centang checkbox (semua, per tahun, per kuartal, hapus semua centang),...
function checkboxSemua() {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach((checkbox) => {
    checkbox.checked = true;
  });
}

function checkboxQuarter() {
  quarters.forEach((quarter) => {
    const allQuarter = document.getElementById(`all${quarter}`);

    function checkboxKuartal(event) {
      event.preventDefault();

      const quarter = this.textContent.slice(-2);
      const checkboxes = document.querySelectorAll('input[type="checkbox"]');

      checkboxes.forEach((checkbox) => {
        checkbox.checked = false;

        const checkboxId = checkbox.id;
        const lastTwoChars = checkboxId.slice(-2);

        if (lastTwoChars === quarter) {
          checkbox.checked = true;
        }
      });
    }

    if (!(allQuarter == null)) {
      allQuarter.addEventListener("click", checkboxKuartal);
    }
  });
}

function checkboxYear() {
  for (let year = new Date().getFullYear(); year >= 2010; year--) {
    const allYear = document.getElementById(`all${year}`);

    function checkboxTahun(event) {
      event.preventDefault();

      const year = this.textContent;
      const checkboxes = document.querySelectorAll('input[type="checkbox"]');

      checkboxes.forEach((checkbox) => {
        checkbox.checked = false;

        const checkboxId = checkbox.id;
        const lastFourChars = checkboxId.slice(8, 12);

        if (lastFourChars === year) {
          checkbox.checked = true;
        }
      });
    }

    if (!(allYear == null)) {
      allYear.addEventListener("click", checkboxTahun);
    }
  }
}

function checkboxOnlyYear() {
  const onlyYears = document.getElementById(`onlyYears`);

  function checkboxTahunSaja(event) {
    event.preventDefault();

    const checkboxes = document.querySelectorAll('input[type="checkbox"]');

    checkboxes.forEach((checkbox) => {
      checkbox.checked = false;

      const checkboxId = checkbox.id;
      const lastFourChars = checkboxId.slice(-4);

      const yearPattern = /^\d{4}$/;

      if (yearPattern.test(lastFourChars)) {
        checkbox.checked = true;
      }
    });
  }
  if (!(onlyYears == null)) {
    onlyYears.addEventListener("click", checkboxTahunSaja);
  }
}

function clearCheckbox() {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach((checkbox) => {
    checkbox.checked = false;
  });
}
// ..., sampe sini.

// CHARTS (BERANDA)
function TerimaDataBar() {
  $.ajax({
    type: "GET",
    url: "/beranda/ShowBarChart",
    dataType: "json",
    success: function (data) {
      let dataFloat = [];
      dataArray = Object.values(data);
      for (const element of dataArray) {
        dataFloat.push(element.map((str) => parseFloat(str).toFixed(2)));
      }
      renderBarChart(dataFloat[0], dataFloat[1], dataFloat[2]);
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error);
    },
  });
}

function renderBarChart(dataY, dataQ, dataC) {
  // Bar Charts
  var barChartYOY = $("#barChartYOY").get(0).getContext("2d");
  var barChartQTQ = $("#barChartQTQ").get(0).getContext("2d");
  var barChartCTC = $("#barChartCTC").get(0).getContext("2d");

  var komponen = ["PKRT", "PK-LNPRT", "PKP", "PMTB", "Ekspor", "Impor"];

  var dataYOY = {
    labels: komponen,
    datasets: [
      {
        backgroundColor: "#dc3545",
        borderColor: "#dc3545",
        data: dataY,
      },
    ],
  };

  var dataQTQ = {
    labels: komponen,
    datasets: [
      {
        backgroundColor: "#007bff",
        borderColor: "#007bff",
        data: dataQ,
      },
    ],
  };

  var dataCTC = {
    labels: komponen,
    datasets: [
      {
        backgroundColor: "#28a745",
        borderColor: "#28a745",
        data: dataC,
      },
    ],
  };

  var barChartOptions = {
    responsive: false,
    maintainAspectRatio: false,
    datasetFill: false,
    scales: {
      xAxes: [
        {
          gridLines: {
            display: false,
          },
        },
      ],
      yAxes: [
        {
          gridLines: {
            display: true,
          },
          ticks: {
            display: false,
          },
        },
      ],
    },
    legend: {
      display: false,
    },
    plugins: {
      datalabels: {
        align: "end",
        anchor: "end",
        font: {
          size: 12,
        },
        color: "black",
        formatter: (value) => value,
        display: "auto",
      },
    },
  };

  // Function to set the maximum y-axis tick value (so any bar values won't get clipped)
  function maxTickValue(data) {
    return Math.round(Math.round(Math.max(...data)) / 5) * 5 + 10;
  }

  barChartOptions.scales.yAxes[0].ticks.max = maxTickValue(
    dataYOY.datasets[0].data
  );
  new Chart(barChartYOY, {
    type: "bar",
    data: dataYOY,
    options: barChartOptions,
  });

  barChartOptions.scales.yAxes[0].ticks.max = maxTickValue(
    dataQTQ.datasets[0].data
  );
  new Chart(barChartQTQ, {
    type: "bar",
    data: dataQTQ,
    options: barChartOptions,
  });

  barChartOptions.scales.yAxes[0].ticks.max = maxTickValue(
    dataCTC.datasets[0].data
  );
  new Chart(barChartCTC, {
    type: "bar",
    data: dataCTC,
    options: barChartOptions,
  });
}

function KirimDataLine(rumus, selectedPeriode, jenisKomponen) {
  $.ajax({
    type: "POST",
    url: "/beranda/ShowLineChart",
    data: {
      jenisTable: rumus,
      periode: selectedPeriode,
      jenisKomponen: jenisKomponen,
    },
    dataType: "json",
    success: function (data) {
      dataArray = Object.values(data);
      renderLineChart(dataArray[0], dataArray[1]);
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error);
    },
  });
}

function renderLineChart(datalabels, dataLine) {
  // Line Chart
  $("#lineChart").remove();
  $("#graph-container").append(
    '<canvas id="lineChart" style="height: 281px; width: 649px;"><canvas>'
  );
  var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
  var ChartData = {
    labels: datalabels,
    datasets: [
      {
        fill: false,
        backgroundColor: "rgb(255,0,0)",
        borderColor: "rgb(255,0,0)",
        pointStyle: "circle",
        pointRadius: 5,
        pointHoverRadius: 10,
        data: dataLine,
      },
    ],
  };
  var ChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    scales: {
      yAxes: [
        {
          gridLines: {
            display: true,
          },
          ticks: {
            display: true,
            padding: 20,
          },
        },
      ],
    },
    plugins: {
      datalabels: {
        anchor: "end",
        align: "top",
        font: {
          weight: "bold",
          size: 13,
        },
      },
    },
    legend: {
      display: false,
    },
    labels: {
      usePointStyle: true,
    },
  };
  function maxTickValue(data) {
    return Math.round(Math.round(Math.max(...data)) / 5) * 5 + 10;
  }
  ChartOptions.scales.yAxes[0].ticks.max = maxTickValue(
    ChartData.datasets[0].data
  );
  new Chart(lineChartCanvas, {
    type: "line",
    data: ChartData,
    options: ChartOptions,
  });
}

// DROPDOWN TAHUNAN/TRIWULANAN (BERANDA)
$(document).ready(function () {
  var optarray = $("#Jenis")
    .children("option")
    .map(function () {
      return {
        value: this.value,
        option:
          "<option id='" +
          this.id +
          "' value='" +
          this.value +
          "'>" +
          this.text +
          "</option>",
      };
    });
  $("#Jangka")
    .change(function () {
      $("#Jenis").children("option").remove();
      var addoptarr = [];
      for (i = 0; i < optarray.length; i++) {
        if (optarray[i].value.indexOf($(this).val()) > -1) {
          addoptarr.push(optarray[i].option);
        }
      }
      $("#Jenis").html(addoptarr.join(""));

      let checkboxContainer;
      if (document.getElementById("checkboxes-container")) {
        checkboxContainer = document.getElementById("checkboxes-container");
        checkboxContainer.innerHTML = "";
        checkboxContainer.id = "checkboxes-container-year-only";
        generateCheckboxesYearOnly();
        generateTahunDropdown();
      } else if (document.getElementById("checkboxes-container-year-only")) {
        checkboxContainer = document.getElementById(
          "checkboxes-container-year-only"
        );
        checkboxContainer.innerHTML = "";
        checkboxContainer.id = "checkboxes-container";
        generateCheckboxes();
        generateTahunDropdown();
      }
      loadDataLine();
    })
    .change();
});

// Berubah Tiap Perubahan Pilihan (BERANDA)
if (document.getElementById("Grafik") != null) {
  document.getElementById("Grafik").addEventListener("change", function () {
    loadDataLine();
  });
}
if (document.getElementById("Jenis") != null) {
  document.getElementById("Jenis").addEventListener("change", function () {
    loadDataLine();
  });
}

// MENAMPILKAN NAMA FILE YANG SUDAH TERPILIH (UPLOAD ANGKA PDRB)
$("#inputFile").change(function () {
  fileLabel = document.getElementById("inputFileLabel");
  if (this.files.length > 0) {
    fileLabel.textContent = this.files[0].name;
  } else {
    fileLabel.textContent = "Pilih file";
  }
});
