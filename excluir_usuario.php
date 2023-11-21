<?php
// Inclua o arquivo de conexão com o banco de dados
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $usuario_id = $_POST['id'];

        $delete_query = "DELETE FROM tb_usuario WHERE id = :id";

        $stmt = $pdo->prepare($delete_query);

        if ($stmt->execute(['id' => $usuario_id])) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
} else {
    // Responda a outros tipos de solicitações, se necessário
    http_response_code(405); // Método não permitido
    echo 'Method Not Allowed';
}
?>