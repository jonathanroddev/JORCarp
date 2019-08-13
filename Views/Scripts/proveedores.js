function createRowNewSupplier(e) {
    let j = e.value;
    let table = document.getElementById("suppliers");
    let row = table.insertRow(j);
    let cell1 = row.insertCell(0);
    let cell2 = row.insertCell(1);
    cell1.innerHTML = "<td scope='row'><input style='text-align:center' type='text' class='form-control' name='supCifs[]' autocomplete='off' form='newSup'></td>";
    cell2.innerHTML = "<td scope='row'><input style='text-align:center' type='text' class='form-control' name='supNames[]' autocomplete='off' form='newSup'></td>";
    e.value = parseInt(j) + 1;
}

var modForm = "";

function modifySupplier(e) {
    let id = e.value;
    let cif = document.getElementById('cif' + id);
    let name = document.getElementById('name' + id);
    let mod = document.getElementById('modSupplier');
    cif.removeAttribute('readonly');
    cif.className = 'form-control';
    cif.name = 'cifs[' + id +']';
    name.removeAttribute('readonly');
    name.className = 'form-control';
    name.name = 'names[' + id +']';
    mod.name = 'modSupplier';
    modForm = "modSup";
}

function deleteSupplier(e) {
    let id = e.value;
    let del =  document.getElementById('del' + id);
    del.name = 'del[' + id +']';
    let nameSup = document.getElementById('name' + id).value;
    if (window.confirm("¿Deseas borrar " + nameSup + "?")) {
        document.getElementById("delSup").submit();
    }
}

function submitForms() {
    document.getElementById("newSup").submit();
    if(modForm != "") document.getElementById("modSup").submit();
}