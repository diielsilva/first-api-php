<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/project-manager/config/autoload.php";

class Client
{
    private $id;
    private $name;
    private $phone;
    private $createdAt;
    private $updatedAt;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function storeClient($client)
    {
        try {
            $connection = Connection::openConnection();
            $sql = "INSERT INTO clients VALUES (?,?,?,?,?)";
            $statement = $connection->prepare($sql);
            $statement->bindValue(1, null);
            $statement->bindValue(2, $client->getName());
            $statement->bindValue(3, $client->getPhone());
            $statement->bindValue(4, $client->getCreatedAt());
            $statement->bindValue(5, $client->getUpdatedAt());
            $connection->beginTransaction();
            $statement->execute();
            $rowCount = $statement->rowCount();
            if ($rowCount != 1) {
                $connection->rollBack();
            } else {
                $connection->commit();
            }
            Connection::closeConnection($connection);
            return $rowCount;
        } catch (PDOException $error) {
            echo $error->getMessage();
            die;
        }
    }
}
