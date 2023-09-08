var selectTable   = document.getElementById("selectTable");
var selectKota    = document.getElementById("selectKota");
var selectPutaran = document.getElementById("selectPutaran");
var judulTable    = document.getElementById("judulTable");
var modalWilayah  = document.getElementById("modalWilayah");

// dropdown jenis tabel
selectTable.addEventListener("change", function (){
    var tableSelected = selectTable.value;
    var kotaSelected  = selectKota.value;

    // mengganti judul tabel
    judulTable.textContent = tableSelected + " - " + kotaSelected;
    
});


// dropdown kota
selectKota.addEventListener("change", function (){
    var tableSelected = selectTable.value;
    var kotaSelected  = selectKota.value;

    // mengganti judul tabel
    judulTable.textContent = tableSelected + " - " + kotaSelected;
    
});

// dropdown putaran
selectPutaran.addEventListener("change", function (){
    var tableSelected = selectTable.value; 
    var kotaSelected  = selectKota.value;
    var putaranSelected = selectPutaran.value;

    // mengganti judul tabel
    judulTable.textContent = tableSelected + " - " + kotaSelected + " - " + putaranSelected;
});

// modal untuk upload table 
modalWilayah.addEventListener("change", function(){
    var kotaSelected  = selectKota.value; 
    var judulModal = modalWilayah;

    // mengganti judul pop-up
    judulModal.textContent = "Upload Data PDRB " + kotaSelected;
});

// modal pilih periode 
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