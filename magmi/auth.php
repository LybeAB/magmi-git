<?php

$msg = '';

if (!empty($_POST['username'])
&& !empty($_POST['password'])) {

    if ($_POST['username'] == 'lybe' &&
        $_POST['password'] == 'pI*%v@9iX!Oi'
    ) {

        session_start();
        $_SESSION['valid'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['username'] = 'lybe';


        header('Location: web/magmi.php');

    } else {
        $msg = 'Wrong username or password';
        $uri = "index.php";
        header('Location: '.$uri.' ');
    }
}


