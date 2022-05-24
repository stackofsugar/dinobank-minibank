<?php
include "./../views_templates/header.php";

use Components\Fortify\Auth;
use Components\Fortify\EncryptedStore as ES;
use Components\Reminisce\Session;
use Components\Shepherd\Http;
use Components\Shepherd\Request;

$session = Session::getInstance();
$post = Request::capture();
$DBStore = ES::getInstance();

$hasSuccess = false;
$hasError = false;
$error = "";

if ($post->hasPost()) {
    $usernameTo = $DBStore->getUsernameByAccNo($post->accNumber);
    $amount = $post->tfAmount;
    if (!is_numeric($amount)) {
        $amount == 0;
    } else {
        $amount = intval($amount);
        if ($amount < 0) {
            $amount == 0;
        }
    }
    if ($usernameTo == null) {
        $hasError = true;
        $error = "Nomor rekening tujuan salah!";
    } else {
        if (!Auth::transfer($usernameTo, $amount)) {
            $hasError = true;
            $error = "Saldo anda tidak mencukupi!";
        } else {
            $hasSuccess = true;
        }
    }
}
?>

<div class="container">
    <div class="container d-flex flex-column align-items-center">
        <div class="fs-1 mb-4 text-center">Setor Tunai</div>
        <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-9 col-sm-10 col-12">
            <?php if ($hasError) : ?>
                <div class="alert alert-danger">
                    <h5 class="alert-heading">Transfer Gagal!</h5>
                    <div><?= $error; ?></div>
                </div>
            <?php endif; ?>
            <?php if ($hasSuccess) : ?>
                <div class="alert alert-success">
                    <h5 class="alert-heading">Transfer Berhasil!</h5>
                </div>
            <?php endif; ?>
            <form action="/transfer" method="post">
                <div class="mb-3">
                    <label for="accNumber" class="form-label">Nomor Rekening Tujuan</label>
                    <input type="number" class="form-control" id="accNumber" name="accNumber">
                </div>
                <div class="mb-3">
                    <label for="tfAmount" class="form-label">Jumlah Transfer</label>
                    <input type="number" class="form-control" id="tfAmount" name="tfAmount">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "./../views_templates/footer.php"; ?>