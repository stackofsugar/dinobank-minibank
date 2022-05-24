<?php

namespace Public;

require("../Components/autoload.php");

use Components\Fortify\EncryptedStore as ES;

include "./../views_templates/header.php";

$DBCryptInstance = ES::getInstance();

echo "<pre>";
echo print_r($DBCryptInstance->getDBArray());
echo "</pre>";

?>

<?php include "./../views_templates/footer.php" ?>