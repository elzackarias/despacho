<?php
session_start();
include 'components/navbar.php';
include 'components/footer.php';
include 'components/alerts.php';
if (isset($_SESSION['uid'])) {
    header('Location: home.php');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Despacho Contable - Ingresar</title>
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/vendor/sweetalert2/sweetalert2.css">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <?= navbar_notlogged(); ?>
    <section class="d-flex align-items-center section-bg">
        <div class="container position-relative" style="padding-top: 5px !important;">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-9">
                    <?php
                    $_GET['prev'] == "home" ? info_alert("Ups!", 'Primero necesitas iniciar sesión o <a href="signup.php" class="alert-link">registrarte</a>') : null;
                    $_GET['prev'] == "logout" ? info_alert("Chaoo!", 'Has cerrado sesión correctamente') : null;
                    ?>
                    <form method="post" id="formlogin" autocomplete="off">
                        <h1 class="title-form">Ingresar</h1>
                        <label class="text-right up-txt-form" for="email" class="form-label">Email o usuario</label><br>
                        <input class="form-control form-space" type="text" placeholder="micorreo@ejemplo.com" name="email" id="email" required>
                        <label class="text-right up-txt-form" for="pinput" class="form-label">Contraseña</label><br>
                        <input class="form-control form-space" type="password" placeholder="•••••••" name="password" id="pwd" required>
                        <button type="submit" class="btn btn-primary btn-form mt-2" name="signup" id="btnlogin">Iniciar sesión</button>
                        <p class="text-center mt-1">¿Aún no tienes cuenta?, <a href="signup.php">Registrate</a></p>
                    </form>
                </div>
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
    <script src="assets/js/login.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>