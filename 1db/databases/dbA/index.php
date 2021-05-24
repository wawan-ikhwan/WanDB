<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/1db/class.php"; //memanggil fungsi
    $db=new WanDB("dbA");
    $rowRange=100; //berapa banyak baris yang ditampilkan dalam 1 halaman
    $colBL=[]; //kolom yang diblacklist agar tidak ditampilkan
    // $colBL=[0]; //blacklist ID(kolom ke 0) agar tidak ditampilkan
    $totalRow=$db->getRow();
    if(isset($_GET["page"])){
        $page=$_GET["page"];
    }
    else{
        $page=0; //secara default di page pertama
    }

    $maxPage=floor($totalRow/$rowRange); //jangkauan maximum sebuah page

    if($page<0){ //jika pagenya tak logis
        header("Location: ./?page=0"); //redirect ke halaman pertama
        exit;
    }
    else if($page>$maxPage){ //jika pagenya tak logis
        header("Location: ./?page=$maxPage"); //redirect ke halaman terakhir
        exit;
    }
?>
<html>
    <head>
        <title><?php echo $db->DBName; ?> | 1db Database Manager</title>
        <style>
            table{
                border-style:solid;
                border-width:2px;
                border-color:purple;
                margin-left:auto;
                margin-right:auto;
                text-align:center;
            }
            button{
                margin-left:auto;
                margin-right:auto;
            }
        </style>
    </head>
    <body>
        <div style="text-align:center;">
            <h1>ONE QUERY LANGUAGE DATABASE CENTER</h1>
            <h2>DB: "<?php echo($db->DBName); ?>"</h2>
            <p> Kolom: <?php echo  $db->getCol();?> | Baris: <?php echo  $db->getRow(); ?> </p>
            <p> ID Terakhir: <?php echo  $db->getLastID(); ?></p>
            <a href="..">EXIT</a>
            <a href="/">HOME</a>
            <a href=<?php echo "./$db->DBName.1db";?>>RAW DATA</a>
            <br>
            <form method="GET" action=".">
                <input type="number" min="0" max=<?php echo $maxPage; ?> name="page" value=<?php echo $page;?>></input>
                <input type="submit" name="submit" value="GO"></input>
            </form>
            <button onclick="window.location.href='./index.php?page=0'">Halaman Awal</button>
            <button onclick="window.location.href='./index.php?page=<?php echo $page-1;?>'">Halaman Sebelum</button>
            <button onclick="window.location.href='./index.php?page=<?php echo $page+1;?>'">Halaman Berikut</button>
            <button onclick="window.location.href='./index.php?page=<?php echo $maxPage;?>'">Halaman Terakhir</button>
            <table border="1" style="width:75%">
                <tr>
                    <th>NO</th>
                    <?php
                    for($i=0; $i<=$db->getCol(); $i++){
                        if(!in_array($i,$colBL)){ //PENGECUALIAN KOLOM HEADER
                            echo "<th>".$db->getData(0,$i)."</th>"; //tampilkan header
                        }
                    }
                    ?>
                </tr>
                <?php
                for($j=1+($rowRange*$page); $j<=$rowRange*($page+1); $j++){ //perulangan baris, tampilkan baris secukupnya
                    if($j<=$totalRow){ //tampilkan hanya jika $j tidak lebih dari banyak baris
                        echo "<tr>";
                        echo "<td>".$j."</td>"; //NOMOR BARIS
                        for($i=0; $i<=$db->getCol(); $i++){ //perulangan kolom
                                if(!in_array($i,$colBL)){ //PENGECUALIAN KOLOM DATA
                                    echo "<td>".$db->getData($j,$i)."</td>";
                                }
                        }
                        echo "</tr>";
                    }
                }
                ?>
            </table>
            <button onclick="window.location.href='./index.php?page=0'">Halaman Awal</button>
            <button onclick="window.location.href='./index.php?page=<?php echo $page-1;?>'">Halaman Sebelum</button>
            <button onclick="window.location.href='./index.php?page=<?php echo $page+1;?>'">Halaman Berikut</button>
            <button onclick="window.location.href='./index.php?page=<?php echo $maxPage;?>'">Halaman Terakhir</button>
        </div>
    </body>
</html>