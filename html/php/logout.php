<?php
session_start();
session_unset();
session_destroy();
header("Location: ../index"); // redirige vers la page de login
exit();
?>
