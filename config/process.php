<?php

    session_start();

    include_once("connection.php");
    include_once("url.php");

    $data = $_POST;

    // MODIFICAÇÕES NO BANCO
    if(!empty($data)) {

        // CRIAR CONTATO
        if($data["type"] === "create") {

            $name = $data["name"];
            $phone = $data["phone"];
            $email = $data["email"];
            $observations = $data["observations"];

            $query = "INSERT INTO contacts (name, phone, email, observations) VALUES (:name, :phone, :email, :observations)";

            $stmt = $conn->prepare($query);

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":observations", $observations);

            try {

                $stmt->execute();
                $_SESSION["msg"] = "Contato criado com sucesso!";

            } catch(PDOException $e) {
                // erro na conexão
                $error = $e->getMessage();
                echo "Erro: $error";
            }

        } else if($data["type"] === "edit") {

            $name = $data["name"];
            $phone = $data["phone"];
            $email = $data["email"];
            $observations = $data["observations"];
            $id = $data["id"];

            $query = "UPDATE contacts SET name = :name, phone = :phone, email = :email, observations = :observations WHERE id = :id";

            $stmt = $conn->prepare($query);

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":observations", $observations);
            $stmt->bindParam(":id", $id);

            try {

                $stmt->execute();
                $_SESSION["msg"] = "Contato atualizado com sucesso!";

            } catch(PDOException $e) {
                // erro na conexão
                $error = $e->getMessage();
                echo "Erro: $error";
            }

        } else if($data["type"] === "delete") {

            $id = $data["id"];

            $query = "DELETE FROM contacts WHERE id = :id";

            $stmt = $conn->prepare($query);

            $stmt->bindParam(":id", $id);

            try {

                $stmt->execute();
                $_SESSION["msg"] = "Contato removido com sucesso!";

            } catch(PDOException $e) {
                // erro na conexão
                $error = $e->getMessage();
                echo "Erro: $error";
            }

        }

        // Redirect HOME
        header("Location:" . $BASE_URL . "/../index.php");

    // SELEÇÃO DE DADOS
    } else {

        $id;

        if(!empty($_GET)) {
            $id = $_GET["id"];
        }
    
        // RETORNA O DADO DE UM CONTATO
        if(!empty($id)) {
    
            $query = "SELECT * FROM contacts WHERE id = :id";
    
            $stmt = $conn->prepare($query);
    
            $stmt->bindParam(":id", $id);
    
            $stmt->execute();
    
            $contact = $stmt->fetch();
    
        } else {
            // RETORNA TODOS OS CONTATOS
            $contacts = [];
    
            $query = "SELECT * FROM contacts";
    
            $stmt = $conn->prepare($query);
    
            $stmt->execute();
    
            $contacts = $stmt->fetchAll();
        }

    }

    // FECHAR CONEXÃO
    $conn = null;
