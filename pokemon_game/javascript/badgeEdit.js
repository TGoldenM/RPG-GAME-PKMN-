$(document).ready(function() {
    var rules = {
        name: {
            required: true
        }
    };
    
    $("#formData").validate({
        rules: rules
    });
});