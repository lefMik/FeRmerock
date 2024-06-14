<?php
echo '<div class="boxzagolovka">
        <div class="tekstkataloga">Продаваемая продукция</div>
        <div class="napravlenie">
            <a href="/index.php?'.(isset($_GET['serch']) ? 'serch&' : '').(isset($_GET['categor']) ? 'categor='.$_GET['categor'].'&' : '').(isset($_GET['link']) ? 'link='.$_GET['link'].'&' : '').'sort=az"><i class=\'bx bx-sort-a-z\'></i></a>
            <a href="/index.php?'.(isset($_GET['serch']) ? 'serch&' : '').(isset($_GET['categor']) ? 'categor='.$_GET['categor'].'&' : '').(isset($_GET['link']) ? 'link='.$_GET['link'].'&' : '').'sort=za"><i class=\'bx bx-sort-z-a\'></i></a>
        </div>
    </div>';
$_SESSION['serchtext'] = isset($_POST['serchtext']) ? $_POST['serchtext'] : $_SESSION['serchtext'];
$db_connect = mysqli_connect( '127.0.0.1', 'root', '', 'dbmagazin' ) or die('Связь потеряна '.mysqli_connect_error());
$sql = 'SELECT COUNT(*) as coun 
        FROM tovar 
        Inner Join kultura ON kultura.id_kultura = tovar.id_kultura_tovar'.
        (isset($_GET['categor']) ? ' WHERE kultura.id_kultura = '.$_GET['categor'] : (($_SESSION['serchtext'] != "") ? ' WHERE tovar.name_tovar LIKE \'%'.$_SESSION['serchtext'].'%\'' : ''));
