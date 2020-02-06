progPath = '/pokemon_game';
progController = progPath + '/proc_data';

function makeFight(data){
    var tr1 = data.trainer1;
    var tr2 = data.trainer2;
    var tpkmn1 = tr1.tpkmn;
    var tpkmn2 = tr2.tpkmn;
    var idmove1 = tr1.id_move;
    var idmove2 = tr2.id_move;
    var move1 = findInArray(tpkmn1.moves, idmove1);
    var move2 = findInArray(tpkmn2.moves, idmove2);
    
    //the effect vars make reference to the effectiveness of a move
    //according to its type in relation to the opponent's selected move
    var effect1 = findEffect(move1.type.effects, move2.type.id);
    var effect2 = findEffect(move2.type.effects, move1.type.id);
    
    var cat1 = move1.category.id;
    var cat2 = move2.category.id;
    
    var dmg1 = 0;
    var dmg2 = 0;
    var item1 = data.trainer1.item;
    var item2 = data.trainer2.item;
    
    var atk1, def1;
    var moveValues = getRaisedMoveStat(tpkmn1, item1, cat1);
    atk1 = moveValues[0];
    def1 = moveValues[1];
    
    var atk2, def2;
    var moveValues = getRaisedMoveStat(tpkmn2, item2, cat2);
    atk2 = moveValues[0];
    def2 = moveValues[1];
    
    //Base power of moves
    var mv1 = parseInt(move1.value);
    var mv2 = parseInt(move2.value);
            
    dmg1 = (((2*tpkmn1.level + 10)/250) * atk1/def2 * mv1 + 2);
    dmg1 *= move1.type.name === move2.type.name ? 1.5 : 1;//STAB
    dmg1 *= getRandomFloat(0.85, 1);
    dmg1 *= parseFloat(effect1.multiplier);//Type move factor
    dmg1 = Math.floor(dmg1);
    
    dmg2 = (((2*tpkmn2.level + 10)/250) * atk2/def1 * mv2 + 2);
    dmg2 *= move1.type.name === move2.type.name ? 1.5 : 1;
    dmg2 *= getRandomFloat(0.85, 1);
    dmg2 *= parseFloat(effect2.multiplier);
    dmg2 = Math.floor(dmg2);
    
    var spd1 = parseInt(tpkmn1.speed);
    var spd2 = parseInt(tpkmn2.speed);
    
    if(spd1 > spd2){
        tpkmn2.cur_hp -= dmg1;
        if(tpkmn2.cur_hp <= 0){
            tpkmn2.cur_hp = 0;
        } else {
            tpkmn1.cur_hp -= dmg2;
            if(tpkmn1.cur_hp <= 0){
                tpkmn1.cur_hp = 0;
            }
        }
        
    } else if(spd1 < spd2){
        tpkmn1.cur_hp -= dmg2;
        if(tpkmn1.cur_hp <= 0){
            tpkmn1.cur_hp = 0;
        } else {
            tpkmn2.cur_hp -= dmg1;
            if(tpkmn2.cur_hp <= 0){
                tpkmn2.cur_hp = 0;
            }
        }
    } else {
        var v = getRandomInt(0, 1);
        if(v === 0){
            tpkmn2.cur_hp -= dmg1;
            if(tpkmn2.cur_hp <= 0){
                tpkmn2.cur_hp = 0;
            }
        } else {
            tpkmn1.cur_hp -= dmg2;
            if(tpkmn1.cur_hp <= 0){
                tpkmn1.cur_hp = 0;
            }
        }
    }
        
    return new Array({effect: effect1, move: move1}
        , {effect: effect2, move: move2});
}

function calcExperienceGain(data){
    var tr1 = data.trainer1;
    var tr2 = data.trainer2;
    var tpkmn1 = tr1.tpkmn;
    var tpkmn2 = tr2.tpkmn;
    
    //Get fainted and victorious pokemon
    var fpkmn = tpkmn1.cur_hp <= 0 ? tpkmn1 : tpkmn2;
    var vpkmn = tpkmn1.cur_hp > 0 ? tpkmn1 : tpkmn2;

    var a = pkmnIsWild(fpkmn) ? 1 : 1.5;
    var b = fpkmn.pokemon.base_exp; //Base experience
    var e = 1;
    
    var L = fpkmn.level;
    var Lp = vpkmn.level;

    var p = 1;
    var s = 1;
    var t = 1;
    
    var exp = (((a * b * L)/ 5 * s) * ((Math.pow(2*L + 10, 2.5)/Math.pow(L + Lp + 10, 2.5)) + 1)) * t * e * p;
    return Math.round(exp);
}

