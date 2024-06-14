<?php
    session_start();
    if(!'BEZ_KEY')
    {
        header("HTTP/1.1 404 Not Found");
        exit(file_get_contents('404.html'));
    }
    
    $_SESSION['user'] = null;
    $_SESSION['id'] = null;
    header('Location: http://'. $_SERVER['HTTP_HOST']);
    exit;
?>