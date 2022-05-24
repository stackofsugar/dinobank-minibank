<?php
include "./../views_templates/header.php";

use Components\Fortify\Auth;
use Components\Reminisce\Session;
use Components\Shepherd\Http;
use Components\Shepherd\Request;

$session = Session::getInstance();
$post = Request::capture();

if ($post->hasPost()) {
    $depositValue = $post->deposit;
    if (!is_numeric($depositValue)) {
        $depositValue = 0;
    } else {
        $depositValue = intval($depositValue);
        if ($depositValue < 0) {
            $depositValue = 0;
        }
    }
    Auth::modifyBalance($depositValue);
    Http::redirect("/balance");
}
?>

<div class="container">
    <div class="container d-flex flex-column align-items-center">
        <div class="fs-1 mb-4 text-center">Setor Tunai</div>
        <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-9 col-sm-10 col-12">
            <form action="/deposit" method="post">
                <div class="mb-3">
                    <label for="deposit" class="form-label">Jumlah Setoran</label>
                    <input type="number" class="form-control" id="deposit" name="deposit">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "./../views_templates/footer.php"; ?>