function increasePkmnStats(tpkmn, factor){
    var pkmn = tpkmn.pokemon;
    var v = Math.round(factor*pkmn.hp/50);
    tpkmn.hp = parseInt(tpkmn.hp) + v;
    
    v = Math.round(factor*pkmn.attack/50);
    tpkmn.attack = parseInt(tpkmn.attack) + v;
    
    v = Math.round(factor*pkmn.defense/50);
    tpkmn.defense = parseInt(tpkmn.defense) + v;
    
    v = Math.round(factor*pkmn.speed/50);
    tpkmn.speed = parseInt(tpkmn.speed) + v;
    
    v = Math.round(factor*pkmn.spec_attack/50);
    tpkmn.spec_attack = parseInt(tpkmn.spec_attack) + v;
    
    v = Math.round(factor*pkmn.spec_defense/50);
    tpkmn.spec_defense = parseInt(tpkmn.spec_defense) + v;
}

function getRaisedMoveStat(tpkmn, item, cat){
    var atk, def;
    if(cat == 1){//Physical move
        atk = parseInt(tpkmn.attack);
        //Raise attack if item stat is normal attack
        atk += item !== null && item.stat.id == 2 ? parseInt(item.value) : 0;

        def = parseInt(tpkmn.defense);
        //Raise defense if item stat is normal defense
        def += item !== null && item.stat.id == 3 ? parseInt(item.value) : 0;
    } else if(cat == 2) {//Special move
        atk = parseInt(tpkmn.spec_attack);
        //Raise attack if item stat is special attack
        atk += item !== null && item.stat.id == 5 ? parseInt(item.value) : 0;

        def = parseInt(tpkmn.spec_defense);
        //Raise defense if item stat is special defense
        def += item !== null && item.stat.id == 6 ? parseInt(item.value) : 0;
    } else {
        atk = 1;
        def = 1;
    }
    
    return new Array(atk, def);
}

function pkmnIsWild(tpkmn){
    var t = tpkmn.trainer;
    var f = t.faction;
    
    if((typeof f) === 'undefined'){
        return false;
    }
    
    return f.name === 'Wild Nature'
            && t.name === 'Forest';
}

function getEffectMsg(move, effect){
    var m = parseFloat(effect.multiplier);
    switch(m){
        case 0:
            return move.name + ' had no effect';
        case 0.5:
            return move.name + ' was not very effective';
        case 2:
            return move.name + ' was super effective!';
    }
    
    return null;//Normal attack
}

function getFirstAvailablePkmn(arr){
    for(var i = 0; i < arr.length; i++){
        var elem = arr[i];
        var p = elem.hp == 0 ? 0 : Math.round(elem.cur_hp/elem.hp * 100);
        if(p > 0){
            return elem;
        }
    }
    
    return null;
}

function getAvailablePkmnCount(arr){
    var r = 0;
    for(var i = 0; i < arr.length; i++){
        var elem = arr[i];
        var p = elem.hp == 0 ? 0 : Math.round(elem.cur_hp/elem.hp * 100);
        if(p > 0){
            r++;
        }
    }
    return r;
}

function getHighestLvlPkmn(arr){
    var htpkmn = null;
    var lvl = 0;
    
    for(var i = 0; i < arr.length; i++){
        var tpkmn = arr[i];
        if(tpkmn.level > lvl){
            lvl = tpkmn.level;
            htpkmn = tpkmn;
        }
    }
    return htpkmn;
}

