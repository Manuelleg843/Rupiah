// MODAL PILIH PERIODE
let quarters = ["Q1", "Q2", "Q3", "Q4"];
const today = new Date();
const currentYear = today.getFullYear();
const currentMonth = today.getMonth() + 1;

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
    checkbox.set
    checkbox.classList.add("form-check-input");

    if (isNaN(quarter)) {
      checkboxLabel.textContent = `${year}${quarter}`;
      checkboxLabel.setAttribute("for", `checkbox${year}${quarter}`);

      checkbox.name = `${year}${quarter}`;
      checkbox.id = `checkbox${year}${quarter}`;
      checkbox.className = 'checkbox-periode';
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

if (document.getElementById("checkboxes-container-3-years") != null) {
  generateCheckboxes3Year();
}
window.addEventListener("load", checkboxQuarter);
window.addEventListener("load", checkboxYear);
window.addEventListener("load", checkboxOnlyYear);
// ..., sampe sini.

// CHARTS
$(function () {
  // ChartJS
  Chart.plugins.register(ChartDataLabels);

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
        data: [5.26, 9.53, 6.06, 4.22, 6.18, -2.26],
      },
    ],
  };

  var dataQTQ = {
    labels: komponen,
    datasets: [
      {
        backgroundColor: "#007bff",
        borderColor: "#007bff",
        data: [3.68, 8.97, 40.71, 0.44, -4.65, -4.35],
      },
    ],
  };

  var dataCTC = {
    labels: komponen,
    datasets: [
      {
        backgroundColor: "#28a745",
        borderColor: "#28a745",
        data: [4.73, 8.77, 4.28, 2.75, 11.95, 2.1],
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

  // PDRB
  var PDRBtahunan = [
    [2011, 6.73],
    [2012, 6.53],
    [2013, 6.07],
    [2014, 5.91],
    [2015, 5.91],
    [2016, 5.87],
    [2017, 6.2],
    [2018, 6.11],
    [2019, 5.82],
    [2020, -2.39],
    [2021, 3.56],
    [2022, 5.25],
    [2023, null],
  ];
  var PDRBctoc = [
    [0, 0.94],
    [1, 4.1],
    [2, 3.53],
    [3, 3.56],
    [4, 4.61],
    [5, 5.11],
    [6, 5.39],
    [7, 5.25],
    [8, 4.95],
    [9, 5.04],
    [10, null],
    [11, null],
  ];
  var PDRBqtoq = [
    [0, -0.32],
    [1, 0.12],
    [2, 0.09],
    [3, 3.75],
    [4, 0.62],
    [5, 1.07],
    [6, 0.39],
    [7, 2.69],
    [8, 0.72],
    [9, 1.25],
    [10, null],
    [11, null],
  ];
  var PDRByony = [
    [0, -1.94],
    [1, 10.92],
    [2, 2.42],
    [3, 3.63],
    [4, 4.61],
    [5, 5.61],
    [6, 5.93],
    [7, 4.85],
    [8, 4.95],
    [9, 5.13],
    [10, null],
    [11, null],
  ];

  // PKRT
  var PKRTtahunan = [
    [2011, 6.82],
    [2012, 6.22],
    [2013, 5.78],
    [2014, 5.54],
    [2015, 5.31],
    [2016, 5.54],
    [2017, 5.68],
    [2018, 6.03],
    [2019, 6.05],
    [2020, -2.23],
    [2021, 3.54],
    [2022, 5.64],
    [2023, null],
  ];
  var PKRTctoc = [
    [0, -1.58],
    [1, 3.13],
    [2, 3.52],
    [3, 3.54],
    [4, 4.09],
    [5, 4.61],
    [6, 5.6],
    [7, 5.64],
    [8, 4.18],
    [9, 4.73],
    [10, null],
    [11, null],
  ];
  var PKRTqtoq = [
    [0, 1.06],
    [1, 1.6],
    [2, -2.48],
    [3, 3.44],
    [4, 1.56],
    [5, 2.61],
    [6, -0.16],
    [7, 1.63],
    [8, 0.06],
    [9, 3.68],
    [10, null],
    [11, null],
  ];
  var PKRTyony = [
    [0, -1.58],
    [1, 8.22],
    [2, 4.34],
    [3, 3.58],
    [4, 4.09],
    [5, 5.12],
    [6, 7.62],
    [7, 5.74],
    [8, 4.18],
    [9, 5.26],
    [10, null],
    [11, null],
  ];

  var line_data = {
    data: PDRBtahunan,
    color: "#FF0000",
  };

  var p = $.plot("#line-chart", [line_data], {
    grid: {
      hoverable: true,
      borderColor: "#f3f3f3",
      borderWidth: 1,
      tickColor: "#f3f3f3",
    },
    series: {
      shadowSize: 0,
      lines: {
        show: true,
      },
      points: {
        show: true,
      },
    },
    lines: {
      fill: false,
      color: ["#3c8dbc", "#f56954"],
    },
    yaxis: {
      show: true,
    },
    xaxis: {
      show: true,
      tickFormatter: function (val) {
        return "Tahun " + val;
      },
    },
  });

  // Initialize tooltip on hover
  $('<div class="tooltip-inner" id="line-chart-tooltip"></div>')
    .css({
      position: "absolute",
      display: "none",
      opacity: 0.6,
    })
    .appendTo("body");
  $("#line-chart").bind("plothover", function (event, pos, item) {
    if (item) {
      var x = item.datapoint[0].toFixed(0),
        y = item.datapoint[1].toFixed(2);
      $("#line-chart-tooltip")
        .html("Tahun " + x + "<br>" + y)
        .css({
          top: item.pageY + 5,
          left: item.pageX + 5,
        })
        .fadeIn(200);
    } else {
      $("#line-chart-tooltip").hide();
    }
  });
});

// DROPDOWN SELECT
$(function () {
  $(".select2").select2();
});

// DROPDOWN TAHUNAN/TRIWULANAN pada Beranda
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

// Toast untuk validasi upload
// $(".toastsDefaultWarning").click(function () {
//   $(document).Toasts("create", {
//     class: "bg-warning toast-warning-validasi",
//     fixed: false,
//     title: "Perhatian!",
//     body: "<b>{ADHB.2023Q1}</b> Komponen (1) tidak sesuai dengan sub-komponennyaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.\
//     <br>{ADHK.2023Q2} Komponen (2) tidak sesuai dengan sub-komponennya.\
//     <br>{ADHK.2023Q3} Komponen (4) tidak sesuai dengan sub-komponennya.\
//     <br>{ADHK.2022Q3} Komponen (5) tidak sesuai dengan sub-komponennya.\
//     <br>{ADHK.2022Q3} Komponen (6) tidak sesuai dengan sub-komponennya.\
//     <br>{ADHK.2022Q4} Komponen (7) tidak sesuai dengan sub-komponennya.\
//     <br>{ADHK.2021Q1} Komponen (3) tidak sesuai dengan sub-komponennya.\
//     <br>{ADHK.2021Q1} Komponen (4) tidak sesuai dengan sub-komponennya.\
//     <br>{ADHK.2021Q2} Komponen (6) tidak sesuai dengan sub-komponennya.",
//   });
// });

$("#inputFile").change(function () {
  fileLabel = document.getElementById("inputFileLabel");
  // Check if a file has been selected
  if (this.files.length > 0) {
    // Display the selected file name in the label
    fileLabel.textContent = this.files[0].name;
  } else {
    // No file selected, clear the label
    fileLabel.textContent = "Pilih file";
  }
});
