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
    $vet_id = $_GET['delete'];
    // Execute a consulta SQL para excluir o veterinário com base no ID
    $delete_query = "DELETE FROM tb_vet WHERE id = :id";
    $stmt = $pdo->prepare($delete_query);
    $stmt->execute(['id' => $vet_id]);
}

// Consulta para recuperar informações da tabela tb_vet
$vet_query = "SELECT * FROM tb_vet";
$vet_result = $pdo->query($vet_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciar Veterinários</title>
</head>
<body>
    <h2>Gerenciar Veterinários</h2>

    <!-- Tabela para exibir os veterinários -->
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Gênero</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($vet = $vet_result->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $vet['tx_nome']; ?></td>
                    <td><?php echo $vet['tx_genero']; ?></td>
                    <td>
                        <a href="editar_veterinario.php?id=<?php echo $vet['id']; ?>">Editar</a>
                        <a href="veterinario.php?delete=<?php echo $vet['id']; ?>">Excluir</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>

    <!-- Botão para adicionar um novo veterinário -->
    <a href="adicionar_veterinario.php">Adicionar Veterinário</a>
    <br>

    <!-- Botão para voltar ao dashboard -->
    <a href="../dashboard.php">Voltar para o Dashboard</a>
</body>
</html>
