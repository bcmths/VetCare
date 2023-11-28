<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'conexao.php';

$pacientes_query = "SELECT * FROM tb_paciente";
$pacientes_result = $pdo->query($pacientes_query);

$tutores_query = "SELECT * FROM tb_tutor";
$tutores_result = $pdo->query($tutores_query);

$prontuarios_query = "SELECT * FROM tb_prontuario";
$prontuarios_result = $pdo->query($prontuarios_query);

$pacientes_query = "SELECT tb_vet.tx_nome, COUNT(DISTINCT tb_paciente.id) as total_pacientes
FROM tb_paciente
INNER JOIN tb_vet ON tb_paciente.vet_id = tb_vet.id
GROUP BY tb_vet.tx_nome";
$pacientes_result = $pdo->query($pacientes_query);
$pacientes_data = [];

while ($row = $pacientes_result->fetch(PDO::FETCH_ASSOC)) {
    $pacientes_data[$row['tx_nome']] = (int) $row['total_pacientes'];
}

$count_veterinarians_query = "SELECT COUNT(*) AS total_veterinarians FROM tb_vet";
$stmt = $pdo->query($count_veterinarians_query);
$count_result = $stmt->fetch();
$total_veterinarians = $count_result['total_veterinarians'];

$count_patients_query = "SELECT COUNT(*) AS total_patients FROM tb_paciente";
$stmt = $pdo->query($count_patients_query);
$count_result = $stmt->fetch();
$total_patients = $count_result['total_patients'];

$count_prontuarios_query = "SELECT COUNT(DISTINCT paciente_id) AS total_prontuarios FROM tb_prontuario";
$stmt = $pdo->query($count_prontuarios_query);
$count_prontuarios_result = $stmt->fetch();
$total_prontuarios = $count_prontuarios_result['total_prontuarios'];

if ($total_patients > 0) {

    $all_patients_have_prontuarios = ($total_prontuarios == $total_patients);

    if ($all_patients_have_prontuarios) {
        $percent_complete = 100;
    } else {
        $percent_complete = ($total_prontuarios / $total_patients) * 100;
    }
} else {
    $percent_complete = 0;
}

$animais_query = "SELECT tb_paciente.tx_animal, COUNT(*) as total_animais
FROM tb_paciente
WHERE tb_paciente.tx_animal IN ('Gato', 'Cachorro')
GROUP BY tb_paciente.tx_animal";
$animais_result = $pdo->query($animais_query);
$animais_data = [];

while ($row = $animais_result->fetch(PDO::FETCH_ASSOC)) {
    $animais_data[$row['tx_animal']] = (int) $row['total_animais'];
}


echo '<script>';
echo 'var animaisData = ' . json_encode($animais_data) . ';';
echo 'var pacientesData = ' . json_encode($pacientes_data) . ';';
echo '</script>';
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>VetCare - Dashboard</title>

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
                <a class="nav-link usuarios-link" href="#" onclick="#modalSenhaMaster">
                    <i class="fa fa-users"></i>
                    <span>Usuários</span>
                </a>

            </li>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script>
            $(document).ready(function() {
                $(".usuarios-link").click(function() {
                    $("#modalSenhaMaster").modal("show");
                });
            });
            </script>

            <script>
            function verificarSenhaMaster() {

                $("#modalSenhaMaster").modal("show");

                var senhaMasterDigitada = document.getElementById("senhaMasterInput").value;

                $.ajax({
                    type: 'POST',
                    url: 'verificar_senha_master.php',
                    data: {
                        verificar_senha_master: true,
                        senha_master: senhaMasterDigitada
                    },
                    success: function(data) {
                        if (data === 'success') {

                            window.location.href = 'usuarios.php';
                        } else {

                            alert("Senha Master incorreta. Tente novamente.");
                        }
                    },
                    error: function() {
                        console.error('Erro na solicitação AJAX.');
                    }
                });
            }
            </script>

            <div class="modal fade" id="modalSenhaMaster" tabindex="-1" role="dialog"
                aria-labelledby="modalSenhaMasterLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalSenhaMasterLabel">Digite a senha de administrador</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="password" id="senhaMasterInput" class="form-control" placeholder="Senha">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary"
                                onclick="verificarSenhaMaster()">Acessar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

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
                            options);

                        dateElement.textContent = `Hoje é ${formattedDate}.`;
                    }

                    updateDate();
                    setInterval(updateDate, 1000);
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
                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Veterinários
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $total_veterinarians; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Pacientes
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $total_patients; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-cat fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Prontuários
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                        <?php echo number_format($percent_complete, 2); ?>%
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: <?php echo $percent_complete; ?>%"
                                                            aria-valuenow="<?php echo $percent_complete; ?>"
                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pie Chart -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3  align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Pacientes por Veterinário
                            </h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="myPieChart"></canvas>
                            </div>
                        </div>
                        <style>
                        .chart-pie {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }

                        #myPieChart {
                            margin: 0 auto;
                        }
                        </style>

                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                        var pacientesData = <?php echo json_encode($pacientes_data); ?>;

                        var ctx = document.getElementById('myPieChart').getContext('2d');
                        var myPieChart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: Object.keys(pacientesData),
                                datasets: [{
                                    data: Object.values(pacientesData),
                                    backgroundColor: [
                                        '#4e73df',
                                        '#1cc88a',
                                        '#36b9cc',
                                        '#d4e765',
                                        '#f6c23e',
                                        '#e74a3b',
                                        '#4e9a5e',
                                        '#9b59b6',
                                        '#3498db',
                                        '#e67e22',
                                    ],

                                }],
                                options: {
                                    responsive: true,
                                    legend: {
                                        display: false
                                    }
                                }
                            },

                        });
                        </script>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3  align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Quantidade de Cachorros e Gatos
                            </h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-bar pt-4 pb-2">
                                <canvas id="myBarChart"></canvas>
                            </div>
                        </div>
                        <style>
                        .chart-bar {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }

                        #myBarChart {
                            width: 100%;
                            height: auto;
                            /* ou a altura desejada */
                        }
                        </style>

                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                        var animaisData = <?php echo json_encode($animais_data); ?>;
                        console.log(animaisData)
                        console.log('oi')

                        var ctx = document.getElementById('myBarChart').getContext('2d');
                        var labels = Object.keys(animaisData);

                        var backgroundColors = [
                            '#4e73df',
                            '#1cc88a',
                            '#36b9cc',
                            '#d4e765',
                            '#f6c23e',
                            '#e74a3b',
                            '#4e9a5e',
                            '#9b59b6',
                            '#3498db',
                            '#e67e22',
                        ];
                        let data = {
                            labels: ['Animais'],
                            datasets: []
                        };

                        Object.values(animaisData).forEach(function(valor, i) {
                            data.datasets.push({
                                label: labels[i],
                                stack: 'Stack ' + i,
                                data: [valor],
                                backgroundColor: [
                                    backgroundColors[i]
                                ],
                                borderColor: [
                                    backgroundColors[i]
                                ],
                            });
                        });

                        var options = {
                            type: 'bar',
                            data: data,
                            options: {
                                responsive: true,
                                legend: {
                                    display: false
                                }
                            }
                        };
                        new Chart(ctx, options);
                        </script>
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
                    <a class="btn btn-primary" href="logout.php">Sair</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datata bles/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
</body>

</html>