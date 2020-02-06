$(document).ready(function() {
    var rules = {
        name: {
            required: true
        },
        points: {
            required: true,
            number: true
        }
    };
    
    $("#formData").validate({
        rules: rules
    });
});