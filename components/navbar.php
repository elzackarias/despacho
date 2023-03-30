<?php
function navbar_notlogged(){
    //Obtiene el nombre de la pagina
    $actual = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
?>
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center justify-content-between">

            <h1 class="logo"><a href="index.php">Despacho</a></h1>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto <?= $actual == "index.php" ? 'active' : '' ?>" href="index.php">Inicio</a></li>
                    <li><a class="nav-link scrollto <?= $actual == "about.php" ? 'active' : '' ?>" href="about.php">Acerca</a></li>
                    <li><a class="nav-link scrollto <?= $actual == "services.php" ? 'active' : '' ?>" href="services.php">Servicios</a></li>
                    <li><a class="nav-link scrollto <?= $actual == "gallery.php" ? 'active' : '' ?>" href="gallery.php">Galería</a></li>
                    <li><a class="nav-link scrollto <?= $actual == "contact.php" ? 'active' : '' ?>" href="contact.php">Contacto</a></li>
                    <li><a class="getstarted scrollto" style="justify-content:center !important;" href="login.php">Ingresar</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header>
<?php
}
function navbar_logged(){
    $actual = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
?>
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center justify-content-between">

            <h1 class="logo"><a href="index.php">Despacho</a></h1>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto <?= $actual == "home.php" ? 'active' : '' ?>" href="index.php">Inicio</a></li>
                    <li><a class="nav-link scrollto <?= $actual == "about.php" ? 'active' : '' ?>" href="about.php">Acerca</a></li>
                    <li><a class="nav-link scrollto <?= $actual == "services.php" ? 'active' : '' ?>" href="services.php">Servicios</a></li>
                    <li><a class="nav-link scrollto <?= $actual == "gallery.php" ? 'active' : '' ?>" href="gallery.php">Galería</a></li>
                    <li><a class="nav-link scrollto <?= $actual == "contact.php" ? 'active' : '' ?>" href="contact.php">Contacto</a></li>
                    <li><a class="getstarted scrollto" style="justify-content:center !important;" href="logout.php">Cerrar sesión</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header>
<?php
}
?>