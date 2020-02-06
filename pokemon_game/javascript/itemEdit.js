$(document).ready(function () {
    var rules = {
        name: {
            required: true
        },
        value: {
            required: true,
            number: true
        }
    };

    $("#formData").validate({
        rules: rules
    });
});