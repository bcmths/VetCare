<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Incluir o arquivo de conexão com o banco de dados
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recuperar os dados do paciente a ser atualizado
        $paciente_id = $_POST['id'];
        $nome = $_POST['nome'];
        $animal = $_POST['animal'];
        $raca = $_POST['raca'];
        $tutor_id = $_POST['tutor_id'];
        $vet_id = $_POST['vet_id'];

        // Execute a consulta SQL para atualizar os dados do paciente
        $update_query = "UPDATE tb_paciente
                        SET tx_nome = :nome, tx_animal = :animal, tx_raca = :raca, tutor_id = :tutor_id, vet_id = :vet_id
                        WHERE id = :id";
        $stmt = $pdo->prepare($update_query);
        $result = $stmt->execute([
            'nome' => $nome,
            'animal' => $animal,
            'raca' => $raca,
            'tutor_id' => $tutor_id,
            'vet_id' => $vet_id,
            'id' => $paciente_id,
        ]);

        if ($result) {
            // Redirecionar de volta para a página de gerenciamento de pacientes
            header("Location: pacientes.php");
            exit;
        } else {
            echo "Falha na atualização dos dados.";
        }
    } catch (PDOException $e) {
        echo "Erro no banco de dados: " . $e->getMessage();
    }
} else {
    header("Location: pacientes.php");
    exit;
}
?>