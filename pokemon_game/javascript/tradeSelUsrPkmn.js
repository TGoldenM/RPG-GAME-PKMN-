$(document).ready(function() {
    setTrainerAutoCpl('name', 'oid_tr');
    
    var rules = {
        name: {
            required: true
        }
    };
        
    $("#formData").validate({
        rules: rules
    });
	
	$("#formData a[id^='tpkmn_']").click(function(){
		var idAttr = $(this).prop("id");
      var p = idAttr.match(/tpkmn_(.*)/);
        
		var params = {
			'action': 'verify',
			'tid': parseInt(p[1]),
			'name': $('#name').val(),
			'cid_tr': $('#cid_tr').val(),
			'oid_tr': $('#oid_tr').val()
		}
		
		var q = encodeQueryData(params);
		window.location = 'proc_data/proc_trade.php?'+q;
	});
});