WaOne Database (1db) adalah database noSQL buatan Muhammad Ikhwan Perwira. https://www.facebook.com/wawan.ikhwan
Dibuat pada 28 February 2021 (Tahun Kabisat)
COPYRIGHT

Cara kerja DataBase:
    -Directory (folder) "1db" haruslah diletakkan di puncak tertinggi dari public atau di document root. misal: "localhost:80/1db/
    -file "/1db/index.php" merupakan web pusat kendali mengontrol database, seperti halnya phpmyadmin.
    -file "/1db/class.php" layaknya library.
        disinilah syntax/command/perintah terhadap database seperti menambahkan data, menambahkan DB baru, mengambil data, dsb.
        diperlukan perintah (require_once $_SERVER['DOCUMENT_ROOT']."/1db/class.php";") untuk menggunakan fungsi tersebut pada file.php
    -Perlu diperhatikan (End Of Line) atau ending baris yang digunakan hanyalah LF atau LineFeed (\n)
        Pada windows biasanya defaultnya adalah CRLF atau CarriageReturnLineFeed (\r\n)
    -direktori "/1db/databases" menyimpan beberapa direktori DB
    -database "/1db/databases/databaseExample.1db" adalah DB contoh atau format file database ini, digunakan oleh fungsi "createDB()"
        untuk menciptakan database lainnya tanpa perlu repot copy-paste.
        Perhatikan Contohnya dibawah ini!

ID|Header1|Header2|Header3|
1|Col1_Row1|Col2_Row1|Col3_Row1|
4|Col1_Row2|Col2_Row2|Col3_Row2|
9|Col1_Row3|Col2_Row1|Col3_Row3|
\n
        *kolom 1 dan seterusnya adalah isi data, sedangkan kolom ke 0 adalah ID baris.
        *Posisi kolom ID bisa dirubah dari kolom 0 ke lainnya tapi saya tidak menanggung BUG yang akan terjadi.
        *nilai ID AUTO_INCREMENT (otomatis naik) berdasarkan file lastID.id pada tiap direktori database. (walau bisa dimatikan pada argumen terakhir fungsi setData)
        *baris 1 dan seterusnya adalah isi data, sedangkan baris ke 0 adalah baris HEADER atau baris untuk judul KOLOM
        !!! Lebih Baik file "databaseExample.1db" jangan dihapus/diubah, karena itu dibuat untuk menciptakan DB lainnya !!!
    -untuk file "lastID.id" pada tiap direktori database, merupakan nilai yang menampung ID terakhir pada database,
        *mengingat tiap ID itu tidak sama untuk tiap barisnya maka lebih baik kolom ID tidak perlu dihapus.

Algoritma Database:
    -Menggunakan Separator pipeline "|" sebagai detektor kolom.
    -Menggunakan Separator newline "\n" sebagai detektor baris.
    -Jika ingin mengedit nilai file database secara langsung melalui text editor, EOL harus diatur ke LF bukannya CRLF.
    -fungsi explode2D dan implode2D ditambahkan untuk mengkonversi string menjadi array2D dan array2D menjadi string
    -Matrix adalah kata lain dari array2D
    -Bayangkan sadja bahwa baris dan kolom disini tak terhingga layaknya excel, jadi tidak usah khawatir dalam mengatur data
    -Memperlakukan Kolom sama halnya dengan memperlakukan Baris, tinggal di-Transpose sadja!
        Fungsi Transpose array2D: array_map(null, ...$array2D);

Keterangan fungsi:
    *Parameter untuk baris dan kolom boleh integer ataupun string
        -Jika integer, maka posisinya berdasarkan urutan indeks misal baris 0 adalah header
            Misal:
            getRowData(1);
            getColData(1);
        -Jika string, maka paramaternya berdasarkan nama dari header/ID 
            Misal:
            getRowData("67");
            getColData("Nama");
        *Ini terjadi karena adanya fungsi yang mengkonversi paramater baris dan kolom menjadi integer
            -Fungsi tersebut adalah getIDPos, getHeaderPos, getRowPos
        *Adapun char spesial &^ untuk argumen mengisi data pada baris/kolom
            -Char itu digunakan untuk mempertahankan data agar tidak berubah
            Misal:
            setRowData(3,["Ujang","&^","17 tahun"]); maka data pada baris ke 3 dan kolom ke 2 tidak berubah

JavaScript untuk auto update data tanpa refresh!
        
Methods:
createDB(); //membuat DB baru
isDBExist(); //mengecek apakah DB sudah ada atau belum
getIDPath(); //mendapatkan path dimana lastID.id disimpan
getLastID(); //mendapatkan nilai lastID.id
incrementID(); //menaikkan nilai ID (mengubah file lastID.id)
getHeaderPos($header); //mengambil posisi kolom berdasarkan nama header (baris 0)
getIDPos($ID); //mengambil posisi baris berdasarkan nilai ID (kolom 0)
getContent(); //mengambil isi content file database (return array/string)
putContent($data); //menaruh data ke file database (OVERWRITE!!!)
getDBLength(); //panjang DB (file.1db)
getRow(); //mengambil banyak baris pada file database
getRowData($row,$rawData=false); //mendapatkan data baris ke-n,
getCol(); //mendapatkan banyaknya kolom pada file.1db
getColData($col); //mendapatkan data kolom ke-n dalam bentuk array
getData($col,$row); //mendapatkan isi data berdasarkan baris dan kolom
setData($col,$row,$data,$increaseID=true); //mengubah isi data dari baris dan kolom, menambahkan baris dan kolom
getRowPos($col,$data); //mengambil posisi baris dari data pada kolom (Hampis sama seperti getIDPos)
deleteRow( $row); //menghapus baris berdasarka posisi indeks baris (return true jika barisnya ada, return false jika barisnya tidak ada)
    *Karena ada return cocok untuk menghapus baris berulang: while(deleteRow(5)){} //lakukan sampai tersisa 4 baris
deleteCol($col); //menghapus kolom berdasarkan posisi indeks kolom
moveRow($from,$to); //memindahkan row ke posisi lainnya (mempengaruhi posisi kolom lainnya)
moveCol($from,$to); //memindahkan col ke posisi lainnya (mempengaruhi posisi kolom lainnya)
swapRow($row1,$row2); //menukar posisi antara dua baris (tidak mempengaruhi posisi baris lainnya)
swapCol($col1,$col2); //menukar posisi antara dua kolom (tidak mempengaruhi posisi kolom lainnya)
insertRow( $row); //menyisipkan baris dengan data kosong sesuai dengan argumen row yang diminta
pushUpRows(); //membuat posisi semua baris terdorong ke atas
pushDownRows(); //membuat posisi semua baris terdorong ke kebawah
setRowData($row,$data); //mengatur data pada keseluruhan kolom pada sebuah baris, $data adalah array, isi argumen dengan "^&" agar data sebelumnya tidak diubah, menambahkan baris baru
setColData($col,$data); //mengatur data pada keseluruhan baris pada sebuah kolom, $data adalah array, isi argumen dengan "^&" agar data sebelumnya tidak diubah , menambahkan kolom baru
insertRowData($row,$data); //menyisipkan data pada keseluruhan kolom pada sebuah baris, $data adalah array, isi argumen dengan "^&" agar data sebelumnya tidak diubah, menambahkan baris baru
insertColData($col,$data); //menyisipkan data pada keseluruhan baris pada sebuah kolom, $data adalah array, isi argumen dengan "^&" agar data sebelumnya tidak diubah , menambahkan kolom baru
deleteRowEmptyID(); //menghapus baris yang tidak memiliki ID
clearRowData($row); //membersihkan keseluruhan data pada sebuah baris
clearColData($col); //membersihkan keseluruhan data pada sebuah kolom