$(document).ready(function () {

    $('#mainForm, #mainForm2').validator();

    $(document).ready(function () {
        var date_input = $('input[name="date"]'); //our date input has the name "date"
        date_input.datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true
        });
    });

});

