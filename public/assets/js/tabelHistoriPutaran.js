document.getElementById('periodeForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // get periode yang dipilih
    var selectedPeriode = [];
    var checkboxes = document.querySelectorAll('input[type="checbox"]:checked');
    checkboxes.forEach(function(checkbox) {
        // console.log(checkbox.value);
        selectedPeriode.push(checkbox.value);
    });

    // get the table 
    var table = document.getElementById('tableHistori');

    console.log(table);

    // get header tabel 
    var tableHeader = table.querySelector('thead tr');

    // add column 
    selectedPeriode.forEach(function(column) {
        var newTh = document.createElement('th');
        newTh.textContent = column;
        tableHeader.appendChild(newTh);
    });

    // get all row in table 
    var tableRows = table.querySelectorAll('tbody tr');

    // looping through all row and add the data
    tableRows.forEach(function(row) {
        selectedPeriode.forEach(function(column) {
            var newTd = document.createElement('td');
            newTd.textContent = 'Data for ' + column;
            row.appendChild(newTd);
        });
    });
})

