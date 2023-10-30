<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once('../conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    $stmt->execute(['nome' => $nome, 'animal' => $animal, 'raca' => $raca, 'tutor_id' => $tutor_id, 'vet_id' => $vet_id, 'id' => $paciente_id]);

    // Redirecionar de volta para a página de gerenciamento de pacientes
    header("Location: paciente.php");
    exit;
}

// Verificar se foi fornecido um ID de paciente válido na URL
if (isset($_GET['id'])) {
    $paciente_id = $_GET['id'];
    $paciente_query = "SELECT p.*, t.tx_nome AS tutor_nome, v.tx_nome AS vet_nome
                      FROM tb_paciente p
                      LEFT JOIN tb_tutor t ON p.tutor_id = t.id
                      LEFT JOIN tb_vet v ON p.vet_id = v.id
                      WHERE p.id = :id";
    $stmt = $pdo->prepare($paciente_query);
    $stmt->execute(['id' => $paciente_id]);
    $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

    // Buscar as opções disponíveis de tutores
    $tutores_query = "SELECT id, tx_nome FROM tb_tutor";
    $tutores_result = $pdo->query($tutores_query);

    // Buscar as opções disponíveis de veterinários
    $vets_query = "SELECT id, tx_nome FROM tb_vet";
    $vets_result = $pdo->query($vets_query);
} else {
    // Se não houver ID válido, redirecione para a página de gerenciamento de pacientes
    header("Location: paciente.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Paciente</title>
</head>
<body>
    <h2>Editar Paciente</h2>

    <form method="post" action="editar_paciente.php">
        <input type="hidden" name="id" value="<?php echo $paciente['id']; ?>">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo $paciente['tx_nome']; ?>" required>
        <label for="animal">Animal:</label>
        <input type="text" name="animal" value="<?php echo $paciente['tx_animal']; ?>" required>
        <label for="raca">Raça:</label>
        <input type="text" name="raca" value="<?php echo $paciente['tx_raca']; ?>" required>
        
        <!-- Selecionar o tutor do paciente -->
        <label for="tutor_id">Tutor:</label>
        <select name="tutor_id" required>
            <?php
            while ($row = $tutores_result->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $row['id'] . '"';
                if ($row['id'] == $paciente['tutor_id']) {
                    echo ' selected';
                }
                echo '>' . $row['tx_nome'] . '</option>';
            }
            ?>
        </select>

        <!-- Selecionar o veterinário do paciente -->
        <label for="vet_id">Veterinário:</label>
        <select name="vet_id" required>
            <?php
            while ($row = $vets_result->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $row['id'] . '"';
                if ($row['id'] == $paciente['vet_id']) {
                    echo ' selected';
                }
                echo '>' . $row['tx_nome'] . '</option>';
            }
            ?>
        </select>

        <input type="submit" value="Salvar Alterações">
    </form>

    <a href="paciente.php">Voltar para a lista de pacientes</a>
</body>
</html>
