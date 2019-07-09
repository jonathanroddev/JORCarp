function calculateAmount(index) {
    let quantity = document.getElementById("quantity" + index);
    let unitPrice = document.getElementById("unitprice" + index);
    let amount = document.getElementById("amount" + index);
    amount.value = quantity.value * unitPrice.value;
    calculateTotal();
}

function calculateTotal() {
    let grossTotal = document.getElementById("grosstotal");
    let igicPercentage = 6.5/100;
    let igicTotal = document.getElementById("igic");
    let total = document.getElementById("total");
    let amounts = document.getElementsByName("amounts[]");
    let valor = 0;
    for(let i=0;i<amounts.length;i++){
        valor += +(amounts[i].value);
    }
    grossTotal.value = valor;
    igicAmount = parseFloat(valor * igicPercentage).toFixed(2);
    igicTotal.value = igicAmount;
    total.value = valor + igicAmount;
}