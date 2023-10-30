<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once('../conexao.php');

// Consulta para recuperar informações dos pacientes
$pacientes_query = "SELECT p.*, t.tx_nome AS tutor_nome, v.tx_nome AS vet_nome
                   FROM tb_paciente p
                   LEFT JOIN tb_tutor t ON p.tutor_id = t.id
                   LEFT JOIN tb_vet v ON p.vet_id = v.id";

$pacientes_result = $pdo->query($pacientes_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pacientes</title>
</head>
<body>
    <h2>Gerenciar Pacientes</h2>

    <!-- Botão para adicionar um novo paciente -->
    <a href="adicionar_paciente.php">Adicionar Paciente</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Animal</th>
            <th>Raça</th>
            <th>Tutor</th>
            <th>Veterinário</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $pacientes_result->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['tx_nome']; ?></td>
                <td><?php echo $row['tx_animal']; ?></td>
                <td><?php echo $row['tx_raca']; ?></td>
                <td><?php echo $row['tutor_nome']; ?></td>
                <td><?php echo $row['vet_nome']; ?></td>
                <td>
                    <a href="editar_paciente.php?id=<?php echo $row['id']; ?>">Editar</a>
                    <a href="excluir_paciente.php?id=<?php echo $row['id']; ?>">Excluir</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="../dashboard.php">Voltar para o Dashboard</a>
</body>
</html>
