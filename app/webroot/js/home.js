// A $( document ).ready() block.
$( document ).ready(function() {
    alert("Hello Bradder");
    var year = new Date().getFullYear();

    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        yearRange: '1950:' + year,
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true,
    });
});
