<?php 
    $mode = isset($_GET['mode'])  ? $_GET['mode'] : false;
    switch($mode)
    {
        case 'reg':
            include '_reg.php';
            include 'reg_form.html';
            break;
        case 'auth':
            include '_auth.php';
            include 'auth_form.html';
            break;
        case 'close':
            include '_close.php';
            break;
    } 
?>