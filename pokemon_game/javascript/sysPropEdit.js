$(document).ready(function() {
    var rules = {
        name: {
            required: true
        }, value: {
            required: true
        }
    };
    
    $("#formData").validate({
        rules: rules
    });
});