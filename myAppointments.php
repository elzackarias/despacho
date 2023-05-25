<?php
date_default_timezone_set("America/Mexico_City");
session_start();
include 'components/navbar.php';
include 'components/footer.php';
include 'lib/funcs.php';
if (!isset($_SESSION['uid'])) {
    header("Location: login.php?prev=home");
}
if (!verify($_SESSION['uid'])) {
    header("Location: onboard.php");
}
$data = getData($_SESSION['uid']);
$appoint = getMyAppointments($_SESSION['uid']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Mis Citas</title>
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
    <script>
        async function cancelar(pid) {
            const tk = '<?= base64_encode($_SESSION['tk']) ?>';
            const uid = '<?= base64_encode($_SESSION['uid']) ?>';
            Swal.fire({
                icon: 'question',
                title: '¿Estás seguro de cancelar tu cita?',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then(async (result) => {
                const settings = {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        pid,
                        uid,
                        tk
                    })
                };
                if (result.isConfirmed) {
                    try {
                        const q = await fetch('/api/index.php?type=borrar&que=appointment', settings);
                        const res = await q.json();
                        if (res.status == "EXITO") {
                            Swal.fire({
                                title: 'Exito!',
                                text: res.msg,
                                icon: 'success',
                                allowOutsideClick: false,
                                timer: 1500, // Duración de la alerta en milisegundos (2 segundos)
                                showConfirmButton: false
                            }).then(function() {
                                window.location.replace("https://2104-187-191-42-160.ngrok-free.app/myAppointments.php");
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: res.msg,
                                icon: 'error',
                                showConfirmButton: true
                            })
                        }
                    } catch (error) {
                        Swal.fire({
                                title: 'Error!',
                                text: error,
                                icon: 'error',
                                showConfirmButton: true
                            })
                    }
                }
            })
        }
        async function edit(pid){
            const tk = '<?= base64_encode($_SESSION['tk']) ?>';
            const uid = '<?= base64_encode($_SESSION['uid']) ?>';
            Swal.fire({
                icon: 'question',
                title: '¿Estás seguro de cancelar tu cita?',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then(async (result) => {
                const settings = {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        pid,
                        uid,
                        tk
                    })
                };
                if (result.isConfirmed) {
                    try {
                        const q = await fetch('/api/index.php?type=borrar&que=appointment', settings);
                        const res = await q.json();
                        if (res.status == "EXITO") {
                            Swal.fire({
                                title: 'Exito!',
                                text: res.msg,
                                icon: 'success',
                                allowOutsideClick: false,
                                timer: 1500, // Duración de la alerta en milisegundos (2 segundos)
                                showConfirmButton: false
                            }).then(function() {
                                window.location.replace("https://2104-187-191-42-160.ngrok-free.app/myAppointments.php");
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: res.msg,
                                icon: 'error',
                                showConfirmButton: true
                            })
                        }
                    } catch (error) {
                        Swal.fire({
                                title: 'Error!',
                                text: error,
                                icon: 'error',
                                showConfirmButton: true
                            })
                    }
                }
            })
        }
    </script>
</head>

<body>
    <?= navbar_logged(); ?>
    <div id="loader"></div>
    <section id="contact" class="contact section-bg">
        <div class="container">
            <div class="section-title mt-2 pb-0">
                <h2 style="text-transform:none !important;font-weight:500;margin-bottom: 0px;">Citas de <b><?= $data['firstname'] ?></b></h2>
            </div>
            <div class="row justify-content-center mt-3">
                <?php
                if(count($appoint) == 0){
                    echo '<center><h2>No tienes citas :(</h2></center>';
                }
                for ($i = 0; $i < count($appoint); $i++) {
                    $fecha = strtotime($appoint[$i]['start']);
                ?>
                    <div class="card mb-2 ms-2" style="width: 19.5rem;">
                        <div class="card-body">
                            <h5 class="card-title"><b>Cita #<?= $appoint[$i]['id'] ?></b></h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary" style="font-family: Open Sans, sans-serif !important;"><b>Fecha:</b> <?= strftime('%e/%m/%Y a las %H:00 pm', $fecha) ?></h6>
                            <p class="card-text mb-1"><b>Asunto:</b> <?= $appoint[$i]['subject'] ?></p>
                            <p class="card-text"><b>Modalidad:</b> <?= $appoint[$i]['mode'] == 'onsite' ? 'Presencial' : 'En línea' ?></p>
                            <button type="button" onclick="cancelar('<?= base64_encode($appoint[$i]['id']) ?>')" class="btn btn-secondary">Cancelar cita</button>
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