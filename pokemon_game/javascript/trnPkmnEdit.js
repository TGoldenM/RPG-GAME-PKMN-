$(document).ready(function() {
    setPkmnAutoCpl('name', 'id_pokemon', function(sugg){
        var genderless = parseInt(sugg.data.genderless);
        if(!genderless){
            var n = getRandomInt(0, 1);
            if(n == 0){
                $('#gender').val('M');
            } else {
                $('#gender').val('F');
            }
        } else {
            $('#gender').val('?');
        }
    });
    
    $('#norm').click(function(){
        var tpkmn = getTrainerPkmnLeveled($('#lvl').val(),
            $('#id_pokemon').val(), $('#id_trainer').val());
            
        $('#hp').val(tpkmn.hp);
        $('#cur_hp').val(tpkmn.hp);
        $('#attack').val(tpkmn.attack);
        $('#defense').val(tpkmn.defense);
        $('#speed').val(tpkmn.speed);
        $('#spec_attack').val(tpkmn.spec_attack);
        $('#spec_defense').val(tpkmn.spec_defense);
    });
    
    $('#lvl').change(function(){
        if(!isNaN($(this).val())){
            $('#exp').text(Math.pow($(this).val(), 3));
        }
    });
    
    var rules = {
        name: {
            required: true
        },
        lvl: {
            required: true,
            number: true
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
        }
    };
    
    
    $("#formData").validate({
        rules: rules
    });
});