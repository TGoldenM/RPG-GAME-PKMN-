$(document).ready(function() {
    var rules = {
        rank: {
            required: true,
            number: true
        }, name: {
            required: true
        }
    };
    
    $("#formData").validate({
        rules: rules
    });
});