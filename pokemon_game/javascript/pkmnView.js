$(document).ready(function() {
    $('#btnBuy').click(function(e){
        var choice = confirm('Do you really want to buy this pokemon?');
        if(!choice){
            e.preventDefault();
        }
    });
});