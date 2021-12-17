<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/project-manager/config/autoload.php";

class ResponseService
{
    private $resultJson;


    public function getResponse()
    {
        echo json_encode($this->resultJson);
    }

    public function getResponseHeaders()
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    }

    public function storeClient()
    {
        $this->getResponseHeaders();

        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            $this->resultJson = [
                "error" => "Método não permitido!"
            ];
        } else {
            $jsonDecoded = json_decode(file_get_contents("php://input"), true);
            $client = new Client();
            $client->setName($jsonDecoded["name"]);
            $client->setPhone($jsonDecoded["phone"]);
            $client->setCreatedAt($jsonDecoded["createdAt"]);
            $client->setUpdatedAt($jsonDecoded["updatedAt"]);
            $result = $client->storeClient($client);
            
            if ($result != 1) {
                $this->resultJson = [
                    "error" => "Algo deu errado, tente novamente!"
                ];
            } else {
                $this->resultJson = [
                    "message" =>  "Cliente cadastrado com sucesso!",
                ];
            }
        }

        $this->getResponse();
    }
}
