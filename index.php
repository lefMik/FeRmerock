<?php
    session_start(); //запуск сессии
    $err = array();
    define('BEZ_KEY', true);
    $_SESSION['serchtext'] = isset($_GET['serch']) ? $_SESSION['serchtext'] : null;
    $_SESSION['karzina'] = isset($_SESSION['karzina']) ? $_SESSION['karzina'] : array();
    if(isset($_GET['add']))
    {
      $_SESSION['karzina'][$_GET['add']] = array('idtovara' => $_GET['add'], 'kolich' => 1);
    }
?>

<html lang="ru">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/boxicons.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/javascr.js"></script>
    <title>ФермерОК</title>
  </head>
  <body>
    <div class="poz-0">
      <div class="conteyner">
        <div class="contact-left">
            <div class="email">
                <i class="bx bx-envelope"></i>
                <a href="mailto: email@email.email">email@email.email</a>
            </div>
            <div class="telefon">
                <i class="bx bx-phone"></i>
                <a href="tel:8 029 123-45-67">8 029 123-45-67</a>
            </div>
            </div>
            <div class="contact-rite">
            <div class="socseti">
                <a href="https://ru-ru.facebook.com/"><i class="bx bxl-facebook"></i></a>
                <a href="https://web.telegram.org/"><i class="bx bxl-telegram"></i></a>
                <a href="https://vk.com/"><i class="bx bxl-vk"></i></a>
                <a href="https://twitter.com"><i class="bx bxl-twitter"></i></a>
            </div>
            <div class="login">
                <?php
                if(isset($_SESSION['user']))
                {
                    echo '<a href="/client/index.php?user=conf" title="Кабинет пользователя: '.$_SESSION['user'].'">
                            <i class=\'bx bxs-user-circle onuser\'></i>
                          </a>
                          <a href="/autorization/_form.php?mode=close">Выход</a>';
                }
                else
                {
                    echo '<i class="bx bx-user"></i>
                          <a href="/autorization/_form.php?mode=auth">Вход</a>
                          <a href="/autorization/_form.php?mode=reg">Регистрация</a>';
                }
                ?>
            </div>
            </div>
      </div>
    </div>

    <div class="poz-1">
      <div class="conteyner">
        <div class="logon">
            <a href="<?php echo 'http://'. $_SERVER['HTTP_HOST']; ?>"><img src="img/logo.png" /></a>
        </div>
        <div class="menu">
            <ul>
                <li><a href="<?php echo 'http://'. $_SERVER['HTTP_HOST']; ?>">Главная</a></li>
                <li><a href="<?php echo 'http://'. $_SERVER['HTTP_HOST'].'/index.php?material=compani'; ?>">Контакты</a></li>
                <li><a href="<?php echo 'http://'. $_SERVER['HTTP_HOST'].'/index.php?material=skidki'; ?>">Скидки</a></li>
            </ul>
        </div>
        <?php
        if(!isset($_GET['korzina']))
        {
          echo '<div class="corzina">
          <a href="/index.php?korzina"><i class="bx bx-cart"></i></a>
          <span class="count">'.count($_SESSION['karzina']).'</span>
          </div>';
        }
        ?>
      </div>
    </div>
<?php

//каталог культур
  if(!isset($_GET['korzina']))
  {
    $_SESSION['nomercheka'] = null;
    echo '<div class="poz-2">
      <div class="conteyner">
      <div class="catalog">
        <span class="catalog-text">Каталог</span>
        <i class=\'bx bx-plus\'></i>
        <ul>';
                $db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Не удалось соединиться: ');
                $sql = 'SELECT kultura.id_kultura, kultura.name_kultura FROM kultura';
                $res = mysqli_query($db_connect, $sql);
                if(mysqli_num_rows($res)>0)
                {
                    while($re = mysqli_fetch_assoc($res))
                    {
                        echo '<li><a href="/index.php?categor='.$re['id_kultura'].'">'.$re['name_kultura'].'</a></li>';
                    }
                }
                else
                {
                    echo '<li>Ошибка</li>';
                }
                mysqli_close($db_connect);
        echo  '</ul>
      </div>
      <div class="search">
          <form action="index.php?serch" method="post">
              <input type="search" name="serchtext" id="serchid">
              <input type="submit" value="Найти" name="serch">
          </form>
      </div>
    </div>
  </div>';
    if(!isset($_GET['material']) && !isset($_GET['categor']) && !isset($_GET['serch']))
    {
      include "slaidshow.html";
    }

    if(!isset($_GET['material']) && !isset($_GET['categor']) && !isset($_GET['serch']))
    {
      include "miniinfo.html";
    }
  echo '<div class="poz-5">
    <div class="conteyner">';
        switch($_GET['material'])
        {
          case 'compani':
            include "compani.html";
            break;
          case 'skidki':
            include 'skidki.html';
            break;
          default:
            include "tovari.php";
        }
      echo  '</div></div>';
  }
  else
  {
    include 'korzina.php';
  } 
?>
    <div class="poz-6">
      <div class="conteyner">
        <?php include "ubka.html"?>
      </div>
    </div>
  </body>
</html>