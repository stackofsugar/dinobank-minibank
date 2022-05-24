<?php
include "./../views_templates/header.php";

use Components\Fortify\Auth;
use Components\Reminisce\Session;

$session = Session::getInstance();
?>

<div class="container">
    <div class="fs-2 mb-1">
        <?= $session->user["fullname"]; ?>
    </div>
    <div class="fs-3 mb-2 text-muted">
        <?= $session->user["account_no"]; ?>
    </div>
    <div class="fs-3">
        Rp <?= Auth::getBalance(); ?>
    </div>
</div>

<?php include "./../views_templates/footer.php"; ?>