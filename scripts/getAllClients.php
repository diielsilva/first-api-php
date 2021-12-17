<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/project-manager/config/autoload.php";

$response = new ResponseService();
$response->getAllClients();