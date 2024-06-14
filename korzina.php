<?php
  if(!'BEZ_KEY')
  {
    header("HTTP/1.1 404 Not Found");
    exit(file_get_contents('404.html'));
  }
  $_SESSION['nomercheka'] = isset($_SESSION['nomercheka']) ? $_SESSION['nomercheka'] : date('dmYhis');
  if(isset($_GET['delete']))
  {
    unset($_SESSION['karzina'][$_GET['delete']]);
  }
  if(isset($_GET['addcolich']))
  {
    $i = $_SESSION['karzina'][$_GET['addcolich']]['kolich'];
    $k = $_SESSION['karzina'][$_GET['addcolich']]['cena'];
    if($i < 100)
    {
      $i = $i + 1;
      $k = $i * $k;
    }
    $_SESSION['karzina'][$_GET['addcolich']]['kolich'] = $i;
    $_SESSION['karzina'][$_GET['addcolich']]['summa'] = number_format($k, 2);
  }
  if(isset($_GET['remcolich']))
  {
    $i = $_SESSION['karzina'][$_GET['remcolich']]['kolich'];
    $k = $_SESSION['karzina'][$_GET['remcolich']]['cena'];
    if($i > 1)
    {
      $i = $i - 1;
      $k = $i * $k;
    }
    $_SESSION['karzina'][$_GET['remcolich']]['kolich'] = $i;
    $_SESSION['karzina'][$_GET['remcolich']]['summa'] = number_format($k, 2);
  }
  if(isset($_GET['buy']))
  {
    $db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Не удалось соединиться: ');
    $sql = 'INSERT INTO list(nomer_list, `data`, summa, id_bez_reg_list) 
              VALUES ('.$_SESSION['nomercheka'].',\''.date('Y-m-d h:i:s').'\','.$_SESSION['summacheka'].','.$_SESSION['id'].')';
    if(mysqli_query($db_connect, $sql))
    {
      foreach($_SESSION['karzina'] as $mass)
      {
        $sql = 'INSERT INTO chek(id_list_chek, id_tovar_chek, kolichestvo, cena) 
                VALUES ((
                  SELECT id_list 
                  FROM list WHERE nomer_list = '.$_SESSION['nomercheka'].'
                ),'.$mass['idtovara'].','.$mass['kolich'].','.$mass['summa'].')';
        if(mysqli_query($db_connect, $sql))
        {
          echo '<div>Заказ оформлен</div>';
        }
        else
        {
          echo '<div>Ошибка: '.mysqli_error($db_connect).'</div>';
        }
      }
      $_SESSION['karzina'] = null;
      $_SESSION['nomercheka'] = null;
      $_SESSION['summacheka'] = null;
      mysqli_close($db_connect);
    }
    else
    {
      echo '<div>Ошибка: '.mysqli_error($db_connect).'</div>';
    }
  }
  $_SESSION['summacheka'] = null;
  // $_SESSION['karzina'] = null;
  $db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Не удалось соединиться: ');
  echo '<div class="poz-7">
    <div class="shopping-cart">
    <div class="title">Корзина покупок</div>
    <div class="info">
      <div class="username">Пользователь: '.(isset($_SESSION['user']) ? $_SESSION['user'] : '-').'</div>
      <div class="chek">#'.$_SESSION['nomercheka'].'</div>
    </div>';
  if(count($_SESSION['karzina'])>0)
  {
    foreach($_SESSION['karzina'] as $mass)
    {
      $sql = 'SELECT tovar.name_tovar, tovar.foto, tovar.cena, edinic_izmer.name_edinic_izmer
              FROM tovar
              Inner Join edinic_izmer ON edinic_izmer.id_edinic_izmer = tovar.id_edinic_izmer_tovar
              WHERE tovar.id_tovar='.$mass['idtovara'];
      $res = mysqli_query($db_connect, $sql);
      if(mysqli_num_rows($res)>0)
      {
        while($re = mysqli_fetch_assoc($res))
        {
          $mass['cena'] = (isset($mass['cena']) ? $mass['cena'] : $re['cena']);
          $mass['summa'] = (isset($mass['summa']) ? $mass['summa'] : $mass['cena']);
          echo '<form method="POST" action="" class="item">
            <div class="buttons">
              <a href="index.php?korzina&delete='.$mass['idtovara'].'">X</a>
            </div>

            <div class="image" style="background-image: url('.$re['foto'].');"></div>

            <div class="description">
              <span>'.$re['name_tovar'].'</span>
              <span>'.$re['name_edinic_izmer'].'</span>
            </div>

            <div class="quantity">
            
              <a href="index.php?korzina&addcolich='.$mass['idtovara'].'"><div class="button">+</div></a>
              <div class="kolichestvo">'.$mass['kolich'].'</div>
              <a href="index.php?korzina&remcolich='.$mass['idtovara'].'"><div class="button">-</div></a>
            </div>
            <div class="total-price">'.$mass['summa'].'<span>бел.руб.</span></div>
          </form>';
          $_SESSION['karzina'][$mass['idtovara']] = array('idtovara' => $mass['idtovara'], 'kolich' => $mass['kolich'], 'cena' => $mass['cena'], 'summa' => $mass['summa']);
          $_SESSION['summacheka'] = number_format($_SESSION['summacheka'] + $mass['summa'], 2);
        }
      }
    }
    echo '<div class="oformlenie">
            <div class="summa">Итого: '.$_SESSION['summacheka'].' бел.руб.</div>';
              if(isset($_SESSION['user']))
              {
                echo '<a href="index.php?korzina&buy=ok"><div class="buy">Заказать</div></a>';
              }
              else
              {
                echo '<div class="buy">Заказать</div> ';
              }
    echo '</div>';
  }
  else
  {
    echo '<div class="item"><div>Корзина пуста</div></div>';
  }
  mysqli_close($db_connect);
  echo '</div></div>';
?>