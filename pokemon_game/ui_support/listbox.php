<?php

function createDbListBox($id_dest, $id_src, $table, $value, $label
         , $btnSubmit, $sel = array(), $style = 'list_box', $orderBy = null){
    global $db;

    $q = "select $value, $label from $table";

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($res) {
        $d_lbls = array();
        $s_lbls = array();
        
        foreach ($res as $v){
            if(in_array($v[$value], $sel)){
                $d_lbls[$v[$value]] = $v[$label];
            } else {
                $s_lbls[$v[$value]] = $v[$label];
            }
        }
        
        echo "<input type='hidden' id='$id_dest' name='$id_dest' value=''/>";
        echo "<table class='$style'>";
        
        echo "<tr><td class='elements'><select class='dest' id='lst_$id_dest' name='lst_".$id_dest."[]' multiple>";
        foreach ($d_lbls as $k => $lbl) {
            echo "<option value='$k'>$lbl</option>";
        }
        echo "</select></td>";
        
        echo "<td class='actions'>";
        echo "<input type='button' class='move_left' onclick=\"listbox_moveacross('$id_src', 'lst_$id_dest')\" value='&lt;&lt;' />";
        echo "<br/>";
        echo "<input type='button' class='move_right' onclick=\"listbox_moveacross('lst_$id_dest', '$id_src')\" value='&gt;&gt;'/>";
        echo "</td>";
        
        echo "<td class='elements'><select class='src' id='$id_src' name='".$id_src."[]' multiple>";
        foreach ($s_lbls as $k => $lbl) {
            echo "<option value='$k'>$lbl</option>";
        }
        echo "</select></td>";
        
        echo "</table>";
        
        echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#$btnSubmit').click(function(){
                        var sel = document.getElementById('lst_$id_dest');
                        var values = '';

                        for(var i=0; i < sel.options.length; i++){
                            if(i == 0)
                                values = '' + sel.options[i].value;
                            else
                                values += ',' + sel.options[i].value;
                        }

                        $('#$id_dest').val(values);
                    });
                });
             </script>";
    }
}

?>
