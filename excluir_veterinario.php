<?php

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $veterinario_id = $_POST['id'];

        $delete_query = "DELETE FROM tb_vet WHERE id = :id";

        $stmt = $pdo->prepare($delete_query);

        if ($stmt->execute(['id' => $veterinario_id])) {
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