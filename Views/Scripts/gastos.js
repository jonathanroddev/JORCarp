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
    let suppliers = getCookieSuppliers();
    let options = "";
    for (let i = 0; i < suppliers.length; i++) {
        let supplierId = suppliers[i].sup_id;
        let supplierName = suppliers[i].sup_name.replace(/\+/g, ' ');
        options += "<option value='" + supplierId + "'>" + supplierName + "</option>"
    }
    ;
    let table = document.getElementById("outgoing");
    let row = table.insertRow(indexRow);
    let cell1 = row.insertCell(0);
    let cell2 = row.insertCell(1);
    let cell3 = row.insertCell(2);
    let cell4 = row.insertCell(3);
    let cell5 = row.insertCell(4);
    let cell6 = row.insertCell(5);
    cell1.innerHTML = "<td scope='row'><select class='form-control' id='sup" + indexRow + "' name='suppliers[]' form='saveOutgoingForm'><option selected value='0'>Proveedor...</option> " + options + "</select></td>";
    cell2.innerHTML = "<td scope='row'><input type='text' class='form-control' id='outref" + indexRow + "' name='outgoingreferences[]' form='saveOutgoingForm' autocomplete='off' required></td>";
    cell3.innerHTML = "<td scope='row'><input type='date' class='form-control' id='outdate" + indexRow + "' name='outgoingdates[]' form='saveOutgoingForm'required></td>";
    cell4.innerHTML = "<td scope='row'><input type='text' class='form-control' id='outgross" + indexRow + "' name='outgoinggross[]' form='saveOutgoingForm' oninput='calculateTotalSingleOutgoing(" + indexRow + ")' autocomplete='off'></td>";
    cell5.innerHTML = "<td scope='row'><input type='text' class='form-control' id='outigic" + indexRow + "' name='outgoingigic[]' form='saveOutgoingForm' oninput='calculateTotalSingleOutgoing(" + indexRow + ")' autocomplete='off'></td>";
    cell6.innerHTML = "<td scope='row'><input type='text' class='form-control' id='outtotal" + indexRow + "' name='outgoingtotals[]' form='saveOutgoingForm' autocomplete='off' readonly></td>";
    indexRow++;
}

function calculateTotalSingleOutgoing(index) {
    let outGross = document.getElementById("outgross" + index);
    let outIgic = document.getElementById("outigic" + index);
    let outTotal = document.getElementById("outtotal" + index);
    if(outIgic.value == "") outTotal.value = outGross.value;
    else outTotal.value = (parseFloat(outGross.value) + parseFloat(outIgic.value)).toFixed(2);
    calculateTotalAllOutgoing();
}

function calculateTotalAllOutgoing() {
    let singleGross = document.getElementsByName("outgoinggross[]");
    let singleIgic = document.getElementsByName("outgoingigic[]");
    let singleTotal = document.getElementsByName("outgoingtotals[]");
    let allGross = document.getElementById("outallgross");
    let allIgic = document.getElementById("outalligic");
    let allTotal = document.getElementById("outalltotal");
    let grossSum = 0; let igicSum = 0; let totalSum = 0;
    for (let i = 0; i < singleGross.length; i++) {
        grossSum += +(singleGross[i].value);
        igicSum += +(singleIgic[i].value);
        totalSum += +(singleTotal[i].value);
    }
    allGross.value = grossSum.toFixed(2); allIgic.value = igicSum.toFixed(2); allTotal.value = totalSum.toFixed(2);
}

function getCookieSuppliers() {
    let name = "suppliers";
    let cookieArr = document.cookie.split(";");
    let suppliers = null;
    for (let i = 0; i < cookieArr.length; i++) {
        let cookiePair = cookieArr[i].split("=");
        if (name == cookiePair[0].trim()) {
            suppliers = JSON.parse(decodeURIComponent(cookiePair[1]));
        }
    }
    return suppliers;
}