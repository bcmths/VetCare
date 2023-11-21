<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Incluir o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Consulta para recuperar informações do banco de dados
// Aqui você pode criar consultas SQL para obter os dados das tabelas

// Exemplo: Consulta para recuperar informações de pacientes
$pacientes_query = "SELECT * FROM tb_paciente";
$pacientes_result = $pdo->query($pacientes_query);

// Exemplo: Consulta para recuperar informações de tutores
$tutores_query = "SELECT * FROM tb_tutor";
$tutores_result = $pdo->query($tutores_query);

// Exemplo: Consulta para recuperar informações de prontuários
$prontuarios_query = "SELECT * FROM tb_prontuario";
$prontuarios_result = $pdo->query($prontuarios_query);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard - Sistema Veterinário</title>
    <style>
        /* Estilos para a barra lateral */
        .sidenav {
            height: 100%;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #333;
            /* Cor de fundo da barra lateral */
            padding-top: 20px;
        }

        .sidenav a {
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            color: #fff;
            /* Cor do texto dos botões */
            display: block;
        }

        .sidenav a:hover {
            background-color: #4CAF50;
            /* Cor de fundo dos botões ao passar o mouse */
        }
    </style>
</head>

<body>
    <!-- Barra lateral -->
    <div class="sidenav">
        <a href="veterinarios.php">Veterinário</a>
        <a href="paciente/paciente.php">Paciente</a>
        <a href="tutor.php">Tutor</a>
        <a href="prontuario.php">Prontuário</a>
        <a href="sinais/sinaisclinicos.php">Sinais Clínicos</a>
        <a href="usuarios.php">Usuário</a>
    </div>

    <!-- Conteúdo principal -->
    <div style="margin-left: 200px; padding: 20px;">
        <h2>Dashboard - Sistema Veterinário</h2>

        <!-- Exibir mensagem de boas-vindas com o nome do veterinário -->
        <?php
        $usuario_id = $_SESSION['user_id'];
        $vet_query = "SELECT tb_vet.tx_nome, tb_vet.tx_genero FROM tb_vet
              JOIN tb_usuario ON tb_vet.id = tb_usuario.vet_id
              WHERE tb_usuario.id = :usuario_id";
        $stmt = $pdo->prepare($vet_query);
        $stmt->execute(['usuario_id' => $usuario_id]);
        $vet = $stmt->fetch();

        // Verificar o gênero do veterinário e adicionar o prefixo adequado
        if ($vet['tx_genero'] === 'Masculino') {
            $prefixo = 'Dr.';
        } elseif ($vet['tx_genero'] === 'Feminino') {
            $prefixo = 'Dra.';
        } else {
            $prefixo = '';
        }

        echo "<p>Olá, " . $prefixo . " " . $vet['tx_nome'] . "</p>";
        ?>

        <!-- Botão para deslogar -->
        <form method="post" action="logout.php">
            <input type="submit" value="Deslogar">
        </form>

        <!-- Exemplo de exibição de dados de pacientes -->
        <h3>Dados de Pacientes</h3>
        <ul>
            <?php
            while ($row = $pacientes_result->fetch()) {
                echo "<li>" . $row['tx_nome'] . " - Animal: " . $row['tx_animal'] . " - Raça: " . $row['tx_raca'] . "</li>";
            }
            ?>
        </ul>

        <!-- Exemplo de exibição de dados de tutores -->
        <h3>Dados de Tutores</h3>
        <ul>
            <?php
            while ($row = $tutores_result->fetch()) {
                echo "<li>" . $row['tx_nome'] . " - Email: " . $row['tx_email'] . " - Telefone: " . $row['nb_telefone'] . "</li>";
            }
            ?>
        </ul>

        <!-- Exemplo de exibição de dados de prontuários -->
        <h3>Dados de Prontuários</h3>
        <ul>
            <?php
            $prontuarios_query = "SELECT tb_prontuario.tx_obs, tb_paciente.tx_nome AS paciente_nome 
                         FROM tb_prontuario 
                         JOIN tb_paciente ON tb_prontuario.paciente_id = tb_paciente.id";

            $prontuarios_result = $pdo->query($prontuarios_query);

            while ($row = $prontuarios_result->fetch()) {
                echo "<li>Paciente: " . $row['paciente_nome'] . " - Observação: " . $row['tx_obs'] . "</li>";
            }
            ?>
        </ul>
</body>

</html>