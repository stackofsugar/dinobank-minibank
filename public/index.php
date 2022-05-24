<?php

namespace Public;

require("../Components/autoload.php");

use Components\Fortify\Auth;
use Components\Shepherd\Http;

$requestURI = $_SERVER["REQUEST_URI"];

const SRCDIR = "../src";

switch ($requestURI) {
    case "/":
    case "":
        Http::redirect("/login");
        break;
    case "/login":
        if (Auth::guest()) {
            require SRCDIR . "/login.php";
        } else {
            Http::redirect("/home");
        }
        break;
    case "/register":
        if (Auth::guest()) {
            require SRCDIR . "/register.php";
        } else {
            Http::redirect("/home");
        }
        break;
    case "/home":
        if (Auth::guest()) {
            Http::redirect("/login");
        } else {
            require SRCDIR . "/home.php";
        }
        break;
    case "/logout":
        if (Auth::guest()) {
            Http::redirect("/login");
        } else {
            require SRCDIR . "/logout.php";
        }
        break;
    case "/balance":
        if (Auth::guest()) {
            Http::redirect("/login");
        } else {
            require SRCDIR . "/balance.php";
        }
        break;
    case "/deposit":
        if (Auth::guest()) {
            Http::redirect("/login");
        } else {
            require SRCDIR . "/deposit.php";
        }
        break;
    case "/withdraw":
        if (Auth::guest()) {
            Http::redirect("/login");
        } else {
            require SRCDIR . "/withdraw.php";
        }
        break;
    case "/transfer":
        if (Auth::guest()) {
            Http::redirect("/login");
        } else {
            require SRCDIR . "/transfer.php";
        }
        break;
    default:
        require SRCDIR . "/404.php";
}
