<?php
    session_start();
    $_SESSION['user'] = null;
    $_SESSION['id'] = null;
    if(!'BEZ_KEY')
    {
        header("HTTP/1.1 404 Not Found");
        exit(file_get_contents('404.html'));
    }

    if(isset($_POST['submit']))
    {
        if(count($err) > 0){
            for($i = 0; $i<count($err); $i++ )
            {
                echo '<div>'. $err[$i] .'</div>';
            }
        }
        else
        {
            $db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Не удалось соединиться: ');
            $sql = 'SELECT bez_reg.id, userstat.name_userstat
                    FROM bez_reg
                    Inner Join userstat ON userstat.id_userstat = bez_reg.userstatus
                    WHERE  bez_reg.login = "'. $_POST['login'] .'" AND bez_reg.active_hex = "'. md5($_POST['pass']) .'" AND bez_reg.status = 1';
            $res = mysqli_query($db_connect, $sql);
            if(mysqli_num_rows($res)>0)
            {
                $_SESSION['user'] = $_POST['login'];
                $re = mysqli_fetch_assoc($res);
                $_SESSION['id'] = $re['id'];
                $_SESSION['name_userstat'] = $re['name_userstat'];
                mysqli_close($db_connect);
                header('Location: http://'. $_SERVER['HTTP_HOST']);
                exit;
            }
            else
            {
                echo '<div class="messageokno actione red">Ошибка авторизации</div>';
                mysqli_close($db_connect);
            }
        }
    }
?>