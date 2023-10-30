<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once('../conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $animal = $_POST['animal'];
    $raca = $_POST['raca'];
    $tutor_id = $_POST['tutor_id'];
    $vet_id = $_POST['vet_id'];

    // Execute a consulta SQL para adicionar um novo paciente
    $insert_query = "INSERT INTO tb_paciente (tx_nome, tx_animal, tx_raca, tutor_id, vet_id)
                    VALUES (:nome, :animal, :raca, :tutor_id, :vet_id)";
    $stmt = $pdo->prepare($insert_query);
    $stmt->execute(['nome' => $nome, 'animal' => $animal, 'raca' => $raca, 'tutor_id' => $tutor_id, 'vet_id' => $vet_id]);

    // Redirecionar de volta para a página de gerenciamento de pacientes
    header("Location: paciente.php");
    exit;
}

// Buscar as opções disponíveis de tutores
$tutores_query = "SELECT id, tx_nome FROM tb_tutor";
$tutores_result = $pdo->query($tutores_query);

// Buscar as opções disponíveis de veterinários
$vets_query = "SELECT id, tx_nome FROM tb_vet";
$vets_result = $pdo->query($vets_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Paciente</title>
</head>
<body>
    <h2>Adicionar Paciente</h2>

    <form method="post" action="adicionar_paciente.php">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>
        <label for="animal">Animal:</label>
        <input type="text" name="animal" required>
        <label for="raca">Raça:</label>
        <input type="text" name="raca" required>

        <!-- Selecionar o tutor do paciente -->
        <label for="tutor_id">Tutor:</label>
        <select name="tutor_id" required>
            <?php
            while ($row = $tutores_result->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $row['id'] . '">' . $row['tx_nome'] . '</option>';
            }
            ?>
        </select>

        <!-- Selecionar o veterinário do paciente -->
        <label for="vet_id">Veterinário:</label>
        <select name="vet_id" required>
            <?php
            while ($row = $vets_result->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $row['id'] . '">' . $row['tx_nome'] . '</option>';
            }
            ?>
        </select>

        <input type="submit" value="Adicionar Paciente">
    </form>

    <a href="paciente.php">Voltar para a lista de pacientes</a>
</body>
</html>
