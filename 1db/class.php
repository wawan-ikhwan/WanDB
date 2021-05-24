<?php

//VERSI 3 (Sudah Mengunakan OOP)
//Apa yang baru?
//MENGUBAH NAMA MERK DARI 1ql ke 1db
//Menjadikan fungsi agar support OOP
//penghapusan parameter $db pada setiap method karena sudah ada properti $DBname

//VERSI 2 (MASIH MENGGUNAKAN FUNGSI TANPA OOP)
//Apa yang baru?
//Perubahan algoritma pada pengambilan baris menggunakan fungsi bawaan PHP yaitu explode(); dan implode();
//dimana fungsi bawaan tersebut dimodifikasi untuk array 2Dimensi
//Dimana getContent yang didapat bisa dalam bentuk array 2 Dimensi selain plain text
//argumennya baris dulu baru kolom
//yang dimaksud matriks adalah array 2D
//Tentu saja algoritmanya lebih cepat!

//VERSI 1 (MASIH MENGGUNAKAN FUNGSI TANPA OOP)
//DB adalah akronim dari DATABASE
//var $db adalah tipe data string, var ini adalah database yang mau di-handle
//TIPS: Gunakan Fitur Find pada text editor "function get" untuk mencari getter dan "function set" untuk mencari fungsi void
//keywords: put,get,set,swap,move,push,delete
//require $_SERVER['DOCUMENT_ROOT']."/1db/class.php"; //memanggil fungsi
// komen # berarti penting dicatat

class WanDB {

    //Properties:
    public $DBName; //nama DB
    public $nullify=null; //intelephanse pada vscode error sialan, errrornya kalau parameter diisi null menampilkan peringatan, makanya pakai var

    //Constructor
    function __construct($DatabaseName="TEMPLATE") { //secara default pilih DB TEMPLATE
        $this->DBName = $DatabaseName; //atur atribute DBName dari konstruktor
    }

    //Methods:

    function explode2D(string $data,string $rowSeparator,string $colSeparator){ //mengubah string menjadi array 2D
        $result=[]; //deklarasi $result adalah array dua dimensi
        $rawContent=$data; //fungsi rekursif mengambil konten mentah
        $row=explode($rowSeparator,$rawContent,-1); //memecah separator baris kedalam bentuk array, dikurang 1 karena separator akhir
        for($i=0; $i<=count($row)-1; $i++){
            array_push($result,explode($colSeparator,$row[$i],-1)); //memecah separator kolom dalam bentuk array, dikurang 1 karena separator akhir
        } 
        return $result; //array 2D
    }

    function implode2D(array $matrix,string $rowSeparator,string $colSeparator){ //mengubah array 2D menjadi string
        for($i=0; $i<=count($matrix)-1; $i++){
            $matrix[$i]=implode($colSeparator,$matrix[$i]).$colSeparator;
        }
        $matrix=implode($rowSeparator,$matrix).$rowSeparator;
        return $matrix; //string
    }

    function isDBExist($redirect404=true){ //mengecek apakah DB sudah ada atau belum
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        $path=$_SERVER['DOCUMENT_ROOT']."/1db/databases/$db/$db.1db";
        if(file_exists($path)){
            return true;
        }
        else{
            echo " \"$db\" TAK ADA! ";
            if($redirect404){ //PINDAHKAN KE NOT FOUND
                header("Location: /1db/notFound/404.html");
            }
            return false;
        }
    }

    function createDB(){ //membuat DB baru $db=(NAMA DB BARU)
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if(!$this->isDBExist(false)){ //jika DB tidak ada
            mkdir($_SERVER['DOCUMENT_ROOT']."/1db/databases/$db");

            //membuat file rawData (file.1db)
            $dest=$_SERVER['DOCUMENT_ROOT']."/1db/databases/$db/$db.1db";
            copy($_SERVER['DOCUMENT_ROOT']."/1db/databases/TEMPLATE/TEMPLATE.1db",$dest);
            
            //membuat file (lastID.id)
            $dest=$_SERVER['DOCUMENT_ROOT']."/1db/databases/$db/lastID.id";
            copy($_SERVER['DOCUMENT_ROOT']."/1db/databases/TEMPLATE/lastID.id",$dest);
            
            //membuat file (index.php)
            $dest=$_SERVER['DOCUMENT_ROOT']."/1db/databases/$db/index.php";
            copy($_SERVER['DOCUMENT_ROOT']."/1db/databases/TEMPLATE/index.php",$dest);
            
            echo("\"$db\""." SUKSES DIBUAT!");
        }
        else{
            return "\"$db\""." SUDAH ADA!"; //GUNAKAN ECHO AGAR STATUS BISA DILIHAT
        }
    }

