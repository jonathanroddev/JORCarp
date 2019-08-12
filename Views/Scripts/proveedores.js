function createRowNewSupplier(e) {
    let j = e.value;
    let table = document.getElementById("suppliers");
    let row = table.insertRow(j);
    let cell1 = row.insertCell(0);
    let cell2 = row.insertCell(1);
    cell1.innerHTML = "<td scope='row'><input type='text' class='form-control' name='supCifs[]' autocomplete='off'></td>";
    cell2.innerHTML = "<td scope='row'><input type='text' class='form-control' name='supNames[]' autocomplete='off'></td>";
    e.value = parseInt(j) + 1;
}