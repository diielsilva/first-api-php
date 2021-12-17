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

    public function getAllClients()
    {
        $connection = Connection::openConnection();
        $sql = "SELECT * FROM clients";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        Connection::closeConnection($connection);
        return $result;
    }

    public function deleteClient($id)
    {
        $connection = Connection::openConnection();
        $sql = "DELETE FROM clients WHERE id = ? LIMIT 1";
        $statement = $connection->prepare($sql);
        $statement->bindValue(1, $id);
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
    }

    public function updateClient($client)
    {
        $connection = Connection::openConnection();
        $sql = "UPDATE clients SET name = ?, phone = ?, updated_at = ? WHERE id = ? LIMIT 1";
        $statement = $connection->prepare($sql);
        $statement->bindValue(1, $client->getName());
        $statement->bindValue(2, $client->getPhone());
        $statement->bindValue(3, $client->getUpdatedAt());
        $statement->bindValue(4, $client->getId());
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
    }
}
