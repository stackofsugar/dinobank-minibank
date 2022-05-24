<?php

namespace Public;

require("../Components/autoload.php");

use Components\Fortify\Auth;
use Components\Reminisce\Session;
use Components\Fortify\Str;
use Components\Shepherd\Http;

$session = Session::getInstance();

include "./../views_templates/header.php"
?>
<p>hai</p>
<pre>
    <?php
    $rand = random_int(10000000, 99999999);
    print_r($session->dump());
    print_r($_SESSION);
    print_r([
        "isGuest" => Auth::guest(),
    ]);
    ?>
</pre>
<a href="/logout">Logout</a>
<?php include "./../views_templates/footer.php" ?>