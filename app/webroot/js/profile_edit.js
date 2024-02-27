var year = new Date().getFullYear();

document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('image-input');
    var previewImage = document.getElementById('preview-image');

    console.log(fileInput);
    console.log(previewImage);

    fileInput.addEventListener('change', function(event) {
        var input = event.target;
        var reader = new FileReader();

        reader.onload = function() {
            previewImage.src = reader.result;
        };

        reader.readAsDataURL(input.files[0]);
    });
});

$(".datepicker").datepicker({
    dateFormat: 'yy-mm-dd',
    yearRange: '1950:' + year,
    showButtonPanel: true,
    changeMonth: true,
    changeYear: true,
    showOtherMonths: true,
    selectOtherMonths: true,
});

console.log($(".datepicker"));