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

if (document.getElementById("checkboxes-container-current-year") != null) {
  generateCheckboxesCurrentYear();
  generateTahunDropdownCurrentYear();
}

if (
  document.getElementById("checkboxes-container-current-year-min2kuartal") !=
  null
) {
  generateCheckboxesCurrentYearMin2Kuartal();
  generateTahunDropdownCurrentYear();
}

if (document.getElementById("checkboxes-container-3-years") != null) {
  generateCheckboxes3Year();
}
window.addEventListener("load", checkboxQuarter);
window.addEventListener("load", checkboxYear);
window.addEventListener("load", checkboxOnlyYear);
// ..., sampe sini.

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

        if (document.title == "Rupiah | Tabel Ringkasan") {
          if (year == currentYear) {
            if (i == currentQuarter - 1) {
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

function generateCheckboxesCurrentYear() {
  const year = currentYear;
  popCount = 4 - Math.ceil(currentMonth / 3) + 1;
  quarters.splice(-popCount, popCount);
  const checkboxesContainerCurrentYear = document.getElementById(
    "checkboxes-container-current-year"
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
      checkbox.className = "checkbox-periode";
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
      checkbox.className = "checkbox-periode";
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
function TerimaData() {
  $.ajax({
    type: "GET",
    url: "/beranda/ShowChart",
    dataType: "json",
    success: function (data) {
      let dataFloat = [];
      dataArray = Object.values(data);
      for (const element of dataArray) {
        dataFloat.push(element.map((str) => parseFloat(str).toFixed(2)));
      }
      console.log(dataFloat);
      renderChart(dataFloat[0], dataFloat[1], dataFloat[2]);
    },
    error: function (error) {
      // Handle kesalahan jika ada
      console.error("Terjadi kesalahan:", error);
    },
  });
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

function renderChart(dataY, dataQ, dataC) {
  // ChartJS
  // Chart.plugins.register(ChartDataLabels);

  console.log("yoooo");
  console.log(dataY);

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

  // Flot
  // Line Chart
  var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
  var ChartData = {
    labels: [
      2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022,
    ],
    datasets: [
      {
        fill: false,
        backgroundColor: "rgb(255,0,0)",
        borderColor: "rgb(255,0,0)",
        pointStyle: "circle",
        pointRadius: 5,
        pointHoverRadius: 10,
        data: [
          6.73,
          6.53,
          6.07,
          5.91,
          5.91,
          5.87,
          6.2,
          6.11,
          5.82,
          -2.39,
          3.56,
          5.25,
          null,
        ],
      },
    ],
  };
  var ChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    plugins: {
      datalabels: {
        anchor: "end",
        align: "bottom",
        // formatter: Math.round.toFixed(2),
        font: {
          weight: "bold",
          size: 10,
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
  var lineChart = new Chart(lineChartCanvas, {
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
        option: "<option value='" + this.value + "'>" + this.text + "</option>",
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
    })
    .change();
});

// MENAMPILKAN NAMA FILE YANG SUDAH TERPILIH (UPLOAD ANGKA PDRB)
$("#inputFile").change(function () {
  fileLabel = document.getElementById("inputFileLabel");
  if (this.files.length > 0) {
    fileLabel.textContent = this.files[0].name;
  } else {
    fileLabel.textContent = "Pilih file";
  }
});
