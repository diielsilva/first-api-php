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
            $sanitizedInputs = $this->sanitizeInsertClientInputs($jsonDecoded);
            $hasNull = $this->hasInputNull($sanitizedInputs);

            if ($hasNull) {
                $this->resultJson = [
                    "error" => "Inputs inválidos, tente novamente!"
                ];
            } else {
                $client = new Client();
                $client->setName($sanitizedInputs[0]);
                $client->setPhone($sanitizedInputs[1]);
                $client->setCreatedAt($sanitizedInputs[2]);
                $client->setUpdatedAt($sanitizedInputs[3]);
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

    public function sanitizeInsertClientInputs($jsonDecoded)
    {
        $name = filter_var($jsonDecoded["name"], FILTER_SANITIZE_STRING);
        $phone = filter_var($jsonDecoded["phone"], FILTER_SANITIZE_STRING);
        $createdAt = filter_var($jsonDecoded["createdAt"], FILTER_SANITIZE_STRING);
        $updatedAt = filter_var($jsonDecoded["updatedAt"], FILTER_SANITIZE_STRING);
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

    public function sanitizeDeleteClient($inputs)
    {
        $id = filter_var($inputs["id"], FILTER_SANITIZE_STRING);
        $sanitizedInputs = [0 => $id];
        return $sanitizedInputs;
    }

    public function deleteClient()
    {
        $this->getResponseHeaders();

        if ($_SERVER["REQUEST_METHOD"] != "DELETE") {
            $this->resultJson = [
                "error" => "Método não permitido!"
            ];
        } else {
            $jsonDecoded = json_decode(file_get_contents("php://input"), true);
            $sanitizedInputs = $this->sanitizeDeleteClient($jsonDecoded);
            $hasNull = $this->hasInputNull($sanitizedInputs);

            if ($hasNull) {
                $this->resultJson = [
                    "error" => "Inputs inválidos, tente novamente!"
                ];
            } else {
                $client = new Client();
                $result = $client->deleteClient($sanitizedInputs[0]);

                if ($result != 1) {
                    $this->resultJson = [
                        "error" => "Cliente não encontrado!"
                    ];
                } else {
                    $this->resultJson = [
                        "message" => "Cliente removido com sucesso!"
                    ];
                }
            }
        }
        $this->getResponse();
    }

    public function updateClient()
    {
        $this->getResponseHeaders();

        if ($_SERVER["REQUEST_METHOD"] != "PUT") {
            $this->resultJson = [
                "error" => "Método não permitido!"
            ];
        } else {
            $jsonDecoded = json_decode(file_get_contents("php://input"), true);
            $sanitizedInputs = $this->sanitizeUpdateClientInputs($jsonDecoded);
            $hasNull = $this->hasInputNull($sanitizedInputs);

            if ($hasNull) {
                $this->resultJson = [
                    "error" => "Inputs inválidos, tente novamente!"
                ];
            } else {
                $client = new Client();
                $client->setId($sanitizedInputs[0]);
                $client->setName($sanitizedInputs[1]);
                $client->setPhone($sanitizedInputs[2]);
                $client->setUpdatedAt($sanitizedInputs[3]);
                $rowCount = $client->updateClient($client);

                if ($rowCount != 1) {
                    $this->resultJson = [
                        "error" => "Algo deu errado, tente novamente!"
                    ];
                } else {
                    $this->resultJson = [
                        "message" => "Cliente editado com sucesso!"
                    ];
                }
            }
        }

        $this->getResponse();
    }

    public function sanitizeUpdateClientInputs($jsonDecoded)
    {
        $id = filter_var($jsonDecoded["id"], FILTER_SANITIZE_STRING);
        $name = filter_var($jsonDecoded["name"], FILTER_SANITIZE_STRING);
        $phone = filter_var($jsonDecoded["phone"], FILTER_SANITIZE_STRING);
        $updatedAt = filter_var($jsonDecoded["updatedAt"], FILTER_SANITIZE_STRING);
        $sanitizedInputs = [0 => $id, 1 => $name, 2 => $phone, 3 => $updatedAt];
        return $sanitizedInputs;
    }
}
