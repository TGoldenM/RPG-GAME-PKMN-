$(document).ready(function() {
    var decPattern = "/^(\d+|\d+.\d{1})$/";
    var decPatternMsg = "Enter a number, example 1 or 1.5";
    
    var ms = "{";
    var rs = ms;
    
    $('.number').each(function(i, obj){
        var sep;
        if(i === 0){
            sep = "";
        } else {
            sep = ",";
        }
        
        rs += sep + "\"" + obj.id + "\":{\"required\":\"true\", \"pattern\":\""+decPattern+"\"}";
        ms += sep + "\"" + obj.id + "\":{\"pattern\":\""+decPatternMsg+"\"}";
    });
    
    ms += "}";
    rs += "}";
    
    $("#formData").validate({rules: $.parseJSON(rs), messages: $.parseJSON(ms)});
});