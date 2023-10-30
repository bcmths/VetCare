<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once('../conexao.php');

// Tratamento para exclusão
if (isset($_GET['delete'])) {
    $sinal_id = $_GET['delete'];
    // Execute a consulta SQL para excluir o sinal clínico com base no ID
    $delete_query = "DELETE FROM tb_sinaisclinicos WHERE id = :id";
    $stmt = $pdo->prepare($delete_query);
    $stmt->execute(['id' => $sinal_id]);
}

// Consulta para recuperar informações da tabela tb_sinaisclinicos, incluindo os animais associados
$sinais_query = "SELECT tb_sinaisclinicos.id, tb_sinaisclinicos.tx_descricao, tb_paciente.tx_nome AS tx_paciente_nome
                FROM tb_sinaisclinicos
                LEFT JOIN tb_paciente ON tb_sinaisclinicos.paciente_id = tb_paciente.id";
$sinais_result = $pdo->query($sinais_query);

// Consulta para recuperar informações de todos os pacientes
$pacientes_query = "SELECT id, tx_nome FROM tb_paciente";
$pacientes_result = $pdo->query($pacientes_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciar Sinais Clínicos</title>
</head>
<body>
    <h2>Gerenciar Sinais Clínicos</h2>

    <!-- Tabela para exibir os sinais clínicos -->
    <table>
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Animal</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($sinal = $sinais_result->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $sinal['tx_descricao']; ?></td>
                    <td><?php echo $sinal['tx_paciente_nome'] ?? 'Nenhum'; ?></td>
                    <td>
                        <a href="editar_sinalclinico.php?id=<?php echo $sinal['id']; ?>">Editar</a>
                        <a href="sinaisclinicos.php?delete=<?php echo $sinal['id']; ?>">Excluir</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>

    <!-- Botão para adicionar um novo sinal clínico -->
    <a href="adicionar_sinalclinico.php">Adicionar Sinal Clínico</a>
    <br>

    <!-- Botão para voltar ao dashboard -->
    <a href="../dashboard.php">Voltar para o Dashboard</a>

</body>
</html>
