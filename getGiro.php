<?php

include_once "_core.php";
if (!isset($_POST['searchTerm'])) {
    $q="SELECT * FROM giroMH LIMIT 5";
    $r= _query($q);
} else {
    $query = $_POST['searchTerm'];
    $r= buscarDesc($query, 'codigo');

    if (!$r) {
        $r= buscarDesc($query, 'descripcion');
    }
}
$data = array();

while ($row = _fetch_array($r)) {
    $data[] = array("id"=>$row['codigo'], "text"=>$row['descripcion']);
}
echo json_encode($data);


function buscarDesc($query, $field)
{
    $sql = "SELECT codigo, descripcion
					FROM giroMH
					WHERE $field LIKE '%$query%'";

    $result = _query($sql);
    $numrows= _num_rows($result);
    if ($numrows>0) {
        return $result;
    } else {
        return false;
    }
}