$resul = mysqli_query($db_connect, $sql) or die('Ошибка отправки запроса '.mysqli_error($db_connect));
$re = mysqli_fetch_array($resul);
$re = ceil($re['coun'] / 8);
if($re > 0)
{
    $_POST['minBtn'] = isset($_POST['minBtn']) ? $_POST['minBtn'] : 1;
    $_POST['maxBtn'] = ($re > 1) ? $re : $_POST['minBtn'];
    $_GET['link'] = isset($_GET['link']) ? $_GET['link'] : 1;
    $sql = 'SELECT tovar.id_tovar, tovar.name_tovar, tovar.foto, tovar.cena, edinic_izmer.name_edinic_izmer,
                tovar.opisanie, kultura.name_kultura
                FROM tovar
                Inner Join kultura ON kultura.id_kultura = tovar.id_kultura_tovar
                Inner Join edinic_izmer ON edinic_izmer.id_edinic_izmer = tovar.id_edinic_izmer_tovar'.
                (isset($_GET['categor']) ? ' WHERE kultura.id_kultura = '.$_GET['categor'] : (($_SESSION['serchtext'] != "") ? ' WHERE tovar.name_tovar LIKE \'%'.$_SESSION['serchtext'].'%\'' : '')).' 
                ORDER BY '.(isset($_GET['sort']) ? (($_GET['sort'] == 'az') ? 'tovar.cena DESC' : 'tovar.cena ASC') : 'tovar.id_tovar DESC').'
                LIMIT '.(($_GET['link']-1) * 8).' , 8';
    $resul = mysqli_query($db_connect, $sql) or die('Ошибка отправки запроса '.mysqli_error($db_connect));
    if(mysqli_num_rows($resul)>0)
    {
        echo '<div class="allprodukt">';
        while($re = mysqli_fetch_array($resul))
        {
            echo '<div class="produkt" id="'.$re['id_tovar'].'">
                        <div class="foto" style="background-image: url('.$re['foto'].');"></div>
                        <div class="nazvanie">'.$re['name_tovar'].'</div>
                        <div class="infcena">
                            <div class="cena">'.$re['cena'].' бел.руб.</div>
                            <div class="edizmeren">за 1 '.$re['name_edinic_izmer'].'</div>
                        </div>
                        <div class="batton">
                            <a href="#win'.$re['id_tovar'].'"><i class=\'bx bx-message-square-dots\'></i></a>
                            <a href="#close" class="overlay" id="win'.$re['id_tovar'].'"></a>
                            <div class="popup"><a class="close" title="Закрыть" href="#close">X</a>'.$re['opisanie'].'<br/>'.$re['name_kultura'].'</div>
                            <a href="/index.php?'.(isset($_GET['serch']) ? 'serch&' : '').(isset($_GET['categor']) ? 'categor='.$_GET['categor'].'&' : '').(isset($_GET['link']) ? 'link='.$_GET['link'].'&' : '').'add='.$re['id_tovar'].'"><i class=\'bx bxs-cart-add\'></i></a>
                        </div>
                    </div>';
        }
        echo '</div>';
        echo '<div class="prokrutka">
            <a class="link" href="/index.php?'.(isset($_GET['serch']) ? 'serch&' : '').(isset($_GET['categor']) ? 'categor='.$_GET['categor'].'&' : '').(isset($_GET['sort']) ? 'sort='.$_GET['sort'].'&' : '').'link='.$_POST['minBtn'].'" ';
                if($_GET['link'] == $_POST['minBtn'])
                {
                    echo 'style="pointer-events: none"';
                }
            echo '>
                <i class="bx bx-chevrons-left"></i>
            </a>
            <a class="link" href="/index.php?'.(isset($_GET['serch']) ? 'serch&' : '').(isset($_GET['categor']) ? 'categor='.$_GET['categor'].'&' : '').(isset($_GET['sort']) ? 'sort='.$_GET['sort'].'&' : '').'link=';
                if($_GET['link']>$_POST['minBtn'])
                {
                    echo ($_GET['link']-1).'" ';
                }
                else
                {
                    echo $_GET['link'].'" ';
                }
                if($_GET['link'] == $_POST['minBtn'])
                {
                    echo 'style="pointer-events: none"';
                }
            echo '>
                <i class="bx bx-chevron-left"></i>
            </a>';
                for($i = $_POST['minBtn']; $i<= (isset($_POST['maxBtn']) ? $_POST['maxBtn'] : $_POST['minBtn']); $i++)
                {
                    echo '<a href="/index.php?'.(isset($_GET['serch']) ? 'serch&' : '').(isset($_GET['categor']) ? 'categor='.$_GET['categor'].'&' : '').(isset($_GET['sort']) ? 'sort='.$_GET['sort'].'&' : '').'link='.$i.'" class="link';
                    if($_GET['link'] == $i)
                    {
                        echo ' active';
                    }
                    echo '">'.$i.'</a>';
                }
        echo '<a class="link" href="/index.php?'.(isset($_GET['serch']) ? 'serch&' : '').(isset($_GET['categor']) ? 'categor='.$_GET['categor'].'&' : '').(isset($_GET['sort']) ? 'sort='.$_GET['sort'].'&' : '').'link=';
                if($_GET['link'] < $_POST['maxBtn'])
                {
                    echo ($_GET['link']+1).'" ';
                }
                else
                {
                    echo $_GET['link'].'" ';
                }
                if($_GET['link'] == $_POST['maxBtn'])
                {
                    echo 'style="pointer-events: none"'; 
                }
            echo '>
                <i class="bx bx-chevron-right"></i>
            </a>
            <a class="link" href="/index.php?'.(isset($_GET['serch']) ? 'serch&' : '').(isset($_GET['categor']) ? 'categor='.$_GET['categor'].'&' : '').(isset($_GET['sort']) ? 'sort='.$_GET['sort'].'&' : '').'link='.$_POST['maxBtn'].'" ';
                if($_GET['link'] == $_POST['maxBtn'])
                {
                    echo 'style="pointer-events: none"';
                }
            echo '>
                <i class="bx bx-chevrons-right"></i>
            </a>
            </div>';
    }
    else
    {
        echo '<div>Ничего не найдено</div>';
    }
}
else
{
    echo '<div>Ничего не найдено</div>';
}
mysqli_close($db_connect);
?>