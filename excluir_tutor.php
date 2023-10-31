<?php
// Inclua o arquivo de conexão com o banco de dados
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $tutor_id = $_POST['id'];

        $delete_query = "DELETE FROM tb_tutor WHERE id = :id";

        $stmt = $pdo->prepare($delete_query);

        if ($stmt->execute(['id' => $tutor_id])) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}
?>