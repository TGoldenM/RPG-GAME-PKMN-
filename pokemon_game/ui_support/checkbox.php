<?php

/**
 * Creates a list of checkboxes. Uses style v_list by default.
 * @param string $name Attribute name to use for checkboxes
 * @param array $values Associative array (value=>label) used to set
 *                     checkbox attributes.
 * @param array $sel Checkboxes selected according to $values key
 */
function createChkboxList($name, $values, $sel = array(), $liStyle = 'v_list') {
    echo "<ul class='$liStyle'>";

    foreach ($values as $val => $label) {
        echo "<li><input name='" . $name . "[]' type='checkbox' value='$val'";

        if (in_array($val, $sel)) {
            echo ' checked ';
        }

        echo "/>&nbsp;$label</li>";
    }

    echo "</ul>";
}

/**
 * Creates a list of checkboxes. Uses style v_list by default and a database.
 * 
 * @global PDO $db Database connection
 * @param string $name Attribute name to use for checkboxes
 * @param string $table Database table
 * @param string $value Table field used to put values to checkboxes
 * @param string $label Table field used to put labels to checkboxes
 * @param array $sel Checkboxes selected according to $value field
 * @param string $liStyle List style
 * @param string $orderBy order by clause used
 * @param int $rows Number of rows to read from database table
 */
function createDbCheckboxList($name, $table, $value, $label, $sel = array(), $liStyle = 'v_list', $orderBy = null, $rows = null) {
    global $db;

    $q = "select $value, $label from $table";

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($res) {
        echo "<ul class='$liStyle'>";
        foreach ($res as $v) {
            echo "<li><input name='" . $name . "[]' type='checkbox' value='$v[$value]'";

            if (in_array($v[$value], $sel)) {
                echo ' checked ';
            }

            echo "/>&nbsp;$v[$label]</li>";
        }
        echo "</ul>";
    }
}

/**
 * Allows to know if a particular checkbox is checked using request data.
 * 
 * This function is expected to be used with a group of checkboxes with a
 * common name.
 * 
 * @param array $req_data Could be $_POST, $_GET, $_REQUEST or similar arrays
 * @param string $chkname Name of the checkbox
 * @param string $value Value to search for
 * @return boolean True if checked, false otherwise.
 */
function isChecked($req_data, $chkname, $value) {
    if (!empty($req_data[$chkname])) {
        foreach ($req_data[$chkname] as $chkval) {
            if ($chkval == $value) {
                return true;
            }
        }
    }
    return false;
}

?>