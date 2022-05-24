<?php

namespace Public;

require("../Components/autoload.php");

use Components\Shepherd\Http;
use Components\Reminisce\Session;

$session = Session::getInstance();
$session->invalidateAndRedirect("/login");
