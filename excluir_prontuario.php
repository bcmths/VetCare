<?php

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $prontuario_id = $_POST['id'];

        $delete_query = "DELETE FROM tb_prontuario WHERE id = :id";

        $stmt = $pdo->prepare($delete_query);

        if ($stmt->execute(['id' => $prontuario_id])) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
} else {

    http_response_code(405);
    echo 'Method Not Allowed';
}
?>