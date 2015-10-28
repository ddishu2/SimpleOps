<?php
require_once 'open_sos.php';
require_once 'cl_DB.php';

$lo = new open_sos(null, null);
$db = new cl_DB();
echo json_encode($db->getResultsFromQuery($lo->getBaseQuery()));
?>
