<?php
include "./../views_templates/header.php";

use Components\Fortify\Auth;
use Components\Reminisce\Session;
use Components\Shepherd\Http;
use Components\Shepherd\Request;

$session = Session::getInstance();
$post = Request::capture();

$hasInsufficientError = false;

if ($post->hasPost()) {
    $withdrawValue = $post->withdraw;
    if (!is_numeric($withdrawValue)) {
        $withdrawValue = 0;
    } else {
        $withdrawValue = intval($withdrawValue);
        if ($withdrawValue < 0) {
            $withdrawValue = 0;
        }
        $withdrawValue = -$withdrawValue;
    }
    if (Auth::getBalance() < -$withdrawValue) {
        $hasInsufficientError = true;
    } else {
        Auth::modifyBalance($withdrawValue);
        Http::redirect("/balance");
    }
}
?>

<div class="container">
    <div class="container d-flex flex-column align-items-center">
        <div class="fs-1 mb-4 text-center">Tarik Tunai</div>
        <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-9 col-sm-10 col-12">
            <?php if ($hasInsufficientError) : ?>
                <div class="alert alert-danger">
                    <h5 class="alert-heading">Kesalahan Penarikan!</h5>
                    <div>Saldo anda tidak mencukupi!</div>
                </div>
            <?php endif; ?>
            <form action="/withdraw" method="post">
                <div class="mb-3">
                    <label for="withdraw" class="form-label">Jumlah Tarik</label>
                    <input type="number" class="form-control" id="withdraw" name="withdraw">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "./../views_templates/footer.php"; ?>