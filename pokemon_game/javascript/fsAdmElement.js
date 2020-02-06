$(document).ready(function() {
    var $type = $("#type");
    setFsElementAutoCpl(parseInt($type.val()), 'name', 'id_obj');
    
    var rules = {
        name: {
            required: true
        },
        lvl: {
            number: true
        },
        pts_cost: {
            required: true,
            number: true
        }
    };
        
    $("#formData").validate({
        rules: rules
    });
    
    $type.change(function(){
        setFsElementAutoCpl(parseInt($(this).val()), 'name', 'id_obj');
    });
});