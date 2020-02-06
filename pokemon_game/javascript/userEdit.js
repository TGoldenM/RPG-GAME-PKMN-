$(document).ready(function() {
    var rules = {
        username: {
            required: true
        },
        mail: {
            required: true
        }
    };
    
    if ($("#id").length === 0) {
        $.extend(rules, {password: {
                required: true
            }}
        );
    }

    $("#formData").validate({
        rules: rules
    });
});