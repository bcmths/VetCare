<?php
// Inclua o arquivo de conexão com o banco de dados
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $pacienteId = $_POST['id'];

        // Query para excluir o paciente com o ID fornecido
        $delete_query = "DELETE FROM tb_paciente WHERE id = :id";
        $stmt = $pdo->prepare($delete_query);

        if ($stmt->execute(['id' => $pacienteId])) {
            echo 'success'; // A exclusão foi bem-sucedida
        } else {
            echo 'error'; // A exclusão falhou
        }
    }
}