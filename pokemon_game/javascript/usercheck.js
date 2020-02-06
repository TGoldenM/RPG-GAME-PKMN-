$(document).ready(function(){
    var path = '/pokemon_game';
    var controller = path + '/proc_data';

    setInterval(function() {
        var reqData = {
            'action': 'ping',
            'id': $('#id_usrchk').val()
        };

        $.ajax({
            method: 'POST',
            cache: false,
            dataType: 'json',
            data: reqData,
            url: controller + '/proc_user.php'
        });
    }, 60000);
});