function getRandPokemon(){
    var reqData = {
        'action': 'read_r'
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_pokemon.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function assignRandPkmnItem(id_trainer){
    var reqData = {
        'action': 'assign',
        'id_trainer': id_trainer
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_trainer_item.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}
function getCurrentTrainer(){
    var reqData = {
        'action': 'ctrn'
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_trainer.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function getTrainer(id){
    var reqData = {
        'action': 'read',
        'id' : id
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_trainer.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function getTrainerPokemonsList(data){
    var reqData = {
        'action': 'list'
    };

    $.extend(reqData, data);

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_trainer_pokemon.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function getAssigPokemonMove(id){
    var reqData = {
        'action': 'read',
        'id': id
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_pokemon_assig_move.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function getTrainerPkmn(id){
    var reqData = {
        'action': 'read',
        'id': id
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_trainer_pokemon.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function getPkmnItem(id){
    var reqData = {
        'action': 'read',
        'id': id
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_item.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function getFirstBadgeByGym(id_gym){
    var reqData = {
        action: 'read',
        type: 'gym',
        id: id_gym
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_badge.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function assignBadge(trainer, badge){
    var reqData = {
        action: 'assign',
        id_trainer: trainer.id,
        id: badge.id
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_badge.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function updateHPTrainerPkmn(data){
    var reqData = {
        'action': 'edit',
        'id': data.id,
        'cur_hp': data.cur_hp
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_trainer_pokemon.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function updateExpTrainerPkmn(data){
    var reqData = {
        'action': 'edit',
        'id': data.id,
        'exp': data.exp
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_trainer_pokemon.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function updateVDTrainer(data){
    var reqData = {
        'action': 'vd',
        'id': data.id,
        'victory': data.victory,
        'defeat': data.defeat
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_trainer.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function getTrainerPkmnLeveled(baseLvl, id_pokemon, id_trainer){
    var reqData = {
        'action': 'gen',
        'lvl': baseLvl,
        'id': id_pokemon,
        'id_trainer': id_trainer
    };

    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_pokemon.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function updateTrainerPkmn(inclMoves, data){
    var reqData = {
        'action': 'edit'
    };

    $.extend(reqData, data);
    if(!inclMoves){
        delete reqData.moves;
    }
    
    var obj = null;
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_trainer_pokemon.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function saveTrainerPkmn(data){
    var obj = null;
    if(data.id && data.id !== null){
        data.id = null;
    }
    
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: data,
        async: false,
        url: progController + '/proc_trainer_pokemon.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });
    
    return obj;
}

function updateTrainerPts(data){
    var obj = false;
    var reqData = {
        'action': 'pts'
    };
    
    if(data.id_faction !== null){
        $.extend(reqData, data);
        $.ajax({
            method: 'POST',
            cache: false,
            dataType: 'json',
            data: reqData,
            async: false,
            url: progController + '/proc_trainer.php'
        }).done(function(resp) {
            obj = readAJAXResponse(resp);
        });
    }
    
    return obj;
}

function deleteTrainerItem(data){
    var obj = false;
    var reqData = {
        'action': 'delete',
        'id': data.id,
        'btnYes': 1,
        'j': 1
    };
    
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_trainer_item.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });

    return obj;
}

function evalPkmnLvlAndUpdateTrainer(data){
    var trainer1 = data.trainer1;
    var trainer2 = data.trainer2;
    
    if (trainer1.id_faction !== trainer2.id_faction
        && trainerIsUser(trainer2)) {
        var tpkmns1 = data.tpkmns1;
        var tpkmns2 = data.tpkmns2;
    
        var tpkmn1 = getHighestLvlPkmn(tpkmns1);
        var tpkmn2 = getHighestLvlPkmn(tpkmns2);
    
        if (tpkmn1.level < tpkmn2.level) {
            updateTrainerPts({id: trainer1.id, type: 1});
            updateTrainerPts({id: trainer2.id, type: 2});
        } else {
            updateTrainerPts({id: trainer1.id, type: 2});
            updateTrainerPts({id: trainer2.id, type: 1});
        }
    }    
}

function tpkmnEvolveIfReady(id_item, tpkmn){
    var obj = false;
    var reqData = {
        'action': 'evl',
        'id': tpkmn.id
    };
    
    if(id_item !== null){
        $.extend(reqData, {'id_item': id_item});
    }
    
    $.ajax({
        method: 'POST',
        cache: false,
        dataType: 'json',
        data: reqData,
        async: false,
        url: progController + '/proc_trainer_pokemon.php'
    }).done(function(resp) {
        obj = readAJAXResponse(resp);
    });

    return obj;
}

function trainerIsUser(trn){
    var userAssigned = (trn.hasOwnProperty('id_user')
                    && trn.id_user != null)
                    || (trn.hasOwnProperty('user')
                    && trn.user != null);
    
    return userAssigned;
}

function assignPokemonToWinner(tpkmn, id_trainer){
    $.extend(tpkmn, {
        id_pokemon: tpkmn.pokemon.id,
        id_trainer: id_trainer
    });

    var curHp = tpkmn.cur_hp;
    var equipped = tpkmn.equipped;
    var tradeable = tpkmn.tradeable;
    var sellable = tpkmn.sellable;
    
    tpkmn.cur_hp = tpkmn.hp;
    tpkmn.equipped = 0;
    tpkmn.tradeable = 0;
    tpkmn.sellable = 0;
    
    saveTrainerPkmn(tpkmn);
    
    tpkmn.cur_hp = curHp;
    tpkmn.equipped = equipped;
    tpkmn.tradeable = tradeable;
    tpkmn.sellable = sellable;
}

function updateSelPkmn(portrait, tpkmn){
    $('#'+portrait+' .b_pkmn_icon').html('<img src="'
            + tpkmn.pokemon.imgs[0].image_url + '" alt="'
            + tpkmn.pokemon.name + '" />');
    
    var pctg = tpkmn.hp === 0 ? 0 : Math.round(tpkmn.cur_hp/tpkmn.hp * 100);
    $('#'+portrait+' .b_pkmn_hp').html('<span>'+pctg+'%</span>');
    $('#'+portrait+' .b_pkmn_lvl').text('Level: '+tpkmn.level);
}

function updateMovesPkmn(movesCBox, tpkmn){
    var content = '';
    var moveData = [];
    
    for(var i in tpkmn.moves){
        var mv = tpkmn.moves[i];
        if(!(mv.type.name in moveData)){
            moveData[mv.type.name] = [mv];
        } else {
            moveData[mv.type.name].push(mv);
        }
    }
    
    for (var i in moveData) {
        var data = moveData[i];
        content += "<optgroup label='" + i + "'>";
        for (var j in data) {
            var mv = data[j];
            var name = mv.name + " (" + mv.category.name + ")";
            content += "<option value='" + mv.id + "'>" + name + "</option>";
        }

        content += "</optgroup>";
    }
    
    $('#'+movesCBox).html(content);
}

function updateItemsCBox(itemsCBox, trItems){
    var content = '<option value="0">--No selection--</option>';
    var itemData = [];
    
    for(var i in trItems){
        var trItem = trItems[i];
        var item = trItem.item;
        
        if(!(item.category.name in itemData)){
            itemData[item.category.name] = [trItem];
        } else {
            itemData[item.category.name].push(trItem);
        }
    }
    
    for (var i in itemData) {
        var data = itemData[i];
        content += "<optgroup label='" + i + "'>";
        for (var j in data) {
            var trItem = data[j];
            var item = trItem.item;
            var stat = item.stat;
            
            var name;
            if(stat !== null){
                name = item.name + " (" + stat.name + ")";
            } else {
                name = item.name;
            }
            
            content += "<option value='" + item.id + "'>" + name + "</option>";
        }

        content += "</optgroup>";
    }
    
    $('#'+itemsCBox).html(content);
}

function deleteTrnItemFromArr(arr, id_item){
    for(var i = 0; i < arr.length; i++){
        var e = arr[i];
        if(e.item.id == id_item){
            arr.splice(i, 1);
            break;
        }
    }
}

function getTrnItemFromArr(arr, id_item){
    for(var i = 0; i < arr.length; i++){
        var e = arr[i];
        if(e.item.id == id_item){
            return e;
        }
    }
    
    return null;
}

function updatePkmnInList(tableId, tpkmn){
    var $pctg = $('#'+tableId+' #pkmn_'+tpkmn.id + ' .b_pkmn_pctg');
    var $lvl = $('#'+tableId+' #pkmn_'+tpkmn.id + ' .b_pkmn_list_lvl');
    var $name = $('#'+tableId+' #pkmn_'+tpkmn.id + ' .b_pkmn_choice');
    
    var pctg = tpkmn.hp === 0 ? 0 : Math.round(tpkmn.cur_hp/tpkmn.hp * 100);
    $pctg.text(pctg+'%');
    $lvl.text(tpkmn.level);
    $name.text(tpkmn.pokemon.name);
}

function appendPkmnLog(id_log, msg){
    var content = $('#'+id_log).html();
    content += '<span>'+msg+'</span><br/>';
    $('#'+id_log).html(content);
}

function resetPkmnLog(id_log){
    var content = $('#'+id_log).html();
    content = '';
    $('#'+id_log).html(content);
}
