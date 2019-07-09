function calculateAmount(index){
let quantity = document.getElementById("quantity"+index);
let unitPrice = document.getElementById("unitprice"+index);
let amount = document.getElementById("amount"+index);
amount.value =  quantity.value * unitPrice.value;
}