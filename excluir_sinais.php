<?php

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $sinais_id = $_POST['id'];

        $delete_query = "DELETE FROM tb_sinaisclinicos WHERE id = :id";
        $stmt = $pdo->prepare($delete_query);

        if ($stmt->execute(['id' => $sinais_id])) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}