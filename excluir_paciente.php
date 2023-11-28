<?php

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $pacienteId = $_POST['id'];

        $delete_query = "DELETE FROM tb_paciente WHERE id = :id";
        $stmt = $pdo->prepare($delete_query);

        if ($stmt->execute(['id' => $pacienteId])) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}