function fileValidation(){
    var fileInput = document.getElementById('customersFile');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.xls|\.xlsx)$/i;
    if(!allowedExtensions.exec(filePath)){
        alert('Solo se aceptan archivos Excel');
        fileInput.value = '';
        return false;
    }else{
       document.getElementById('uploadForm').submit();
    }
}