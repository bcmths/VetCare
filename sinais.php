<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Incluir o arquivo de conexão com o banco de dados
require_once 'conexao.php';

function adicionarSinaisClinicos($pdo, $descricao, $paciente_id)
{
    $insert_query = "INSERT INTO tb_sinaisclinicos (tx_descricao, paciente_id)
                    VALUES (:descricao, :paciente_id)";
    $stmt = $pdo->prepare($insert_query);
    return $stmt->execute([
        'descricao' => $descricao,
        'paciente_id' => $paciente_id
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_sinaisclinicos'])) {
        $descricao = $_POST['tx_descricao'] ?? '';
        $paciente_id = $_POST['paciente_id'] ?? '';

        if (!empty($descricao) && !empty($paciente_id)) {
            if (adicionarSinaisClinicos($pdo, $descricao, $paciente_id)) {
                // Redirecionar de volta para a página de gerenciamento de sinais clínicos após a adição
                header("Location: sinaisclinicos.php");
                exit;
            } else {
                echo "Falha ao adicionar sinais clínicos.";
            }
        } else {
            echo "Por favor, preencha todos os campos obrigatórios.";
        }
    }
}

// Consulta para recuperar informações de tutores
$sinais_query = "SELECT id, tx_descricao, paciente_id FROM tb_sinaisclinicos";
$sinais_result = $pdo->query($sinais_query);

// Inserir os dados dos tutores no HTML como um objeto JavaScript
$sinais_data = [];

while ($row = $sinais_result->fetch(PDO::FETCH_ASSOC)) {
    $sinais_data[] = $row;
}

// Consulta para recuperar informações do veterinário
$usuario_id = $_SESSION['user_id'];
$vet_query = "SELECT tb_vet.tx_nome, tb_vet.tx_genero FROM tb_vet
    JOIN tb_usuario ON tb_vet.id = tb_usuario.vet_id
    WHERE tb_usuario.id = :usuario_id";
$stmt = $pdo->prepare($vet_query);
$stmt->execute(['usuario_id' => $usuario_id]);
$vet = $stmt->fetch();

if ($vet['tx_genero'] === 'Masculino') {
    $prefixo = 'Dr.';
} elseif ($vet['tx_genero'] === 'Feminino') {
    $prefixo = 'Dra.';
} else {
    $prefixo = '';
}

// Inserir os dados do veterinário no HTML como um objeto JavaScript
echo '<script>var sinaisData = ' . json_encode($sinais_data) . ';</script>';
echo '<script>var veterinarioData = ' . json_encode([
    'prefixo' => $prefixo,
    'nome' => $vet['tx_nome']
]) . ';</script>';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>VetCare - Sinais Clínicos</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fa fa-dog"></i>
                </div>
                <div class="sidebar-brand-text mx-3">VetCare</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" />

            <!-- Heading -->
            <div class="sidebar-heading">Categorias</div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="veterinarios.php">
                    <i class="fa fa-user-md"></i>
                    <span>Veterinários</span></a>
            </li>

            <!-- Nav Item - Tables -->

            <li class="nav-item">
                <a class="nav-link" href="tutores.php">
                    <i class="fa fa-paw"></i>
                    <span>Tutores</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="pacientes.php">
                    <i class="fa fa-dog"></i>
                    <span>Pacientes</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="sinais.php">
                    <i class="fa fa-list"></i>
                    <span>Sinais Clínicos</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="prontuarios.php">
                    <i class="fa fa-file-medical"></i>
                    <span>Prontuários</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="usuarios.php">
                    <i class="fa fa-users"></i>
                    <span>Usuários</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block" />

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <div id="dateDisplay"></div>

                    <script>
                    function updateDate() {
                        const dateElement = document.getElementById('dateDisplay');
                        const currentDate = new Date();
                        const options = {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
                        const formattedDate = currentDate.toLocaleDateString('pt-BR',
                            options); // Altere 'pt-BR' para o código de idioma desejado

                        dateElement.textContent = `Hoje é ${formattedDate}.`;
                    }

                    // Atualize a data automaticamente a cada segundo (ou conforme necessário)
                    updateDate(); // Chama a função para exibir a data inicial
                    setInterval(updateDate, 1000); // Atualiza a data a cada segundo
                    </script>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php
                                $usuario_id = $_SESSION['user_id'];
                                $vet_query = "SELECT tb_vet.tx_nome, tb_vet.tx_genero FROM tb_vet
                                            JOIN tb_usuario ON tb_vet.id = tb_usuario.vet_id
                                            WHERE tb_usuario.id = :usuario_id";
                                $stmt = $pdo->prepare($vet_query);
                                $stmt->execute(['usuario_id' => $usuario_id]);
                                $vet = $stmt->fetch();

                                if ($vet['tx_genero'] === 'Masculino') {
                                    $prefixo = 'Dr.';
                                } elseif ($vet['tx_genero'] === 'Feminino') {
                                    $prefixo = 'Dra.';
                                } else {
                                    $prefixo = '';
                                }
                                ?>
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $prefixo . " " . $vet['tx_nome']; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg" />
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="perfil.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Sair
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h3 class="m-0 font-weight-bold text-primary">
                                Sinais Clínicos
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Sinais Apresentados</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sinais_data as $sinais): ?>
                                        <tr>

                                            <td contenteditable="true" class="editable-cell" data-field="tx_nome">
                                                <?php
                                                    // Substitua $sinais['paciente_id'] pela variável que contém o paciente_id específico
                                                    $paciente_id = $sinais['paciente_id'];

                                                    // Consulta para obter o nome do paciente usando o paciente_id
                                                    $consulta_paciente = "SELECT tx_nome FROM tb_paciente WHERE id = :paciente_id";
                                                    $stmt_paciente = $pdo->prepare($consulta_paciente);
                                                    $stmt_paciente->execute(['paciente_id' => $paciente_id]);
                                                    $paciente = $stmt_paciente->fetch();

                                                    // Exibir o nome do paciente
                                                    echo $paciente['tx_nome'];
                                                    ?>
                                            </td>

                                            <td contenteditable="true" class="editable-cell" data-field="tx_descricao">
                                                <?php echo $sinais['tx_descricao']; ?>
                                            </td>

                                            <td>
                                                <button type="submit" class="btn btn-primary save-btn"
                                                    data-sinais-id="<?php echo $sinais['id']; ?>">
                                                    Salvar
                                                </button>
                                                <button class="btn btn-danger delete-btn"
                                                    data-sinais-id="<?php echo $sinais['id']; ?>">
                                                    Excluir
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>

                                </table>

                                <!-- Botão para abrir o modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalAddTutor">
                                    Adicionar Tutor
                                </button>
                                <!-- Modal para adicionar tutor -->
                                <div class="modal fade" id="modalAddTutor" tabindex="-1" role="dialog"
                                    aria-labelledby="modalAddTutorLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalAddTutorLabel">Adicionar Tutor</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" id="addTutorForm">
                                                    <div class="form-group">
                                                        <label for="nome">Nome:</label>
                                                        <input type="text" name="tx_nome" id="tx_nome"
                                                            class="form-control" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="email">E-mail:</label>
                                                        <input type="email" name="tx_email" id="tx_email"
                                                            class="form-control" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="telefone">Telefone:</label>
                                                        <input type="tel" name="nb_telefone" id="nb_telefone"
                                                            class="form-control" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="endereco">Endereço:</label>
                                                        <input type="text" name="tx_endereco" id="tx_endereco"
                                                            class="form-control" required>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary" name="add_tutor"
                                                        id="addTutorButton">Adicionar Tutor</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-secondary"
                                                    data-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; VetCare 2023</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Quer mesmo sair?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Clique em "Sair" se deseja encerrar sua sessão.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        Cancelar
                    </button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <script>
    $(document).ready(function() {
        // Adicione um evento de clique aos botões "Excluir"
        $('.delete-btn').click(function() {
            const usuario_id = $(this).data('usuario-id');

            // Confirmar com o usuário antes de excluir
            if (confirm('Tem certeza de que deseja excluir este usuário?')) {
                // Realizar uma solicitação AJAX para excluir o usuário
                $.ajax({
                    type: 'POST',
                    url: 'excluir_usuario.php', // Crie um arquivo para a exclusão dos usuários
                    data: {
                        id: usuario_id
                    },
                    success: function(data) {
                        // Verificar a resposta do servidor
                        if (data === 'success') {
                            // Exclusão bem-sucedida
                            console.log('Usuário excluído com sucesso.');
                            // Recarregue a página ou atualize a tabela para refletir a exclusão
                            location.reload();
                        } else {
                            // Exibir uma mensagem de erro se a exclusão falhar
                            console.error('Falha ao excluir usuário.');
                        }
                    }
                });
            }
        });
    });
    </script>

    <script>
    $(document).ready(function() {
        $('.save-btn').click(function() {
            var usuarioId = $(this).data('usuario-id');
            var row = $(this).closest('tr');
            var nomeUsuario = row.find('[data-field="tx_usuario"]').text().trim();
            var senha = row.find('[data-field="tx_senha"]').text().trim();
            var vetId = row.find('[data-field="vet_id"]').val().trim();

            // Realizar uma solicitação AJAX para atualizar o usuário
            $.ajax({
                type: 'POST',
                url: 'atualizar_usuario.php',
                data: {
                    id: usuarioId,
                    nomeUsuario: nomeUsuario,
                    senha: senha,
                    vetId: vetId
                },
                success: function(response) {
                    if (response === 'success') {
                        // Atualização bem-sucedida
                        console.log('Usuário atualizado com sucesso.');
                    } else {
                        // Atualização falhou
                        console.error('Falha na atualização do usuário.');
                    }
                },
                error: function() {
                    console.error('Erro na solicitação AJAX.');
                }
            });
        });
    });
    </script>
    <style>
    .table-responsive {
        overflow-x: hidden;
    }
    </style>

</body>

</html>