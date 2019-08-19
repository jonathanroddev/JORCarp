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
    cif.name = 'cifs[' + id + ']';
    name.removeAttribute('readonly');
    name.className = 'form-control';
    name.name = 'names[' + id + ']';
    mod.name = 'modSupplier';
    modForm = "modSup";
}

function deleteSupplier(e) {
    let id = e.value;
    let del = document.getElementById('del' + id);
    del.name = 'del[' + id + ']';
    let nameSup = document.getElementById('name' + id).value;
    if (window.confirm("¿Deseas borrar " + nameSup + "?")) {
        document.getElementById("delSup").submit();
    }
}

function submitForms() {
    document.getElementById("newSup").submit();
    if (modForm != "") document.getElementById("modSup").submit();
}

let indexRow = 2;

function createRowNewOutgoingInvoice() {
    let table = document.getElementById("outgoings");
    let row = table.insertRow(indexRow);
    let cell1 = row.insertCell(0);
    let cell2 = row.insertCell(1);
    let cell3 = row.insertCell(2);
    let cell4 = row.insertCell(3);
    let cell5 = row.insertCell(4);
    cell1.innerHTML = "<td scope='row'><select class='form-control' id='sup" + indexRow + "' name='suppliers[]'><option selected value='0'>Proveedor...</option> <?php for ($i = 0; $i < sizeof($suppliers); $i++) { ?> <option value='<?php echo $suppliers[$i][&quot;sup_id&quot;] ?>'><?php echo $suppliers[$i]['sup_name'] ?></option> <?php } ?> </select></td>";
    cell2.innerHTML = "<td scope='row'><input type='text' class='form-control' id='outref" + indexRow + "' name='outgoingsreferences[]' autocomplete='off'></td>";
    cell3.innerHTML = "<td scope='row'><input type='text' class='form-control' id='outgross" + indexRow + "' name='outgoingsgross[]' oninput='calculateAmount(" + i + ")' autocomplete='off'></td>";
    cell4.innerHTML = "<td scope='row'><input type='text' class='form-control' id='outigic" + indexRow + "' name='outgoingsigic[]' onkeyup='calculateTotal(" + i + ")' autocomplete='off'></td>";
    cell5.innerHTML = "<td scope='row'><input type='text' class='form-control' id='outtotal" + indexRow + "' name='outgoingtotals[]' onkeyup='calculateTotal(" + i + ")' autocomplete='off'></td>";
    indexRow++;
}

function calculateAmountOutgoings() {

}

function calculateTotalOutgoings() {

}

function getCookieSuppliers() {
    let suppliers = getCookie("suppliers");
}