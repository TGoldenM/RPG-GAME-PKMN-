$(document).ready(function() {
    jQuery.extend(jQuery.validator.messages, {
        required: "Required"
    });
    
    var rules = {
        name: {
            required: true
        },
        hp: {
            required: true,
            number: true
        },
        attack: {
            required: true,
            number: true
        },
        defense:{
            required: true,
            number: true
        },
        speed: {
            required: true,
            number: true
        },
        spec_attack: {
            required: true,
            number: true
        },
        spec_defense: {
            required: true,
            number: true
        },
        base_exp: {
            required: true,
            number: true
        },
        evhp: {
            required: true,
            number: true
        },
        evattack: {
            required: true,
            number: true
        },
        evdefense:{
            required: true,
            number: true
        },
        evspeed: {
            required: true,
            number: true
        },
        evspec_attack: {
            required: true,
            number: true
        },
        evspec_defense: {
            required: true,
            number: true
        }
    };
        
    $("#formData").validate({
        rules: rules
    });
});