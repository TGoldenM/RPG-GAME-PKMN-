$(document).ready(function(){
    setDatatableConfirmOnClick('cnl_', 'Do you really want to cancel this trade?');
    setDatatableConfirmOnClick('rej_', 'Do you really want to reject this trade?');
    setDatatableConfirmOnClick('acc_', 'Do you really want to accept this trade?');
    
    $('#tradeOpt').change(function(){
        $('#formData').submit();
    });
});