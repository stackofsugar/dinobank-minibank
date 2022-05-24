<?php

namespace Public;

use Components\Fortify\Auth;
use Components\Shepherd\Http;
use Components\Reminisce\Session;

include "./../views_templates/header.php";
require("../Components/autoload.php");

$session = Session::getInstance()
?>

<div class="container">
    <div class="text-center fs-1 mb-3">
        Halo, <?= $session->user["fullname"]; ?>
    </div>
    <div class="d-flex flex-column align-items-center">
        <a href="/balance" class="btn btn-primary mb-2">Cek Saldo</a>
        <a href="/transfer" class="btn btn-primary mb-2">Transfer Sesama DinoBank</a>
        <a href="/withdraw" class="btn btn-primary mb-2">Tarik Tunai</a>
        <a href="/deposit" class="btn btn-primary mb-3">Setor Tunai</a>
        <a href="/logout" class="btn btn-danger">Keluar</a>
    </div>
</div>

<?php include "./../views_templates/footer.php" ?>