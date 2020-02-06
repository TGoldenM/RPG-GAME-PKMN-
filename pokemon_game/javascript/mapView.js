$(document).ready(function () {
    $('#map').click(function(){
        var v = getRandomInt(0, 1);
        var msg, pkmn;
        if(v === 0){
            var p = getSystemProperty('pkmn_appeared');
            pkmn = getRandPokemon();
            msg = sprintf(p.value, pkmn.name);
            $('#alertBox').html("<div class='alert-box alert-box-warning'><span>Warning: </span>"
                + msg + ". <a href='game.php?rp="+pkmn.id+"'>Catch</a></div>");
        } else {
            msg = 'Nothing found';
            $('#alertBox').html("<div class='alert-box alert-box-notice'><span>Notice: </span>"
                + msg + "</div>");
        }
    });
});