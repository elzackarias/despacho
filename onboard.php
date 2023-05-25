<?php
session_start();
include 'components/navbar.php';
include 'components/footer.php';
include 'lib/funcs.php';
if (!isset($_SESSION['uid'])) {
    header("Location: login.php?prev=home");
}
if (verify($_SESSION['uid'])) {
    header("Location: home.php");
}
$data = getData($_SESSION['uid']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Despacho Contable - Onboard</title>
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
    <section id="onboard" class="section-bg mt-2">
        <div class="container">
            <div class="section-title pb-0">
                <h2 style="text-transform:none !important;font-weight:500;">Hola <b><?= $data['firstname'] ?></b> :)</h2>
                <div class="alert alert-info" role="alert">
                    Antes de iniciar, por favor proporcione una dirección de su domicilio
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-9">
                    <form method="post" id="formaddress" autocomplete="off">
                        <input type="hidden" value="<?= base64_encode($_SESSION['uid']); ?>" name="uidhash" id="uidhash">
                        <input type="hidden" value="<?= $_SESSION['tk']; ?>" name="tkhash" id="tkhash">
                        <label class="text-right up-txt-form" for="street" class="form-label">Calle y Núm.</label><br>
                        <input class="form-control form-space" type="text" placeholder="Emiliano Zapata #2" name="street" id="street" required>
                        <label class="text-right up-txt-form" for="city" class="form-label">Ciudad</label><br>
                        <input class="form-control form-space" type="text" placeholder="Puebla" name="city" id="city" required>
                        <label class="text-right up-txt-form" for="colony" class="form-label">Colonia o Localidad</label><br>
                        <input class="form-control form-space" type="text" placeholder="La Paz" name="colony" id="colony" required>
                        <label class="text-right up-txt-form" for="zipcode" class="form-label">Código Postal</label><br>
                        <input class="form-control form-space" type="text" placeholder="72160" name="zipcode" id="zipcode" required>
                        <button type="submit" class="btn btn-primary btn-form mt-2" name="accept" id="btnaccept">Aceptar</button>
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
    <script src="assets/js/onboard.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>