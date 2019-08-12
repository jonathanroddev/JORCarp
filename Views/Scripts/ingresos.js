function fileValidation() {
    var fileInput = document.getElementById("customersFile");
    var filePath = fileInput.value;
    var allowedExtensions = /(\.xls|\.xlsx)$/i;
    if (!allowedExtensions.exec(filePath)) {
        alert("Solo se aceptan archivos Excel");
        fileInput.value = '';
        return false;
    } else {
        document.getElementById("uploadForm").submit();
    }
}

function displayDiv() {
    document.getElementById("createFile").style.display = "flex";
    document.getElementById("displayDivBtn").style.display = "none";
}

function submiBtnClick() {
    let formValid = document.forms["exportForm"].checkValidity();
    return formValid;
}
