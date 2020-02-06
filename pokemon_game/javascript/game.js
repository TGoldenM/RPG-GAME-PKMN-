$(document).ready(function(){
    curTrainer = getCurrentTrainer();
    opTrainer = getTrainer($('#id_tr').val());

    curPkmns = getTrainerPokemonsList({
        id_trainer: curTrainer.id,
        equipped: true,
        typeLoad: 2
    });
    
    opPkmns = getTrainerPokemonsList({
        id_trainer: opTrainer.id,
        equipped: true,
        typeLoad: 2
    });
    
    if(opPkmns === null){
        opPkmns = [curSelPkmn];
    }
    
    var pctg = curSelPkmn.hp === 0 ? 0 : Math.round(curSelPkmn.cur_hp/curSelPkmn.hp * 100);
    if(pctg == 0){
        appendPkmnLog('usrLog', "Your pokemon has fainted. Choose other.");
    }

    $("#b_attack").click(function(){
        resetPkmnLog('usrLog');
        resetPkmnLog('opLog');
        
        var pctg = curSelPkmn.hp === 0 ? 0 : Math.round(curSelPkmn.cur_hp/curSelPkmn.hp * 100);
        if(pctg == 0){
            appendPkmnLog('usrLog', "Your pokemon has fainted. Choose other.");
            return;
        }
        
        var curMoveId = parseInt($("#b_move_usr").val());
        var opMoveId = parseInt($("#b_move_op").val());
        var curItemId = getPkmnItem($('#b_item_usr').val());
        
        var fightData = {
            trainer1: {
                id_move: curMoveId,
                tpkmn: curSelPkmn, //Initialized in game.php
                item: curItemId //b_item_usr
            },
            trainer2: {
                id_move: opMoveId,
                tpkmn: opSelPkmn,   //Initialized in game.php
                item: null
            }
        };
        
        var results = makeFight(fightData);
        var move1 = results[0].move;
        var move2 = results[1].move;
        
        appendPkmnLog('usrLog', curSelPkmn.pokemon.name+' uses ' + move1.name);
        var msg = getEffectMsg(results[0].move, results[0].effect);
        if(msg != null){
            appendPkmnLog('usrLog', msg);
        }
        
        appendPkmnLog('opLog', opSelPkmn.pokemon.name+' uses ' + move2.name);
        msg = getEffectMsg(results[1].move, results[1].effect);
        if(msg != null){
            appendPkmnLog('opLog', msg);
        }
        
        updateSelPkmn('usrPkmn', curSelPkmn);
        updateSelPkmn('opPkmn', opSelPkmn);
        
        updatePkmnInList('curEqpPkmn', curSelPkmn);
        updateInArray(curPkmns, curSelPkmn.id, curSelPkmn);
        
        updatePkmnInList('opEqpPkmn', opSelPkmn);
        updateInArray(opPkmns, opSelPkmn.id, opSelPkmn);
        
        updateHPTrainerPkmn(curSelPkmn);
        
        if(curSelPkmn.cur_hp == 0){
            appendPkmnLog('usrLog', curSelPkmn.pokemon.name+' has fainted.');
            if(getAvailablePkmnCount(curPkmns) === 0){
                updateVDTrainer({id: curTrainer.id, victory: 0, defeat: 1});
                updateVDTrainer({id: opTrainer.id, victory: 1, defeat: 0});
                evalPkmnLvlAndUpdateTrainer({trainer1: opTrainer,
                    trainer2: curTrainer,
                    tpkmns1: curPkmns,
                    tpkmns2: opPkmns
                });
                
                alert("You have lost the battle");
                window.location = 'main.php';
            } else {
                appendPkmnLog('usrLog', 'Change your pokemon! (click on the names below)');
            }
        }
        
        if(opSelPkmn.cur_hp == 0){
            appendPkmnLog('opLog', opSelPkmn.pokemon.name+' has fainted.');
            var prevLevel = curSelPkmn.level;
            
            var exp = calcExperienceGain(fightData);
            curSelPkmn.exp = parseInt(curSelPkmn.exp) + exp;
            curSelPkmn.level = Math.round(Math.pow(curSelPkmn.exp, 1/3));
            
            var curLevel = curSelPkmn.level;
            var cpkmn = curSelPkmn.pokemon;
            
            if(prevLevel < curLevel){
                increasePkmnStats(curSelPkmn, curLevel - prevLevel);
                updateTrainerPkmn(false, curSelPkmn);
                appendPkmnLog('usrLog', cpkmn.name + " has leveled up!");

            } else {
                updateExpTrainerPkmn(curSelPkmn);
            }
            
            var epkmn = tpkmnEvolveIfReady(null, curSelPkmn);
            if(epkmn.id != cpkmn.id){
                curSelPkmn.pokemon = epkmn;
                appendPkmnLog('usrLog', cpkmn.name + " has evolved to "+epkmn.name+"!");
            }

            updateSelPkmn('usrPkmn', curSelPkmn);
            updatePkmnInList('curEqpPkmn', curSelPkmn);
            updateInArray(curPkmns, curSelPkmn.id, curSelPkmn);
            
            if(pkmnIsWild(opSelPkmn)){
                assignPokemonToWinner(opSelPkmn, curSelPkmn.trainer.id);
                alert("You've caught " + opSelPkmn.pokemon.name + "!");
                if(!pkmnIsWild(opSelPkmn)){
                    $('.b_battle_restart').show();
                    $("#b_attack").prop("disabled", true);
                } else {
                    window.location = 'main.php';
                }
            } else {
                var userAssigned = trainerIsUser(opSelPkmn.trainer);
            
                if(opSelPkmn.tradeable == 1 && !userAssigned){
                    assignPokemonToWinner(opSelPkmn, curSelPkmn.trainer.id);
                    appendPkmnLog('usrLog', "You've earned " + opSelPkmn.pokemon.name);
                }
                
                if(getAvailablePkmnCount(opPkmns) === 0){
                    updateVDTrainer({id: curTrainer.id, victory: 1, defeat: 0});
                    updateVDTrainer({id: opTrainer.id, victory: 0, defeat: 1});
                    evalPkmnLvlAndUpdateTrainer({trainer1: opTrainer,
                        trainer2: curTrainer,
                        tpkmns1: curPkmns,
                        tpkmns2: opPkmns
                    });
                    
                    if(getRandomInt(0, 1) === 1){
                        var item = assignRandPkmnItem(curTrainer.id);
                        
                        if(item !== null){
                            appendPkmnLog('usrLog', "You earned '" + item.name + "'");
                        }
                    }
                    
                    var msg = "You have won the battle";
                    if(opTrainer.id_gym !== null){
                        var b = getFirstBadgeByGym(opTrainer.id_gym);
                        if(b !== null && assignBadge(curTrainer, b)){
                            msg += ", and have earned " + b.name + "!";
                        }
                    }
                    
                    alert(msg);
                    if(!pkmnIsWild(opSelPkmn)){
                        $('.b_battle_restart').show();
                        $("#b_attack").prop("disabled", true);
                    } else {
                        window.location = 'main.php';
                    }
                } else {
                    opSelPkmn = getFirstAvailablePkmn(opPkmns);
                    var moveData = selectRandElement(opSelPkmn.moves);
                    $("#b_move_op").val(moveData.id);

                    appendPkmnLog('opLog', 'Changed to '+opSelPkmn.pokemon.name);
                    updateSelPkmn('opPkmn', opSelPkmn);
                }
            }
        } else {
            var moveData = selectRandElement(opSelPkmn.moves);
            $("#b_move_op").val(moveData.id); 
        }
        
        var selItem = $("#b_item_usr").val();
        if(selItem != 0){
			var trnItem = getTrnItemFromArr(curItems, selItem);
            var item = trnItem.item;
            var stat = item.stat;
			
            if (item.category.id != 8 && (stat !== null && stat.name !== 'HP')) {
                deleteTrnItemFromArr(curItems, selItem);
                updateItemsCBox("b_item_usr", curItems);
                $("#b_item_usr").val(0);
            }
        }
    });
    
    $(".b_pkmn_choices tr[id^='pkmn_'] .b_pkmn_choice").click(function(){
        var idAttr = $(this).parents('tr').prop("id");
        var id = idAttr.match(/pkmn_(.*)/);
        
        var tpkmn = findInArray(curPkmns, parseInt(id[1]));
        curSelPkmn = tpkmn;
        
        updateSelPkmn('usrPkmn', curSelPkmn);
        updateMovesPkmn('b_move_usr', curSelPkmn);
        resetPkmnLog('usrLog');
    });
    
    $('#b_item_usr').change(function(){
        var selItem = $(this).val();
        if (selItem != 0) {
            var trnItem = getTrnItemFromArr(curItems, selItem);
            var item = trnItem.item;
            var stat = item.stat;
            
            //Item is an evolutionary stone or health related item
            if (item.category.id == 8 || (stat !== null && stat.name === 'HP')) {
                $('#b_item_use').removeClass("b_item_use_h")
                        .addClass("b_item_use");
            } else {
                $('#b_item_use').removeClass("b_item_use")
                        .addClass("b_item_use_h");
            }
        }
    });
    
    $('#b_item_use').click(function(){
        var selItem = $("#b_item_usr").val();
        var trnItem = getTrnItemFromArr(curItems, selItem);
        var item = trnItem.item;
        var stat = item.stat;
        
        //Item is a health related one
        if(stat !== null && stat.name === 'HP'){
            curSelPkmn.cur_hp += item.value;
            if(curSelPkmn.cur_hp > curSelPkmn.hp){
                curSelPkmn.cur_hp = curSelPkmn.hp;
            }
            
            updateSelPkmn('usrPkmn', curSelPkmn);
            updatePkmnInList('curEqpPkmn', curSelPkmn);
            updateInArray(curPkmns, curSelPkmn.id, curSelPkmn);
            deleteTrnItemFromArr(curItems, selItem);
            updateItemsCBox("b_item_usr", curItems);
            $("#b_item_usr").val(0);
            
            $(this).removeClass("b_item_use")
                    .addClass("b_item_use_h");
            
            updateHPTrainerPkmn(curSelPkmn);
        } else if(item.category.id == 8) {
            //Evolutionary stone
            var cpkmn = curSelPkmn.pokemon;
            var epkmn = tpkmnEvolveIfReady(selItem, curSelPkmn);
            
            if(epkmn.id != cpkmn.id){
                curSelPkmn.pokemon = epkmn;
                appendPkmnLog('usrLog', cpkmn.name + " has evolved to "+epkmn.name+"!");
                
                updateSelPkmn('usrPkmn', curSelPkmn);
                updatePkmnInList('curEqpPkmn', curSelPkmn);
                updateInArray(curPkmns, curSelPkmn.id, curSelPkmn);
                
                deleteTrainerItem(trnItem);
                deleteTrnItemFromArr(curItems, selItem);
                updateItemsCBox("b_item_usr", curItems);
            } else {
                appendPkmnLog('usrLog', item.name + " doesn't work in "+cpkmn.name);
            }
        }
    });
	
    $('#restart').click(function(){
        if(!pkmnIsWild(opSelPkmn)){
            window.location.reload();
        }
    });
});