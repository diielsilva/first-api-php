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

            $sanitizedInputs = $this->sanitizeClientInputs($client);
            $hasNull = $this->hasInputNull($sanitizedInputs);

            if ($hasNull) {
                $this->resultJson = [
                    "error" => "Inputs inválidos, tente novamente!"
                ];
            } else {
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
        }

        $this->getResponse();
    }

    public function hasInputNull($inputs)
    {
        $hasNull = false;

        for ($index = 0; $index < count($inputs); $index++) {
            if ($inputs[$index] == null) {
                $hasNull = true;
            }
        }
        return $hasNull;
    }

    public function sanitizeClientInputs($client)
    {
        $name = filter_var($client->getName(), FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = filter_var($client->getPhone(), FILTER_SANITIZE_SPECIAL_CHARS);
        $createdAt = filter_var($client->getCreatedAt(), FILTER_SANITIZE_SPECIAL_CHARS);
        $updatedAt = filter_var($client->getUpdatedAt(), FILTER_SANITIZE_SPECIAL_CHARS);
        $sanitizedInputs = [0 => $name, 1 => $phone, 2 => $createdAt, 3 => $updatedAt];
        return $sanitizedInputs;
    }

    public function getAllClients()
    {
        $this->getResponseHeaders();

        if ($_SERVER["REQUEST_METHOD"] != "GET") {
            $this->resultJson = [
                "error" => "Método não permitido!"
            ];
        } else {

            $client = new Client();
            $allClients = $client->getAllClients();

            if (count($allClients) == 0) {
                $this->resultJson = [
                    "message" => "Nenhum cliente encontrado!"
                ];
            } else {
                $this->resultJson = [
                    "result" => []
                ];
                for ($index = 0; $index < count($allClients); $index++) {
                    $this->resultJson["result"][] = [
                        "id" => $allClients[$index]["id"],
                        "name" => $allClients[$index]["name"],
                        "phone" => $allClients[$index]["phone"],
                        "createdAt" => $allClients[$index]["created_at"],
                        "updatedAt" => $allClients[$index]["updated_at"]
                    ];
                }
            }
        }

        $this->getResponse();
    }
}
