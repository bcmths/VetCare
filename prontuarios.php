<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Incluir o arquivo de conexão com o banco de dados
require_once 'conexao.php';

function adicionarProntuario($pdo, $obs, $paciente_id)
{
    $insert_query = "INSERT INTO tb_prontuario (tx_obs, paciente_id) VALUES (:obs, :paciente_id)";
    $stmt = $pdo->prepare($insert_query);
    return $stmt->execute([
        'obs' => $obs,
        'paciente_id' => $paciente_id
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_prontuario'])) {
        $obs = $_POST['tx_obs'] ?? '';
        $paciente_id = $_POST['paciente_id'] ?? '';

        if (!empty($obs) && !empty($paciente_id)) {
            if (adicionarProntuario($pdo, $obs, $paciente_id)) {
                // Redirecionar de volta para a página de gerenciamento de prontuários após a adição
                header("Location: prontuarios.php");
                exit;
            } else {
                echo "Falha ao adicionar prontuário.";
            }
        } else {
            echo "Por favor, preencha todos os campos obrigatórios.";
        }
    }
}

// Consulta para recuperar informações de prontuários
$prontuarios_query = "SELECT id, tx_obs, paciente_id FROM tb_prontuario";
$prontuarios_result = $pdo->query($prontuarios_query);

// Inserir os dados dos prontuários no HTML como um objeto JavaScript
$prontuarios_data = [];

while ($row = $prontuarios_result->fetch(PDO::FETCH_ASSOC)) {
    $prontuarios_data[] = $row;
}

$pacientes_query = "SELECT p.id, p.tx_nome, p.tx_animal, p.tx_raca, p.tutor_id, p.vet_id, t.tx_nome as tx_tutor, v.tx_nome as tx_veterinario
    FROM tb_paciente p
    LEFT JOIN tb_tutor t ON p.tutor_id = t.id
    LEFT JOIN tb_vet v ON p.vet_id = v.id";
$pacientes_result = $pdo->query($pacientes_query);
$pacientes_data = [];

while ($row = $pacientes_result->fetch(PDO::FETCH_ASSOC)) {
    $pacientes_data[] = $row;
}

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

echo '<script>var prontuariosData = ' . json_encode($prontuarios_data) . ';</script>';
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

    <title>VetCare - Prontuários</title>

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
                                Tutores
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Paciente</th>
                                            <th>Observações</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($prontuarios_data as $prontuario): ?>
                                            <?php
                                            $paciente_id = $prontuario['paciente_id'];
                                            $paciente_query = "SELECT tx_nome FROM tb_paciente WHERE id = :paciente_id";
                                            $stmt = $pdo->prepare($paciente_query);
                                            $stmt->execute(['paciente_id' => $paciente_id]);
                                            $paciente = $stmt->fetch(PDO::FETCH_ASSOC);
                                            ?>
                                            <tr>
                                                <td contenteditable="true" class="editable-cell" data-field="paciente_id">
                                                    <?php echo $paciente['tx_nome']; ?>
                                                </td>
                                                <td contenteditable="true" class="editable-cell" data-field="tx_obs">
                                                    <?php echo $prontuario['tx_obs']; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary save-btn"
                                                        data-prontuario-id="<?php echo $prontuario['id']; ?>">
                                                        Salvar
                                                    </button>
                                                    <button type="button" class="btn btn-danger delete-btn"
                                                        data-prontuario-id="<?php echo $prontuario['id']; ?>">
                                                        Excluir
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>


                                <!-- Botão para abrir o modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalAddProntuario">
                                    Novo Prontuário
                                </button>
                                <!-- Modal para adicionar tutor -->
                                <div class="modal fade" id="modalAddProntuario" tabindex="-1" role="dialog"
                                    aria-labelledby="modalAddProntuarioLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalAddProntuarioLabel">Novo Prontuário
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" id="addProntuarioForm">
                                                    <div class="form-group">
                                                        <label for="obs">Observações:</label>
                                                        <textarea name="tx_obs" id="tx_obs" class="form-control"
                                                            rows="4" required></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="paciente_id">Paciente:</label>
                                                        <select name="paciente_id" id="paciente_id" class="form-control"
                                                            required>
                                                            <!-- Opção padrão vazia para indicar que o usuário deve selecionar -->
                                                            <option value="" disabled selected>Selecione um paciente
                                                            </option>

                                                            <!-- Loop através dos pacientes para gerar as opções do select -->
                                                            <?php foreach ($pacientes_data as $paciente): ?>
                                                                <option value="<?php echo $paciente['id']; ?>">
                                                                    <?php echo $paciente['tx_nome']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>


                                                    <button type="submit" class="btn btn-primary" name="add_prontuario"
                                                        id="addProntuarioButton">Salvar Prontuário</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
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
        $(document).ready(function () {
            $('.delete-btn').click(function () {
                const prontuario_id = $(this).data('prontuario-id');

                if (confirm('Tem certeza de que deseja excluir este prontuário?')) {
                    $.ajax({
                        type: 'POST',
                        url: 'excluir_prontuario.php',
                        data: {
                            id: prontuario_id
                        },
                        success: function (data) {
                            if (data === 'success') {
                                console.log('Prontuário excluído com sucesso.');
                                location.reload();
                            } else {
                                console.error('Falha ao excluir prontuário.');
                            }
                        }
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.save-btn').click(function () {
                var prontuarioId = $(this).data('prontuario-id');
                var row = $(this).closest('tr');
                var obsProntuario = row.find('[data-field="tx_obs"]').text().trim();
                var pacienteId = row.find('[data-field="paciente_id"]').val().trim();

                $.ajax({
                    type: 'POST',
                    url: 'atualizar_prontuario.php',
                    data: {
                        id: prontuarioId,
                        obs: obsProntuario,
                        paciente_id: pacienteId
                    },
                    success: function (response) {
                        if (response === 'success') {
                            console.log('Prontuário atualizado com sucesso.');
                        } else {
                            console.error('Falha na atualização do prontuário.');
                        }
                    },
                    error: function () {
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