// MODAL PILIH PERIODE
// Function to generate checkboxes for each year and quarter
function generateCheckboxes() {
  const today = new Date();
  const currentYear = today.getFullYear();
  const quarters = ["Q1", "Q2", "Q3", "Q4"];
  const checkboxesContainer = document.getElementById("checkboxes-container");

  // Generate checkboxes for each year and quarter
  for (let year = 2011; year <= currentYear; year++) {
    const row = document.createElement("div");
    row.classList.add("row");

    quarters.forEach((quarter) => {
      const col = document.createElement("div");
      col.classList.add("col");
      col.classList.add("form-check", "form-check-inline");

      const checkboxLabel = document.createElement("label");
      checkboxLabel.classList.add("form-check-label");
      checkboxLabel.textContent = `${year} ${quarter}`;
      checkboxLabel.setAttribute("for", `checkbox${year}${quarter}`);

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.classList.add("form-check-input");
      checkbox.name = `${year}-${quarter}`;
      checkbox.id = `checkbox${year}${quarter}`;
      checkbox.value = `option${year}${quarter}`;

      col.appendChild(checkbox);
      col.appendChild(checkboxLabel);
      row.appendChild(col);
    });

    checkboxesContainer.appendChild(row);
  }
}

// Function to generate dropdown submenu for each year
function generateTahunDropdown() {
  const today = new Date();
  const currentYear = today.getFullYear();
  const tahunDropdown = document.getElementById("tahunDropdown");

  for (let year = 2011; year <= currentYear; year++) {
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

// Function to check all checkboxes
function checkboxSemua() {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach((checkbox) => {
    checkbox.checked = true;
  });
}

// Check all checkboxes in a quarter...
const allQ1 = document.getElementById("allQ1");
const allQ2 = document.getElementById("allQ2");
const allQ3 = document.getElementById("allQ3");
const allQ4 = document.getElementById("allQ4");

function checkboxQuarter(event) {
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

allQ1.addEventListener("click", checkboxQuarter);
allQ2.addEventListener("click", checkboxQuarter);
allQ3.addEventListener("click", checkboxQuarter);
allQ4.addEventListener("click", checkboxQuarter);
// ...and it ends here

// Check all checkboxes in a year
function checkboxYear() {
  for (let year = 2011; year <= new Date().getFullYear(); year++) {
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

    allYear.addEventListener("click", checkboxTahun);
  }
}

window.addEventListener("load", generateCheckboxes);
window.addEventListener("load", generateTahunDropdown);
window.addEventListener("load", checkboxYear);

// CHARTS
$(function () {
  // ChartJS
  Chart.plugins.register(ChartDataLabels);

  // BAR CHARTS
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
        display: "auto", // Display data labels
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

  // FLOT
  /* LINE CHART */

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
    [0, -1.94],
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

  //Initialize tooltip on hover
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
  /* END LINE CHART */
});

// DROPDOWN SELECT
$(function () {
  $(".select2").select2();
});

$(document).ready(function() {
    var optarray = $("#Jenis").children('option').map(function() {
      return {
        "value": this.value,
        "option": "<option value='" + this.value + "'>" + this.text + "</option>"
      }
    })
    $("#Jangka").change(function() {
      $("#Jenis").children('option').remove();
      var addoptarr = [];
      for (i = 0; i < optarray.length; i++) {
        if (optarray[i].value.indexOf($(this).val()) > -1) {
          addoptarr.push(optarray[i].option);
        }
      }
      $("#Jenis").html(addoptarr.join(''))
    }).change();
  })