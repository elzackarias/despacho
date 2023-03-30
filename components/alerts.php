<?php
function info_alert(string $titulo,string $texto){
?>
    <div class="alert alert-info align-items-center alert-dismissible fade show" role="alert">
        <i class='bx bxs-info-circle'></i>
        <strong><?= $titulo ?></strong> <?= $texto ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>