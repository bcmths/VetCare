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

$pacientes_query = "SELECT tx_nome as nome_veterinario, COUNT(*) as total_pacientes FROM tb_paciente
GROUP BY nome_veterinario";
$pacientes_result = $pdo->query($pacientes_query);
$pacientes_data = [];

while ($row = $pacientes_result->fetch(PDO::FETCH_ASSOC)) {
    $pacientes_data[$row['nome_veterinario']] = (int) $row['total_pacientes'];
}

// Inserir os dados dos pacientes no HTML como um objeto JavaScript
echo '<script>var pacientesData = ' . json_encode($pacientes_data) . ';</script>';

$count_veterinarians_query = "SELECT COUNT(*) AS total_veterinarians FROM tb_vet";
$stmt = $pdo->query($count_veterinarians_query);
$count_result = $stmt->fetch();

// Obtém o total de veterinários
$total_veterinarians = $count_result['total_veterinarians'];

$count_patients_query = "SELECT COUNT(*) AS total_patients FROM tb_paciente";
$stmt = $pdo->query($count_patients_query);
$count_result = $stmt->fetch();

// Obtém o total de pacientes
$total_patients = $count_result['total_patients'];

$count_prontuarios_query = "SELECT COUNT(*) AS total_prontuarios FROM tb_prontuario";
$stmt = $pdo->query($count_prontuarios_query);
$count_prontuarios_result = $stmt->fetch();

// Total de prontuários
$total_prontuarios = $count_prontuarios_result['total_prontuarios'];

// Calcular a porcentagem de conclusão
if ($total_patients > 0 && $total_prontuarios > 0) {
    $percent_complete = ($total_prontuarios / $total_patients) * 100;
} else {
    $percent_complete = 0; // Evita divisão por zero
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>SB Admin 2 - Charts</title>

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
                <a class="nav-link" href="tutores.php">
                    <i class="fa fa-dog"></i>
                    <span>Pacientes</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tutores.php">
                    <i class="fa fa-paw"></i>
                    <span>Tutores</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tutores.php">
                    <i class="fa fa-list"></i>
                    <span>Sinais Clínicos</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tutores.php">
                    <i class="fa fa-file-medical"></i>
                    <span>Prontuários</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tutores.php">
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
                    <h1 class="h3 mb-2 text-gray-800">Charts</h1>
                    <p class="mb-4">
                        Chart.js is a third party plugin that is used to generate the
                        charts in this theme. The charts below have been customized - for
                        further customization options, please visit the
                        <a target="_blank" href="https://www.chartjs.org/docs/latest/">official Chart.js
                            documentation</a>.
                    </p>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-8 col-lg-7">
                            <!-- Area Chart -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Area Chart
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                    <hr />
                                    Styling for the area chart can be found in the
                                    <code>/js/demo/chart-area-demo.js</code> file.
                                </div>
                            </div>

                            <!-- Bar Chart -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Bar Chart</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-bar">
                                        <canvas id="myBarChart"></canvas>
                                    </div>
                                    <hr />
                                    Styling for the bar chart can be found in the
                                    <code>/js/demo/chart-bar-demo.js</code> file.
                                </div>
                            </div>
                        </div>

                        <!-- Donut Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Donut Chart
                                    </h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <hr />
                                    Styling for the donut chart can be found in the
                                    <code>/js/demo/chart-pie-demo.js</code> file.
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
                    <a class="btn btn-primary" href="login.php">Logout</a>
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
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script src="js/demo/chart-bar-demo.js"></script>
</body>

</html>