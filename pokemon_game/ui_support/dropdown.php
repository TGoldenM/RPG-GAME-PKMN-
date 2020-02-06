<?php

function genSelectOption($value, $label, $selected = false){
    if (!$selected) {
        echo "<option value='$value'>$label</option>";
    } else {
        echo "<option value='$value' selected>$label</option>";
    }
}

function genSelectOptionsArr($arr, $valSel = null, $arrIsLinear = true){
    if ($arrIsLinear) {
        foreach ($arr as $v => $lbl) {
            genSelectOption($v, $lbl, $valSel == $v);
        }
    } else {
        foreach($arr as $gr => $vls){
            echo "<optgroup label='$gr'>";
            foreach($vls as $v){
                genSelectOption($v[0], $v[1], $valSel == $v[0]);
            }
            echo "</optgroup>";
        }
    }
}

/**
 * Outputs a set of option tags (optgroup tags optional) using a database table.
 * 
 * 
 * @global PDO $db database connection
 * @param string $table Table used
 * @param string $value Field used to fill value attribute
 * @param string $label Text content used for option tag
 * @param string $valSel Selected value according to $value field
 * @param string $group The field used to group the option tags, outputting optgroup tags.
 * @param string $conditions A where clause used with table query
 * @param string $orderBy An order by clause
 * @param string $rows A maximum number of results
 */
function genSelectOptions($table, $value, $label, $valSel = null
        , $group = null, $conditions = null, $orderBy = null, $rows = null){
    global $db;
    
    $q = "select $value, $label";

    if(!is_null($group)){
        $q .= ", $group";
    }
    
    $q .= " from $table";
    
    if(!is_null($conditions)){
        $q .= " where ";
        $q .= $conditions;
    }
    
    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    if ($stmt) {
        if (!is_null($group)) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if(!isset($values[$row[$group]])){
                    $values[$row[$group]] = array(array($row[$value], $row[$label]));
                } else {
                    $values[$row[$group]][] = array($row[$value], $row[$label]);
                }
            }
            genSelectOptionsArr($values, $valSel, false);
        } else {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                genSelectOption($row[$value], $row[$label], $valSel == $row[$value]);
            }
        }
    }
}

?>