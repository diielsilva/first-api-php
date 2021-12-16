<?php

spl_autoload_register(function ($className) {
    try {
        require_once $_SERVER["DOCUMENT_ROOT"] . "/project-manager/models/" . $className . ".php";
    } catch (Exception $error) {
        echo $error->getMessage();
        die;
    }
});
