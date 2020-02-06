function setDatatableConfirmOnClick(idPrefix, msg, dataSetStyle){
    dataSetStyle = typeof dataSetStyle !== 'undefined'
            ? dataSetStyle : '.data_table';
    
    $(dataSetStyle + ' a[id^='+idPrefix+']').click(function(e){
        var choice = confirm(msg);
        if(!choice){
            e.preventDefault();
        }
    });
}

function findInArray(arr, id){
    var r = null;
    for(var i = 0; i < arr.length; i++){
        var e = arr[i];
        if(e.id == id){
            r = e;
            break;
        }
    }
    
    return r;
}

function updateInArray(arr, id, obj){
    for(var i = 0; i < arr.length; i++){
        var e = arr[i];
        if(e.id == id){
            arr[i] = obj;
            break;
        }
    }
}

function deleteFromArray(arr, id){
    for(var i = 0; i < arr.length; i++){
        var e = arr[i];
        if(e.id == id){
            arr.splice(i, 1);
            break;
        }
    }
}

function findEffect(effectsArr, id_type_2){
    var r = null;
    for(var i = 0; i < effectsArr.length; i++){
        var e = effectsArr[i];
        if(e.id_type_two == id_type_2){
            r = e;
            break;
        }
    }
    
    return r;
}

function selectRandElement(arr){
    var elem = arr[Math.floor(Math.random()*arr.length)];
    return elem;
}

function setPkmnAutoCpl(id_text, id_value, fnSelect){
    if(jQuery().autocomplete){
        var url = "proc_data/proc_pokemon.php?action=acpl";
//        if(typeof id_faction !== 'undefined'){
//            url += "&idf="+id_faction;
//        }
        
        $('#'+id_text).autocomplete({
            serviceUrl: url,
            ajaxSettings: {
                method: 'POST',
                dataType: 'json'
            },
            onSelect: function(sugg){
                $('#'+id_value).val(sugg.data.id);
                if(typeof fnSelect !== 'undefined'){
                    fnSelect(sugg);
                }
            }
        });
    }
}

function setItemAutoCpl(id_text, id_value, fnSelect){
    if(jQuery().autocomplete){
        var url = "proc_data/proc_item.php?action=acpl";
        
        $('#'+id_text).autocomplete({
            serviceUrl: url,
            ajaxSettings: {
                method: 'POST',
                dataType: 'json'
            },
            onSelect: function(sugg){
                $('#'+id_value).val(sugg.data.id);
                if(typeof fnSelect !== 'undefined'){
                    fnSelect(sugg);
                }
            }
        });
    }
}

function setTrainerAutoCpl(id_text, id_value, fnSelect){
    if(jQuery().autocomplete){
        $('#'+id_text).autocomplete({
            serviceUrl: 'proc_data/proc_trainer.php?action=acpl',
            ajaxSettings: {
                method: 'POST',
                dataType: 'json'
            },
            onSelect: function(sugg){
                $('#'+id_value).val(sugg.data.id);
                if(typeof fnSelect !== 'undefined'){
                    fnSelect(sugg);
                }
            }
        });
    }
}

function setFsElementAutoCpl(type, id_text, id_value, fnSelect){
    switch(type){
        case 1:
            setItemAutoCpl(id_text, id_value, fnSelect);
            break;
            
        case 2:
            setPkmnAutoCpl(id_text, id_value, fnSelect);
            break;
    }
}

function readAJAXResponse(resp){
    if(resp.correct === 1){
        return resp.data;
    } else {
        console.log("Error with AJAX request ", resp.msg);
    }
    
    return null;
}

function getSystemProperty(name){
    var reqData = {
        'action': 'read',
        'name': name
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: '/pokemon_game/proc_data/proc_property.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

// Usage:
//   var data = { 'first name': 'George', 'last name': 'Jetson', 'age': 110 };
//   var querystring = encodeQueryData(data);
// 
function encodeQueryData(data)
{
   var ret = [];
   for (var d in data)
      ret.push(encodeURIComponent(d) + "=" + encodeURIComponent(data[d]));
   return ret.join("&");
}

/**
 * Returns a random float between min (inclusive) and max (inclusive)
  */
function getRandomFloat(min, max) {
    return Math.random() * (max - min + 1) + min;
}

/**
 * Returns a random integer between min (inclusive) and max (inclusive)
  */
function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}