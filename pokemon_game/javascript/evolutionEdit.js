$(document).ready(function() {
    var rules = {
        required_lvl: {
            number: true
        }
    };
        
    $("#formData").validate({
        rules: rules
    });
    
    setPkmnAutoCpl('evolved_name', 'id_evolved_pkmn');
});