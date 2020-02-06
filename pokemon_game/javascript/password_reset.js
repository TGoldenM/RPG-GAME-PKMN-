$(document).ready(function() {
    $("#frmReset").validate({
        rules: {
            password: "required",
            cpassword: {
                equalTo: "#password"
            }
        }
    });
});