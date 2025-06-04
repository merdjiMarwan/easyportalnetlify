<?php
session_start();
session_unset();
session_destroy();
header("Location: ../index.html"); // redirige vers la page de login
exit();
?>
