let i = 2;

function createNotion() {
    let table = document.getElementById("invoice");
    let row = table.insertRow(i);
    let cell1 = row.insertCell(0);
    let cell2 = row.insertCell(1);
    let cell3 = row.insertCell(2);
    let cell4 = row.insertCell(3);
    cell1.innerHTML = "<td scope='row'><input type='text' class='form-control' id='quantity" + i + "' name='quantities[]' oninput='calculateAmount(" + i + ")'></td>";
    cell2.innerHTML = "<td><input type='text' class='form-control' id='description" + i + "' name='descriptions[]'></td>";
    cell3.innerHTML = "<td><input type='text' class='form-control' id='unitprice" + i + "' name='unitprices[]' oninput='calculateAmount(" + i + ")'></td>";
    cell4.innerHTML = "<td><input type='text' class='form-control' id='amount" + i + "' name='amounts[]' onkeyup='calculateTotal(" + i + ")'></td>";
    i++;
}

function createNotionSavedInvoice(e) {
    let j = e.value;
    let table = document.getElementById("invoice");
    let row = table.insertRow(j);
    let cell1 = row.insertCell(0);
    let cell2 = row.insertCell(1);
    let cell3 = row.insertCell(2);
    let cell4 = row.insertCell(3);
    cell1.innerHTML = "<td scope='row'><input type='text' class='form-control' id='quantity" + j + "' name='quantities[]' oninput='calculateAmount(" + j + ")'></td>";
    cell2.innerHTML = "<td><input type='text' class='form-control' id='description" + j + "' name='descriptions[]'></td>";
    cell3.innerHTML = "<td><input type='text' class='form-control' id='unitprice" + j + "' name='unitprices[]' oninput='calculateAmount(" + j + ")'></td>";
    cell4.innerHTML = "<td><input type='text' class='form-control' id='amount" + j + "' name='amounts[]' onkeyup='calculateTotal(" + j + ")'></td>";
    e.value = parseInt(j) + 1;
}

function calculateAmount(index) {
    let quantity = document.getElementById("quantity" + index);
    let unitPrice = document.getElementById("unitprice" + index);
    let amount = document.getElementById("amount" + index);
    amount.value = quantity.value * unitPrice.value;
    calculateTotal();
}

function calculateTotal() {
    let reference = document.getElementById("reference");
    let grossTotal = document.getElementById("grosstotal");
    let igicPercentage = 6.5 / 100;
    let igicTotal = document.getElementById("igic");
    let total = document.getElementById("total");
    let amounts = document.getElementsByName("amounts[]");
    let gross = 0;
    for (let i = 0; i < amounts.length; i++) {
        gross += +(amounts[i].value);
    }
    grossTotal.value = gross;
    if (isNaN(reference.value) || reference.value == "") {
        igicTotal.value = "";
        total.value = gross;
    } else {
        igicAmount = parseFloat(gross * igicPercentage).toFixed(2);
        igicTotal.value = igicAmount;
        total.value = (parseFloat(gross) + parseFloat(igicAmount)).toFixed(2);
    }
}

function listInvoice(e) {
    let reference = document.getElementById("reference");
    if (e.checked == true) {
        reference.removeAttribute("readonly");
        reference.setAttribute("required", "true");
    } else {
        reference.setAttribute("readonly", "true");
        reference.removeAttribute("required");
        reference.value = "";
        calculateTotal();
    }
}

