<?php

namespace Public;

include "./../views_templates/header.php";
require("../Components/autoload.php");

use Components\Shepherd\Request;
use Components\Reminisce\Session;
use Components\Fortify\Auth;
use Components\Shepherd\Http;

$post = Request::capture();
$session = Session::getInstance();

if ($post->hasPost()) {
    if (Auth::authenticate($post->username, $post->password)) {
        Http::redirect("home");
    }
}
?>

<div class="container d-flex flex-column align-items-center">
    <div class="fs-1 mb-4 text-center">Masuk Rekening <strong>DinoBank</strong></div>
    <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-9 col-sm-10 col-12">
        <?php if ($session->hasFlash()) : ?>
            <div class="alert alert-danger">
                <h5 class="alert-heading">Kesalahan Form!</h5>
                <div><?= $session->getFlash(); ?></div>
            </div>
        <?php endif; ?>
        <form class="row g-0" action="/login" method="post">
            <div class="mb-4">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= $post->old("username"); ?>" required autofocus>
                </div>
                <div class="">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <span class="input-group-text password-peek" onmousedown="mousedownPasswordPeek(this)" onmouseup="mouseupPasswordPeek(this)">
                            <i class="bi bi-eye-fill"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                Anda belum memiliki akun? Silakan <a class="link-light" href="register">daftar di sini.</a>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </div>
        </form>
    </div>
</div>

<?php include "./../views_templates/footer.php" ?>