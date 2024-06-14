<?php
    session_start();
    $_SESSION['user'] = null;
    $_SESSION['id'] = null;
    if(!'BEZ_KEY')
    {
        header("HTTP/1.1 404 Not Found");
        exit(file_get_contents('404.html'));
    }

    if(isset($_GET['status']) and $_GET['status'] == 'ok')
    echo '<div class="messageokno actione yellow">Вы успешно зарегистрировались!</div>';

    if(isset($_POST['submit']))
    {
        if($_POST['pass'] != $_POST['pass2'])
            $err[] = 'Пароли не совподают';

        if(count($err) > 0){
            for($i = 0; $i<count($err); $i++ )
            {
                echo '<div class="messageokno actione red">'. $err[$i] .'</div>';
            }
        }
        else
        {
            $db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Не удалось соединиться: ');
            $sql = 'SELECT id FROM bez_reg
                    WHERE `login` = "'. $_POST['login'] .'"';
            $res = mysqli_query($db_connect, $sql);

            if(mysqli_num_rows($res)>0)
                $err[] = 'Логин: <b>'. $_POST['login'] .'</b> занят!';

            mysqli_close($db_connect);

            if(count($err) > 0)
            {
                for($i = 0; $i<count($err); $i++ )
                {
                    echo '<div class="messageokno actione red">'. $err[$i] .'</div>';
                }
            }
            else
            {
                $db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Не удалось соединиться: ');
                $sql = 'INSERT INTO `bez_reg`(`id`, `login`, `active_hex`, `status`, `userstatus`)
                        VALUES("", "'. $_POST['login'] .'", "'. md5($_POST['pass']) .'", 1, 1)';
                if(mysqli_query($db_connect, $sql))
                {
                    echo '<div class="messageokno actione yellow">Регистрация прошла успешно</div>';
                }
                else
                {
                    echo '<div class="messageokno actione red">Ошибка: '.mysqli_error($db_connect).'</div>';
                }
                mysqli_close($db_connect);
            }
        }
    }
?>