<?php

abstract class Connection
{

    public static function openConnection()
    {
        try {
            $connection = new PDO("mysql:host=localhost;dbname=api_projects", "root", "");
            return $connection;
        } catch (PDOException $error) {
            echo $error->getMessage();
            die;
        }
    }

    public static function closeConnection($connection)
    {
        $connection = null;
    }
}
