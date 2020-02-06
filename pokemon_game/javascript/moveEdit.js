$(document).ready(function () {
    var decPattern = /^(\d+|\d+.\d{1})$/;
    var rules = {
        name: {
            required: true
        },
        value: {
            required: true,
            number: true
        },
        accuracy: {
            required: true,
            pattern: decPattern
        },
        power_points: {
            required: false,
            pattern: decPattern
        }
    };

    var decPatternMsg = 'Enter a number, example 1 or 1.5';
    var messages = {
        accuracy: {
            pattern: decPatternMsg
        },
        power_points: {
            pattern: decPatternMsg
        }
    };

    $("#formData").validate({
        rules: rules, messages: messages
    });
});