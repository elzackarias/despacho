<?php
date_default_timezone_set("America/Mexico_City");
session_start();
include 'lib/config.php';
include 'components/navbar.php';
include 'components/footer.php';
include 'lib/funcs.php';
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
}
if (!verify($_SESSION['uid'])) {
    header("Location: onboard.php");
}

$data = getData($_SESSION['uid']);
if($data['role'] != '1'){
    header("Location: home.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Usuarios</title>
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/vendor/sweetalert2/sweetalert2.css">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <?= navbar_logged(); ?>
    <div id="loader"></div>
    <section id="contact" class="contact section-bg">
        <div class="container">
            <div class="section-title mt-2 pb-0">
                <h2 style="text-transform:none !important;font-weight:500;margin-bottom: 0px;">Usuarios:</h2>
            </div>
            <div class="row justify-content-center mt-3">
                <?php
                $q = mysqli_query($connect,"SELECT * FROM users");
                while ($datos = mysqli_fetch_assoc($q)) {
                ?>
                    <div class="card mb-2 ms-2" style="width: 19.5rem;">
                        <div class="card-body">
                            <h5 class="card-title"><b><?= $datos['firstname'].' '.$datos['lastname'] ?></b></h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary" style="font-family: Open Sans, sans-serif !important;">ID #<?= $datos['id'] ?></h6>
                            <p class="card-text mb-1">Email: <b><?= $datos['email'] ?></b> </p>
                            <p class="card-text mb-1">Cel: <b><?= $datos['cellphone'] ?></b> </p>
                            <p class="card-text mb-1">Tel: <b><?= $datos['telephone'] ?></b> </p>
                            <p class="card-text mb-1">User: <b><?= $datos['user'] ?></b> </p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <?= footer(); ?>
    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/sweetalert2/sweetalert2.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>