    function getIDPath(){ //mendapatkan path dimana lastID.id disimpan
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            return $_SERVER['DOCUMENT_ROOT']."/1db/databases/$db/lastID.id";
        }
    }

    function getLastID(){ //mendapatkan nilai lastID.id
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){
            $currentID=file_get_contents($this->getIDPath());
            return $currentID;
        }
    }

    function setLastID(int $data){ //mengatur nilai lastID.id
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){
            file_put_contents($_SERVER['DOCUMENT_ROOT']."/1db/databases/$db/lastID.id",$data);
        }
    }

    function incrementID(){ //menaikkan nilai ID (mengubah file lastID.id) sebanyak 1
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            file_put_contents($this->getIDPath(),$this->getLastID()+1);
        }
    }

    function getContent(bool $rawData=false){ //mengambil isi content file database
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        //secara default mengembalikan dalam bentuk array jika rawData=false
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if($rawData){ //jika user minta data mentah maka dalam bentuk string
                $path=$_SERVER['DOCUMENT_ROOT']."/1db/databases/$db/$db.1db"; //path database
                return file_get_contents($path); //ambil isi file database dan kembalikan dalam bentuk string
            }
            else{ //jika user minta data dalam bentuk array dua dimensi
                    return $this->explode2D($this->getContent(true),"\n","|"); //mengembalikan isi DB  dalam bentuk array dua dimensi $result[baris][kolom] #
            }
        }
    }

    function getTransposedContent(){ //mengubah baris jadi kolom dan kolom jadi baris
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            return array_map($this->nullify, ...$this->getContent()); //mengembalikan dalam bentuk array dua dimensi
        }
    }

    function putContent(string $data){ //menaruh data ke file database (OVERWRITE!!!)
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            $path=$_SERVER['DOCUMENT_ROOT']."/1db/databases/$db/$db.1db"; //path database
            file_put_contents($path,$data); //taruh data ke file database
        }
    }

    function getDBLength(){ //panjang DB (file.1db)
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            return strlen($this->getContent(true));
        }
    }

    function getRow(){ //mengambil banyak baris pada file database
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            return count($this->getContent())-1; //kembalikan nilai baris yang terhitung (INTEGER), dikurang 1 karena baris Header adalah baris ke-0
        }
    }

    function getCol(){ //mengambil banyak kolom pada database
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            return count($this->getTransposedContent())-1; //banyak elemen pada baris 0 adalah banyak kolom database
        }
    }

    // function isRowsEmpty($db){ //Mengecek apakah baris hanya ada Header-Baris1 atau tidak
    //     if($this->getContent()[1][0]==null){
    //         return false;
    //     }
    //     else{
    //         return true;
    //     }
    // }

    function getHeaderPos(string $header){ //mengambil posisi kolom ke-n berdasarkan nama header (baris 0)
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if (is_int(array_search($header,$this->getRowData(0)))){ //mencegah hasilnya null
                return array_search($header,$this->getRowData(0)); //mengembalikan dalam bentuk integer
            }
            else{
                return -1; //return -1 jika tak ketemu
            }
        }
    }

    function getIDPos(string $ID){ //mengambil posisi baris ke-n berdasarkan ID (kolom 0)
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        //fungsi berlaku jika ada kolom ID pada file, $ID haruslah string #
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_int(array_search($ID,$this->getColData(0)))){ //mencegah hasilnya null
                return array_search($ID,$this->getColData(0)); //mengembalikan dalam bentuk integer
            }
            else{
                return -1; //jika hasilnya null maka posisinya -1
            }  
        }
    }

    function getRowData($row,$rawData=false){ //mendapatkan data baris ke-n, dalam bentuk tergantung
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($row)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $row=$this->getIDPos($row);
            }
            if($row>=0 and $row<=$this->getRow()){ //pastikan argumen $row tidak melebihi jumlah baris pada file
                if($rawData){
                    return implode("|",$this->getContent()[$row])."|"; //kembalikan nilai dalam bentuk string jika $rawData=true
                }
                else{
                    return $this->getContent()[$row]; //kembalikan nilai dalam bentuk sarray jika $rawData=false atau tanpa argumen
                }
            }
        }
    }

    function getColData($col){ //mendapatkan data kolom ke-n dalam bentuk array
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($col)){ //konversi ke indeks jika argumen kolomnya berupa nama (string)
                $col=$this->getHeaderPos($col);
            }
            if($col>=0 and $col<=$this->getCol()){ //argumennya harus sesuai dengan banyak kolom pada file
                return $this->getTransposedContent()[$col]; //mengembalikan dalam bentuk array
            }
        }
    }

    function insertCol($col){ ////menambahkan kolom dengan data kosong sesuai dengan argumen row yang diminta
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        //jika argumen kolom kelebihan maka tambahkan kolom sebanyak yang kurang
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            $matrix=$this->getContent(); //mendapatkan konten file 1db menjadi matrix
            //left pipelines dan right pipelines itu bertolak belakang. jika ada yang kiri maka tidak ada yang kanan, vice versa
            $leftPipelines="|";
            $rightPipelines="|";
            if($col>$this->getCol()){ //jika melampui batas kolom maka tambahkan pipeline
                $rightPipelines="";//declare string
                for($i=1; $i<=$col-$this->getCol(); $i++){ //tambahkan pipeline sebanyak yang kurang
                    $rightPipelines=$rightPipelines."|";
                }
                $col=$this->getCol(); //atur arg kolom ke max kolom
                $leftPipelines="";
            }
            for($i=0; $i<=count($matrix)-1; $i++){
                $matrix[$i][$col]=$leftPipelines.$matrix[$i][$col].$rightPipelines; //untuk setiap baris pada matriks tambakan pipeline
            }
            $this->putContent($this->implode2D($matrix,"\n","|")); //simpan ke file dengan memecah matrix menjadi string
        }
    }

    function insertRow( $row,bool $increaseID=true){ //menambahkan baris dengan data kosong sesuai dengan argumen row yang diminta
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        //jika argumen kolom kelebihan maka tambahkan kolom sebanyak yang kurang
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if($row>=0 and $row<=$this->getRow()+1){ //argumen harus logis (tidak melebihi jumlah kolom dan baris pada file)
                //jika kelebihan 1 maka baris baru akan ditambahkan
                $matrix=$this->getContent(); //mengambil array 2D
                $pipelines=[]; //banyaknya elemen berdasarkan banyaknya pipeline atau kolom-1
                for ($i=0; $i<=$this->getCol(); $i++){ //$i adalah posisi kolom skrng
                    if($increaseID and $i==$this->getHeaderPos("ID")){ //naikkan ID jika sekarang ada di posisi header ID
                        $this->incrementID(); //naikkan ID otomatis
                        array_push($pipelines,$this->getLastID()); //isi ID di kolom ke 0
                    }
                    else{
                        array_push($pipelines,""); //isi sesuatu yang kosong, biarkan $this->implode2D mengisi pipeline
                    }
                }
                array_splice($matrix, $row, 0, array($pipelines)); //tambahkan kosong di posisi spesifiks
                $this->putContent($this->implode2D($matrix,"\n","|")); //simpan ke file dengan memecah matrix menjadi string
            }
            else{ //jika argumennya tak logis maka tambahkan baris baru
                $rowTotal=$this->getRow();
                for($i=1; $i<=$row-$rowTotal; $i++){ //tambahkan baris baru sebanyak yang kurang
                   $this->insertRow($this->getRow()+1); //panggil fungsi rekursif dengan baris kelebihan 1
                }
            }
        }
    }

    function getRowPos($col,string $data){ //mengambil posisi baris dari data pada kolom (Hampis sama seperti getIDPos)
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        //PASTIKAN BARIS TERSEBUT UNIK ATAU TIDAK SAMA DENGAN LAINNYA, JIKA ADA YANG SAMA MAKA YANG HASILNYA BARIS YANG TERKECIL DIAMBIL
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($col)){ //konversi ke indeks jika argumen kolomnya berupa nama (string)
                $col=$this->getHeaderPos($col);
            }
            return array_search($data,$this->getColData($col));
        }
    }


    function getData($row,$col){ //mendapatkan single data dari baris dan kolom dan mengembalikan dalam bentuk string
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($col)){ //konversi ke indeks jika argumen kolomnya berupa nama (string)
                $col=$this->getHeaderPos($col);
            }
            if(is_string($row)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $row=$this->getIDPos($row);
            }
            if($col>=0 and $col<=$this->getCol() and $row>=0 and $row<=$this->getRow()){ //pastikan argumennya logis
                return $this->getContent()[$row][$col]; //return string
            }
        }
    }

    function setData($row,$col,string $data,bool $increaseID=true){ //mengatur single data dari baris dan kolom
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($col)){ //konversi ke indeks jika argumen kolomnya berupa nama (string)
                $col=$this->getHeaderPos($col);
            }
            if(is_string($row)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $row=$this->getIDPos($row);
            }
            if($col>=0 and $col<=$this->getCol() and $row>=0 and $row<=$this->getRow()){ //argumen harus logis (tidak melebihi jumlah kolom dan baris pada file)
                $matrix=$this->getContent();
                $matrix[$row][$col]=$data;
                $this->putContent( $this->implode2D($matrix,"\n","|"));
            }
            else if($row>$this->getRow()){ //jika kolom barisnya tak cukup maka tambahkan baris baru
               $this-> insertRow($row,$increaseID); //tambahkan baris kosong agar barisnya cukup
                $this->setData($row,$col,$data,false); //karena sudah memenuhi syarat arguemnnya maka atur datanya
            }
            else if($col>$this->getCol()){ //jika kolom barisnya tak cukup maka tambahkan baris baru
                $this->insertCol($col); //tambahkan kolom kosong agar barisnya cukup
                $this->setData($row,$col,$data,false); //karena sudah memenuhi syarat arguemnnya maka atur datanya
            }
        }
    }

    function deleteRow($row){ //menghapus baris pada baris ke $row
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($row)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $row=$this->getIDPos($row);
            }
            if($row>=0 and $row<=$this->getRow()){ //pastikan argumennya logis
                $matrix=$this->getContent();
                unset($matrix[$row]); //menghapus array tapi tidak menghapus key nya
                $matrix=array_values($matrix); //menyusun key matrix agar berurutan dari 0
                $this->putContent($this->implode2D($matrix,"\n","|"));
                return true; //jika banyak baris tidak sesuai argumen maka return false
            }
            else{
                return false; //jika banyak baris tidak sesuai argumen maka return false
            }
        }
    }

    function deleteCol($col){ //menghapus kolom sesuai dengan kolom yang diminta
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($col)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $col=$this->getIDPos($col);
            }
            if($col>=0 and $col<=$this->getCol()){ //pastikan argumennya logis
                $matrix=$this->getTransposedContent(); //mengambil konten yang di-transpose
                unset($matrix[$col]); //menghapus array tapi tidak menghapus key nya
                $matrix=array_values($matrix); //menyusun key matrix agar berurutan dari 0
                $this->putContent($this->implode2D(array_map($this->nullify, ...$matrix),"\n","|")); //simpan file dari matriks yang di tranpose 2kali ke string
            }
        }
    }

    function moveRow($from,$to){ //memindahkan posisi row ke row lainnya
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($from)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $from=$this->getIDPos($from);
            }
            if(is_string($to)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $to=$this->getIDPos($to);
            }
            if($from>=0 and $from<=$this->getRow() and $to>=0 and $to<=$this->getRow() ){ //pastikan argumennya logis
                $matrix=$this->getContent(); //ambil kontent
                $out = array_splice($matrix, $from, 1); //ini dari stack overflow hwhwhw, buat mindahin baris
                array_splice($matrix, $to, 0 ,$out); //ini dari stack overflow hwhwhw, buat mindahin baris
                $this->putContent($this->implode2D($matrix,"\n","|")); //simpan konten ke DB
            }
            else if($to>$this->getRow()){ //jika destinasinya lebih dari banyak baris pada DB maka tambahkan baris
               $this-> insertRow($to); //insert row sampai memenuhi/logis
                $this->moveRow($from,$to); //panggil fungsi rekursif karena arg sudah logis
            }
            else if($from>$this->getRow()){ //jika asalnya lebih dari banyak baris pada DB maka tambahkan baris
               $this-> insertRow($from); //insert row sampai memenuhi/logis
                $this->moveRow($from,$to); //panggil fungsi rekursif karena arg  logis
            }
        }
    }

    function moveCol($from,$to){ //memindahkan posisi row ke row lainnya
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($from)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $from=$this->getHeaderPos($from);
            }
            if(is_string($to)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $to=$this->getHeaderPos($to);
            }
            if($from>=0 and $from<=$this->getCol() and $to>=0 and $to<=$this->getCol() ){ //pastikan argumennya logis
                $matrix=$this->getTransposedContent(); //ambil kontent transpos
                $out = array_splice($matrix, $from, 1); //ini dari stack overflow hwhwhw, buat mindahin baris
                array_splice($matrix, $to, 0 ,$out); //ini dari stack overflow hwhwhw, buat mindahin baris
                $this->putContent($this->implode2D(array_map($this->nullify, ...$matrix),"\n","|")); //simpan konten ke DB
            }
            else if($to>$this->getCol()){ //jika destinasinya lebih dari banyak baris pada DB maka tambahkan baris
                $this->insertCol($to); //insert row sampai memenuhi/logis
                $this->moveCol($from,$to); //panggil fungsi rekursif karena arg sudah logis
            }
            else if($from>$this->getCol()){ //jika asalnya lebih dari banyak baris pada DB maka tambahkan baris
                $this->insertCol($from); //insert row sampai memenuhi/logis
                $this->moveCol($from,$to); //panggil fungsi rekursif karena arg  logis
            }
        }
    }

    function swapRow($row1, $row2){ //menukar posisi dua row
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($row1)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $row1=$this->getIDPos($row1);
            }
            if(is_string($row2)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $row2=$this->getIDPos($row2);
            }
            if($row1>=0 and $row1<=$this->getRow() and $row2>=0 and $row2<=$this->getRow() ){ //pastikan argumennya logis
                $matrix=$this->getContent(); //ambil DB
                $row1Data=$matrix[$row1];//simpan data baris awal
                $row2Data=$matrix[$row2]; //simpan data baris akhir
                $matrix[$row1]=$row2Data; //swap
                $matrix[$row2]=$row1Data; //swap
                $this->putContent($this->implode2D($matrix,"\n","|")); //simpan konten ke DB

            }
            else if($row2>$this->getRow()){ //jika destinasinya lebih dari banyak baris pada DB maka tambahkan baris
               $this-> insertRow($row2); //insert row sampai memenuhi/logis
                $this->swapRow($row1,$row2); //panggil fungsi rekursif karena arg sudah logis
            }
            else if($row1>$this->getRow()){ //jika asalnya lebih dari banyak baris pada DB maka tambahkan baris
               $this-> insertRow($row1); //insert row sampai memenuhi/logis
                $this->swapRow($row1,$row2); //panggil fungsi rekursif karena arg  logis
            }
        }
    }

    function swapCol( $col1, $col2){ //menukar posisi dua row
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($col1)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $col1=$this->getHeaderPos($col1);
            }
            if(is_string($col2)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $col2=$this->getHeaderPos($col2);
            }
            if($col1>=0 and $col1<=$this->getCol() and $col2>=0 and $col2<=$this->getCol() ){ //pastikan argumennya logis
                $matrix=$this->getTransposedContent(); //ambil DB tranpose
                $col1Data=$matrix[$col1];//simpan data baris awal
                $col2Data=$matrix[$col2]; //simpan data baris akhir
                $matrix[$col1]=$col2Data; //swap
                $matrix[$col2]=$col1Data; //swap
                $this->putContent($this->implode2D(array_map($this->nullify, ...$matrix),"\n","|")); //simpan konten ke DB

            }
            else if($col2>$this->getCol()){ //jika destinasinya lebih dari banyak baris pada DB maka tambahkan baris
                $this->insertCol($col2); //insert row sampai memenuhi/logis
                $this->swapCol($col1,$col2); //panggil fungsi rekursif karena arg sudah logis
            }
            else if($col1>$this->getCol()){ //jika asalnya lebih dari banyak baris pada DB maka tambahkan baris
                $this->insertCol($col1); //insert row sampai memenuhi/logis
                $this->swapCol($col1,$col2); //panggil fungsi rekursif karena arg  logis
            }
        }
    }
    
    function pushUpRow(){ //membuat posisi semua pada baris terdorong ke atas
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            $this->moveRow(1,$this->getRow());
        }
    }

    function pushDownRow(){ //membuat posisi semua baris terdorong kebawah
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            $this->moveRow($this->getRow(),1);
        }
    }

    function setRowData($row,array $data){ //$data adalah data baris dalam bentuk array
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($row)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $row=$this->getIDPos($row);
            }
            if($data!=null and $data!="" and is_array($data)){
                if(count($data)>=$this->getCol()){ //jika argumemnya kelebihan atau sama dari banyak kolom
                    for($i=0; $i<=$this->getCol()-1; $i++){
                        if($data[$i]!="^&"){ // # char itu "^&" agar datanya tidak berubah, digunakan untuk argumen biar default
                            $this->setData($row,$i+1,$data[$i]);
                        }
                        else{ //jika argumennya mengandung char spesial itu
                            $this->setData($row,$i+1,$this->getContent()[$row][$i+1]); //atur datanya default
                        }
                    }
                }
                else{ //panggil fungsi rekursif hingga banyaknya argumen==banyak kolom DB
                    array_push($data,""); //tambahkan kosong ke akhir indeks untuk menyempurnakan data array
                    $this->setRowData($row,$data); //panggil fungsi rekursif
                }
                return "argsBerisi";
            }
            else{
                return "argsKosong";
            }
        }
    }

    function setColData($col,array $data){ //$data adalah data baris dalam bentuk array
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($col)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $col=$this->getHeaderPos($col);
            }
            if($data!=null and $data!="" and is_array($data)){
                if(count($data)>=$this->getRow()){ //jika argumemnya kelebihan atau sama dari banyak kolom
                    for($i=0; $i<=$this->getRow()-1; $i++){
                        if($data[$i]!="^&"){ // # char itu "^&" agar datanya tidak berubah, digunakan untuk argumen biar default
                            $this->setData($i+1,$col,$data[$i]);
                        }
                        else{ //jika argumennya mengandung char spesial itu
                            $this->setData($i+1,$col,$this->getContent()[$i+1][$col]); //atur datanya default
                        }
                    }
                }
                else{ //panggil fungsi rekursif hingga banyaknya argumen==banyak kolom DB
                    array_push($data,""); //tambahkan kosong ke akhir indeks
                    $this->setColData($col,$data); //panggil fungsi rekursif
                }
                return "argsBerisi";
            }
            else{
                return "argsKosong";
            }
        }
    }

    function insertRowData($row,array $data){ //$data adalah array
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($row)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $row=$this->getIDPos($row);
            }
            if($row>=0){
                $rowTotal=$this->getRow(); //banyak baris pada database
                $this->setRowData($rowTotal+1,$data); //ciptakan baris baru
                $this->moveRow($rowTotal+1,$row); //pindahkan baris tersebut
            }
        }
    }

    function insertColData($col,array $data){ //$data adalah array
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            if(is_string($col)){ //konversi ke indeks jika argumen barisnya berupa ID (string)
                $col=$this->getHeaderPos($col);
            }
            if($col>=0){
                $colTotal=$this->getCol(); //banyak baris pada database
                $this->setColData($colTotal+1,$data); //ciptakan baris baru
                $this->moveCol($colTotal+1,$col); //pindahkan baris tersebut
            }
        }
    }

    function deleteRowEmptyID(){ //menghapus baris yang tidak memiliki ID
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            $idHeaderPos=$this->getHeaderPos("ID"); //posisi header ID
            $colData=$this->getColData($idHeaderPos);
            while(array_search("",$colData)){ //lakukan sampai tidak ketemu
                $emptyRowID=array_search("",$colData); //ID kosong pada sebuah baris ketemu
                if($emptyRowID!=$idHeaderPos){
                    $this->deleteRow($emptyRowID); //hapus
                }
                $colData=$this->getColData($idHeaderPos); //refresh
            }
        }
    }

    function clearRowData($row){ //membersihkan semua data di sebuah baris
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            $this->setRowData($row,[""]); //argumen [""] berarti tidak null, tidak "", namun sebuah array yang berisi satu elemen dengan data kosong
        }
    }

    function clearColData($col){ //membersihkan semua data di sebuah kolom
        $db=$this->DBName; //memanggil atribut nama DB dari konstruktor
        if($this->isDBExist()){ //mengecek apakah DBnya ada?
            $this->setColData($col,[""]); //argumen [""] berarti tidak null, tidak "", namun sebuah array yang berisi satu elemen dengan data kosong
        }
    }

};