<?php
    session_start();

    if(isset($_GET['zakazok']))
    {
      $db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Не удалось соединиться: ');
      $sql = 'UPDATE list SET status_zakaza=1 WHERE nomer_list='.$_GET['zakazok'];
      if(mysqli_query($db_connect, $sql))
      {
        echo '<div class="messageokno actione yellow">Заказ выполнен</div>';
      }
      else
      {
        echo '<div class="messageokno actione red">Ошибка при изменении статуса заказа</div>';
      }
    }

    if(isset($_POST['saveform']))
    {
      $db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Не удалось соединиться: ');
      $sql = 'UPDATE bez_reg SET `email`=\''.$_POST['email'].'\', `telefon`=\''.$_POST['telefon'].'\' WHERE `login`=\''.$_SESSION['user'].'\'';
      if(mysqli_query($db_connect, $sql))
      {
        echo '<div class="messageokno actione yellow">Данные сохранены</div>';
      }
      else
      {
        echo '<div class="messageokno actione red">Ошибка при сохранении данных</div>';
      }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/boxicons.min.css" />
    <link rel="stylesheet" href="/css/style.css" />
    <script src="/js/javascr.js"></script>
    <title>ФермерОК</title>
  </head>
  <body>
    <div class="slidebar">
      <div class="logo_content">
        <div class="logo">
          <i class="bx bxs-book-content"></i>
          <div class="logo_name"><?php echo $_SESSION['name_userstat'] ?></div>
        </div>
      </div>
      <div class="nav_list">
        <a href="index.php?user=conf">
          <label class="menu <?php echo (($_GET['user']=="conf") ? 'active' : ''); ?>">
            <i class="bx bxs-user-circle"></i>
            <span class="links_name"><?php echo $_SESSION['user']; ?></span>
          </label>
        </a>

        <?php
          if($_SESSION['name_userstat'] == 'Администратор')
          {
            echo '<a href="index.php?user=zakaz">
                    <label class="menu '.(($_GET['user']=="zakaz") ? 'active' : '').'">
                      <i class="bx bx-wallet-alt"></i>
                      <span class="links_name">Заказы</span>
                    </label>
                  </a>';
          }
          else
          {
            echo '<a href="index.php?user=buy">
                    <label class="menu '.(($_GET['user']=="buy") ? 'active' : '').'">
                      <i class="bx bx-wallet-alt"></i>
                      <span class="links_name">Покупки</span>
                    </label>
                  </a>';
          }
        ?>
      </div>
      <div class="becap_content" id="glavnaya">
        <a href="<?php echo 'http://'. $_SERVER['HTTP_HOST']; ?>">
          <div class="becap">
            <i class="bx bx-log-out-circle"></i>
            <span class="links_name">Главная</span>
          </div>
        </a>
      </div>
    </div>
    <div class="home_content">
      <?php
        switch($_GET['user'])
        {
          case 'conf':
            $db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Не удалось соединиться: ');
            $sql = 'SELECT `login`, `email`, `telefon` FROM `bez_reg` WHERE `login`=\''.$_SESSION['user'].'\'';
            $res = mysqli_query($db_connect, $sql);
            $re = mysqli_fetch_assoc($res);
            echo '<div class="usercontayner">
                <div class="usericon">
                  <i class="bx bxs-user-circle"></i>
                </div>
                <div class="userinfo">
                  <form action="" method="post">
                    <div class="username">'.$_SESSION['user'].'</div>
                    <div class="userstatus">'.$_SESSION['name_userstat'].'</div>
                    <div class="boxemail">
                      <span>E-mail</span>
                      <input type="email" name="email" value="'.$re['email'].'" autocomplete="off"/>
                    </div>
                    <div class="boxtel">
                      <span>Телефон</span>
                      <input type="tel" name="telefon" value="'.$re['telefon'].'" autocomplete="off"/>
                    </div>
                    <input type="submit" value="Сохранить" name="saveform">
                  </form>
                </div>
              </div>';
            break;

          case 'buy':
            $db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Не удалось соединиться: ');
            $sql = 'SELECT nomer_list, data, summa FROM list
                    WHERE id_bez_reg_list = '.$_SESSION['id'];
            $res = mysqli_query($db_connect, $sql);
            if(mysqli_num_rows($res) > 0)
            {
              $massList = array();
              while($re = mysqli_fetch_array($res))
              {
                array_push($massList, $re);
              }
              echo '<div class="accordion">';

              for($i = 0; $i < count($massList); $i++)
              {
                echo '<div class="contentBx">
                        <div class="content_lable" onclick="accordionOnClick(this)">
                          <div>№ чека: '.$massList[$i]['nomer_list'].'</div>
                          <div>дата: '.$massList[$i]['data'].'</div>
                          <div>сумма: '.$massList[$i]['summa'].' бел.руб.</div>
                        </div>';
                $sql = 'SELECT chek.kolichestvo, chek.cena, tovar.name_tovar
                        FROM chek
                        Inner Join tovar ON tovar.id_tovar = chek.id_tovar_chek
                        Inner Join list ON list.id_list = chek.id_list_chek
                        WHERE list.nomer_list = '.$massList[$i]['nomer_list'];
                $res = mysqli_query($db_connect, $sql);
                $massTovar = array();
                while($re = mysqli_fetch_array($res))
                {
                  array_push($massTovar, $re);
                }
                echo '<div class="content_text"><table>
                          <tr>
                            <th>Наименование товара</th>
                            <th>Количество</th>
                            <th>Стоимость</th>
                          </tr>';
                for($j = 0; $j < count($massTovar); $j++)
                {
                  echo '<tr>
                          <td class="tovarname">'.$massTovar[$j]['name_tovar'].'</td>
                          <td class="tovarkolich">'.$massTovar[$j]['kolichestvo'].'</td>
                          <td class="tovarstoim">'.$massTovar[$j]['cena'].' бел.руб.</td>
                        </tr>';
                }
                echo '</table></div></div>';
              }
            }
            else
            {
              echo '<div class="accordion"><div class="gruppa"><div class="gruppatext">Покупак не найдено</div></div></div>';
            }
            echo '</div>';
            mysqli_close($db_connect);
            break;

          case 'zakaz':
            echo '<div class="accordion">';
            $db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Не удалось соединиться: ');
            $sql = 'SELECT list.nomer_list, list.data, list.summa, list.status_zakaza, bez_reg.email, bez_reg.telefon
                    FROM list
                    Inner Join bez_reg ON bez_reg.id = list.id_bez_reg_list
                    WHERE list.status_zakaza = 0';
            $res = mysqli_query($db_connect, $sql);
            if(mysqli_num_rows($res) > 0)
            {
              $massList = array();
              while($re = mysqli_fetch_array($res))
              {
                array_push($massList, $re);
              }
              echo '<div class="gruppa">';
              echo '<div class="gruppatext">Заказы в очереди</div>';
              echo '<div class="gruppazakazov">';
              for($i = 0; $i < count($massList); $i++)
              {
                echo '<div class="contentBx">
                        <div class="content_lab">
                          <div class="content_info" onclick="accordionOn(this)">
                            <div>
                              <div>№ чека: '.$massList[$i]['nomer_list'].'</div>
                              <div>дата: '.$massList[$i]['data'].'</div>
                            </div>
                            <div>сумма: '.$massList[$i]['summa'].' бел.руб.</div>
                            <div>
                              <div>e-mail: '.$massList[$i]['email'].'</div>
                              <div>тел.: '.$massList[$i]['telefon'].'</div>
                            </div>
                          </div>
                          <div class="content_butt">
                            <a href="/client/index.php?user=zakaz&zakazok='.$massList[$i]['nomer_list'].'">
                              <div class="buttonzakaz">Готово</div>
                            </a>
                          </div>
                        </div>';
                $sql = 'SELECT chek.kolichestvo, chek.cena, tovar.name_tovar
                        FROM chek
                        Inner Join tovar ON tovar.id_tovar = chek.id_tovar_chek
                        Inner Join list ON list.id_list = chek.id_list_chek
                        WHERE list.nomer_list = '.$massList[$i]['nomer_list'];
                $res = mysqli_query($db_connect, $sql);
                $massTovar = array();
                while($re = mysqli_fetch_array($res))
                {
                  array_push($massTovar, $re);
                }
                echo '<div class="content_text"><table>
                          <tr>
                            <th>Наименование товара</th>
                            <th>Количество</th>
                            <th>Стоимость</th>
                          </tr>';
                for($j = 0; $j < count($massTovar); $j++)
                {
                  echo '<tr>
                          <td class="tovarname">'.$massTovar[$j]['name_tovar'].'</td>
                          <td class="tovarkolich">'.$massTovar[$j]['kolichestvo'].'</td>
                          <td class="tovarstoim">'.$massTovar[$j]['cena'].' бел.руб.</td>
                        </tr>';
                }
                echo '</table></div></div>';
              }
              echo '</div></div>';
            }
            else
            {
              echo '<div class="gruppa"><div class="gruppatext">Заказов не найдено</div></div>';
            }
            
            $db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Не удалось соединиться: ');
            $sql = 'SELECT list.nomer_list, list.data, list.summa, list.status_zakaza, bez_reg.email, bez_reg.telefon
                    FROM list
                    Inner Join bez_reg ON bez_reg.id = list.id_bez_reg_list
                    WHERE `status_zakaza` = 1';
            $res = mysqli_query($db_connect, $sql);
            if(mysqli_num_rows($res) > 0)
            {
              $massList = array();
              while($re = mysqli_fetch_array($res))
              {
                array_push($massList, $re);
              }
              echo '<div class="gruppa">';
              echo '<div class="gruppatext">Заказы выполнены</div>';
              echo '<div class="gruppazakazov">';
              for($i = 0; $i < count($massList); $i++)
              {
                echo '<div class="contentBx">
                        <div class="content_lable" onclick="accordionOnClick(this)">
                          <div>
                            <div>№ чека: '.$massList[$i]['nomer_list'].'</div>
                            <div>дата: '.$massList[$i]['data'].'</div>
                          </div>
                          <div>сумма: '.$massList[$i]['summa'].' бел.руб.</div>
                          <div>
                            <div>e-mail: '.$massList[$i]['email'].'</div>
                            <div>тел.: '.$massList[$i]['telefon'].'</div>
                          </div>
                        </div>';
                $sql = 'SELECT chek.kolichestvo, chek.cena, tovar.name_tovar
                        FROM chek
                        Inner Join tovar ON tovar.id_tovar = chek.id_tovar_chek
                        Inner Join list ON list.id_list = chek.id_list_chek
                        WHERE list.nomer_list = '.$massList[$i]['nomer_list'];
                $res = mysqli_query($db_connect, $sql);
                $massTovar = array();
                while($re = mysqli_fetch_array($res))
                {
                  array_push($massTovar, $re);
                }
                echo '<div class="content_text"><table>
                          <tr>
                            <th>Наименование товара</th>
                            <th>Количество</th>
                            <th>Стоимость</th>
                          </tr>';
                for($j = 0; $j < count($massTovar); $j++)
                {
                  echo '<tr>
                          <td class="tovarname">'.$massTovar[$j]['name_tovar'].'</td>
                          <td class="tovarkolich">'.$massTovar[$j]['kolichestvo'].'</td>
                          <td class="tovarstoim">'.$massTovar[$j]['cena'].' бел.руб.</td>
                        </tr>';
                }
                echo '</table></div></div>';
              }
              echo '</div></div>';
            }
            else
            {
              echo '<div class="gruppa"><div class="gruppatext">Выполненных заказов не найдено</div></div>';
            }
            echo '</div>';
            mysqli_close($db_connect);
            break;
        }
      ?>
    </div>
  </body>
</html>