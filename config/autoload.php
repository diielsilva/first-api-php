<?php

spl_autoload_register(function ($className) {
    try {
        if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/project-manager/models/" . $className . ".php")) {
            require_once $_SERVER["DOCUMENT_ROOT"] . "/project-manager/models/" . $className . ".php";
        } else if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/project-manager/services/" . $className . ".php")) {
            require_once $_SERVER["DOCUMENT_ROOT"] . "/project-manager/services/" . $className . ".php";
        }
    } catch (Exception $error) {
        echo $error->getMessage();
        die;
    }
});
