<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Incluir o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Função para atualizar um paciente
function atualizarPaciente($pdo, $paciente_id, $nome, $animal, $raca, $tutor_id, $vet_id)
{
    $update_query = "UPDATE tb_paciente
                    SET tx_nome = :nome, tx_animal = :animal, tx_raca = :raca, tutor_id = :tutor_id, vet_id = :vet_id
                    WHERE id = :id";
    $stmt = $pdo->prepare($update_query);
    return $stmt->execute([
        'nome' => $nome,
        'animal' => $animal,
        'raca' => $raca,
        'tutor_id' => $tutor_id,
        'vet_id' => $vet_id,
        'id' => $paciente_id,
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paciente_id = $_POST['id'];
    $nome = $_POST['nome'];
    $animal = $_POST['animal'];
    $raca = $_POST['raca'];
    $tutor_id = $_POST['tutor_id'];
    $vet_id = $_POST['vet_id'];

    // Chamar a função para atualizar os dados do paciente
    if (atualizarPaciente($pdo, $paciente_id, $nome, $animal, $raca, $tutor_id, $vet_id)) {
        // Atualização bem-sucedida
        // Você pode adicionar feedback ao usuário aqui, se necessário
    } else {
        // Atualização falhou
        // Lógica de tratamento de erro aqui
    }
}

// Consulta para recuperar informações de pacientes
$pacientes_query = "SELECT p.id, p.tx_nome, p.tx_animal, p.tx_raca, p.tutor_id, p.vet_id, t.tx_nome as tx_tutor, v.tx_nome as tx_veterinario
    FROM tb_paciente p
    LEFT JOIN tb_tutor t ON p.tutor_id = t.id
    LEFT JOIN tb_vet v ON p.vet_id = v.id";
$pacientes_result = $pdo->query($pacientes_query);
$pacientes_data = [];

while ($row = $pacientes_result->fetch(PDO::FETCH_ASSOC)) {
    $pacientes_data[] = $row;
}
// Consulta para recuperar informações dos tutores
$tutores_query = "SELECT id, tx_nome FROM tb_tutor";
$tutores_result = $pdo->query($tutores_query);
$tutores_data = [];
while ($row = $tutores_result->fetch(PDO::FETCH_ASSOC)) {
    $tutores_data[] = $row;
}

// Consulta para recuperar informações dos veterinários
$veterinarios_query = "SELECT id, tx_nome FROM tb_vet";
$veterinarios_result = $pdo->query($veterinarios_query);
$veterinarios_data = [];
while ($row = $veterinarios_result->fetch(PDO::FETCH_ASSOC)) {
    $veterinarios_data[] = $row;
}

// Inserir os dados dos pacientes, tutores e veterinários no HTML como objetos JavaScript
echo '<script>var pacientesData = ' . json_encode($pacientes_data) . ';</script>';
echo '<script>var tutoresData = ' . json_encode($tutores_data) . ';</script>';
echo '<script>var veterinariosData = ' . json_encode($veterinarios_data) . ';</script>';
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>SB Admin 2 - Dashboard</title>

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
                    <i class="fas fa-laugh-wink"></i>
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
                <a class="nav-link" href="charts.php">
                    <i class="fa fa-user-md"></i>
                    <span>Veterinários</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="pacientes.php">
                    <i class="fa fa-dog"></i>
                    <span>Pacientes</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fa fa-paw"></i>
                    <span>Tutores</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fa fa-list"></i>
                    <span>Sinais Clínicos</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fa fa-file-medical"></i>
                    <span>Prontuários</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tables.html">
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
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2" />
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

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
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Tutores</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Tutores
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Animal</th>
                                            <th>Raça</th>
                                            <th>Tutor</th>
                                            <th>Veterinário</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pacientes_data as $paciente): ?>
                                        <tr>
                                            <td><?php echo $paciente['id']; ?></td>
                                            <td contenteditable="true" class="editable-cell" data-field="tx_nome">
                                                <?php echo $paciente['tx_nome']; ?>
                                            </td>
                                            <td contenteditable="true" class="editable-cell" data-field="tx_animal">
                                                <?php echo $paciente['tx_animal']; ?>
                                            </td>
                                            <td contenteditable="true" class="editable-cell" data-field="tx_raca">
                                                <?php echo $paciente['tx_raca']; ?>
                                            </td>
                                            <td>
                                                <select class="tutor-select" data-field="tutor_id">
                                                    <?php foreach ($tutores_data as $tutor): ?>
                                                    <option value="<?php echo $tutor['id']; ?>"
                                                        <?php if ($tutor['id'] == $paciente['tutor_id']) echo "selected"; ?>>
                                                        <?php echo $tutor['tx_nome']; ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="veterinario-select" data-field="vet_id">
                                                    <?php foreach ($veterinarios_data as $veterinario): ?>
                                                    <option value="<?php echo $veterinario['id']; ?>"
                                                        <?php if ($veterinario['id'] == $paciente['vet_id']) echo "selected"; ?>>
                                                        <?php echo $veterinario['tx_nome']; ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary save-btn"
                                                    data-paciente-id="<?php echo $paciente['id']; ?>">
                                                    Salvar
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

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
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Select "Logout" below if you are ready to end your current session.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        Cancel
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
    // Adicionar um ouvinte de eventos para o botão "Salvar"
    const saveButtons = document.querySelectorAll('.save-btn');
    saveButtons.forEach((button) => {
        button.addEventListener('click', (event) => {
            const pacienteId = event.target.getAttribute('data-paciente-id');
            const row = event.target.closest('tr');
            const nome = row.querySelector('[data-field="tx_nome"]').textContent.trim();
            const animal = row.querySelector('[data-field="tx_animal"]').textContent.trim();
            const raca = row.querySelector('[data-field="tx_raca"]').textContent.trim();
            const tutorSelect = row.querySelector('.tutor-select');
            const tutorId = tutorSelect.options[tutorSelect.selectedIndex].value;
            const veterinarioSelect = row.querySelector('.veterinario-select');
            const veterinarioId = veterinarioSelect.options[veterinarioSelect.selectedIndex].value;

            // Enviar os dados atualizados para o servidor usando uma solicitação AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'pacientes.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    // Atualização bem-sucedida
                    console.log('Paciente atualizado com sucesso.');
                    // Adicionar feedback ao usuário, se necessário
                } else {
                    // Atualização falhou
                    console.error('Falha na atualização do paciente.');
                    // Adicionar tratamento de erro, se necessário
                }
            };

            const data =
                `id=${pacienteId}&nome=${nome}&animal=${animal}&raca=${raca}&tutor_id=${tutorId}&vet_id=${veterinarioId}`;
            xhr.send(data);
        });
    });
    </script>
</body>

</html>