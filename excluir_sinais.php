<?php
// Inclua o arquivo de conexão com o banco de dados
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $sinais_id = $_POST['id'];

        // Query para excluir o paciente com o ID fornecido
        $delete_query = "DELETE FROM tb_sinaisclinicos WHERE id = :id";
        $stmt = $pdo->prepare($delete_query);

        if ($stmt->execute(['id' => $sinais_id])) {
            echo 'success'; // A exclusão foi bem-sucedida
        } else {
            echo 'error'; // A exclusão falhou
        }
    }
}