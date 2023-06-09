<?php
session_start();
include 'lib/config.php';
include 'components/navbar.php';
include 'components/footer.php';
/*if (isset($_SESSION['uid'])) {
    header('Location: home.php');
}*/
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Despacho Contable - Contacto</title>
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
    <?php echo !isset($_SESSION['uid']) ? navbar_notlogged() : navbar_logged(); ?>
    <section id="contact" class="contact section-bg">
        <div class="container">

            <div class="section-title">
                <h2>Contact</h2>
                <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
            </div>
            <div class="row mt-5">

                <div class="col-lg-4">
                    <div class="info">
                        <div class="address">
                            <i class="bi bi-geo-alt"></i>
                            <h4>Ubicación:</h4>
                            <p>Av. 11 Sur 1832 Puebla, PUE 72410</p>
                        </div>

                        <div class="email">
                            <i class="bi bi-envelope"></i>
                            <h4>Email:</h4>
                            <p>info@despacho.com</p>
                        </div>

                        <div class="phone">
                            <i class="bi bi-phone"></i>
                            <h4>Call:</h4>
                            <p>+52 222 222 2222</p>
                        </div>

                    </div>

                </div>

                <div class="col-lg-8 mt-5 mt-lg-0">
                    <form action="" method="post">
                        <div class="row gy-2 gx-md-3">
                            <div class="col-md-6 form-group">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Nombre" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                            </div>
                            <div class="form-group col-12">
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="Asunto" required>
                            </div>
                            <div class="form-group col-12">
                                <textarea class="form-control" name="message" rows="5" placeholder="Mensaje" required></textarea>
                                <p style="color:red;"><?= $errores ?></p>
                                <p style="color:green;margin-top: -10px;font-weight: 700;font-size: 20px;"><?= $_GET['msg'] ?></p>
                            </div>
                            <div class="text-center col-12"><input name="enviar" type="submit" value="Enviar"></div>
                        </div>
                    </form>
                    <?php
                    if (isset($_POST['enviar'])) {
                        // Procesar los datos del formulario
                        $nombre = htmlspecialchars(strip_tags($_POST['name']), ENT_QUOTES);
                        $email = htmlspecialchars(strip_tags($_POST['email']), ENT_QUOTES);
                        $subject = htmlspecialchars(strip_tags($_POST['subject']), ENT_QUOTES);
                        $message = htmlspecialchars(strip_tags($_POST['message']), ENT_QUOTES);
                        if(!empty($nombre) || !empty($email) || !empty($subject) || !empty($message)){
                            $q = mysqli_query($connect,"INSERT INTO messages (name,email,subject,message) VALUES ('$nombre','$email','$subject','$message')");
                            echo '<script>window.location.replace("https://2104-187-191-42-160.ngrok-free.app/contact.php?msg=Enviado");</script>';
                        }else{
                            $errores = "Rellena todos los campos";
                        }
                    }
                    ?>
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
    <!--<script src="assets/js/signup.js"></script>-->
    <script src="assets/js/main.js"></script>
</body>

</html>