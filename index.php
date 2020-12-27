<?php
  session_start();
  // Database Parameter
  $userDb = "root";
  $passDb = "";
  $serverName = "localhost";
  $dbName = "waysGram";

  // Buat Koneksi ke Localhost
  $connLh = mysqli_connect($serverName, $userDb, $passDb);
  // Buat Koneksi ke Database
  
  // Fungsi Membuat Database
  function createDb($conn, $user ,$pass, $dbName, $server){
    // Query Membuat Database Jika Database Belum Ada
    $query = "CREATE DATABASE IF NOT EXISTS waysGram";
    // Cek Koneksi ke Database
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
    // Buat Database
    if (mysqli_query($conn, $query)) {
      echo "Database created successfully";
    } else {
      echo "Error creating database: " . mysqli_error($conn);
    }
    $connDb = mysqli_connect($server, $user, $pass, $dbName);
    createTable($connDb);
    // Tutup Koneksi Setelah Database di Buat
    // mysqli_close($conn);
  }
  // Fungsi Membuat Tabel Database
  function createTable($conn){
    // Query Membuat Tabel
    $query1 = "CREATE TABLE users_tb(
      id INT(6) AUTO_INCREMENT,
      name VARCHAR(30) NOT NULL,
      photo VARCHAR(30) NOT NULL,
      email VARCHAR(30) NOT NULL,
      password VARCHAR(30) NOT NULL,
      PRIMARY KEY(id)
    )";

    $query2 = "CREATE TABLE post_tb(
      id INT(6) AUTO_INCREMENT,
      content VARCHAR(30) NOT NULL,
      image VARCHAR(30) NOT NULL,
      users_id INT(6),
      PRIMARY KEY (id),
      FOREIGN KEY (users_id) REFERENCES users_tb(id)
    )";

    mysqli_query($conn, $query1);
    if(mysqli_query($conn, $query2)){
      echo "table created";
      defaultData($conn);
    }
    // mysqli_close($conn);
  }

  // Fungsi Input Default Data
  function defaultData($conn){
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
    // Mengisi Data Default ke Tabel User
    $userQuery = "INSERT INTO users_tb (name, photo, email, password)
    VALUES ('Ikhz', 'ikhzprofile.jpg', 'ikhz@gmail.com', '123456'),
      ('Fikri', 'fikriprofile.jpg', 'fikriztm@gmail.com', '123456')
    ";
    // Mengisi Data Defaul ke Tabel Post
    $contentQuery = "INSERT INTO post_tb (content, image, users_id) 
    VALUES ('tech', 'tech1.jpg', 1), 
        ('tech', 'tech2.jpg', 1), 
        ('tech', 'tech3.jpg', 1), 
        ('scenery', 'scenery1.jpg', 2), 
        ('scenery', 'scenery2.jpeg', 2), 
        ('scenery', 'scenery3.jpeg', 2)
    ";
    
    // Isi Data Default ke Masing Masing Tabel
    if (mysqli_query($conn, $userQuery)) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    if (mysqli_query($conn, $contentQuery)) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
  }
    // Jalankan Fungsi Pembuatan awal Database
  createDb($connLh, $userDb, $passDb, $dbName, $serverName);
  // Buat Koneksi Baru ke Database Yang Telah di Buat
  $connDb = mysqli_connect($serverName, $userDb, $passDb, $dbName);
  // Query Data Content Untuk di Tampilkan
  $queryContent = mysqli_query($connDb, 'SELECT * FROM post_tb');

  // Menunggu Permintaan Login
  if(isset($_POST['inputLogin'])){
    // Ambil Parameter Email dan Password
    $userEmail = $_POST['userEmail'];
    $userPass = $_POST['userPass'];
    // Buat Query Berdasarkan Parameter
    $queryCredential = "SELECT * FROM users_tb WHERE email = '$userEmail' AND password = '$userPass'";
    // Eksekusi Query Simpan di Dalam Variabel
    $result = mysqli_query($connDb, $queryCredential);
    // Cek Apakah Query Mengembalikan hasil 
    if(mysqli_num_rows($result) === 1){
      $ids = mysqli_fetch_assoc($result);
      // Set Session Untuk Membuka Pilihan Menambahkan, Mengedit dan Delete Content
      $_SESSION['login'] = true;
      $_SESSION['user'] = $ids['id'];
    } else {
      // Jika gagal kembalikan user ke home page
      header("Location: index.php");
    }
  }
  // Menunggu Permintaan Menambahkan Content
  if(isset($_POST['inputContents'])){
      // Mengecek Apakah ada Gambar Yang Akan di Input
      if($_FILES['contentImage']['size'] > 0){
        $inputContentUser = $_POST['contentUser'];
        $contentName = $_POST['contentName'];
        $inputContentImageName = $_FILES['contentImage']['name'];
        move_uploaded_file($_FILES['contentImage']['tmp_name'], 'img/'. $inputContentImageName);
        $queryAddContent = "INSERT INTO post_tb (content, image, users_id)
        VALUES ('$contentName', '$inputContentImageName', $inputContentUser)
        ";
        mysqli_query($connDb, $queryAddContent);
      }
  }
  // Menunggu Permintaan Logout
  if(isset($_POST['logOut'])){
    session_destroy();
    header("Location: index.php");
  }
  // Menunggu Permintaan Edit Content
  if(isset($_POST['inputEdit'])){
    // Cek Apakah Ada Gambar Untuk di Ganti
    if($_FILES['inputImage']['size'] > 0) {
      $contentId = $_POST['inputContentId'];
      $content = $_POST['inputContent'];
      $imageName = $_POST['inputImage']['name'];
      move_uploaded_file($_FILES['inputImage']['tmp_name'], 'img/'. $imageName);
      $inputContent = "UPDATE post_tb SET content = '$content' AND image = '$imageName' WHERE id = '$contentId' ";
      mysqli_query($connDb, $inputContent);
      header("Location: index.php");
    } else {
      // Ganti Content Tanpa Gambar
      $contentId = $_POST['inputContentId'];
      $content = $_POST['inputContent'];
      $inputContent = "UPDATE post_tb SET content = '$content' WHERE id = '$contentId' ";
      mysqli_query($connDb, $inputContent);
      header("Location: index.php");
    }  
  }
  // Menuggu Permintaan Delete
  if(isset($_POST['delete'])){
    $deleteId = $_POST['deleteId'];
    mysqli_query($connDb, "DELETE FROM post_tb WHERE id = $deleteId");
    header("Location: index.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Dumbways test</title>
</head>
<body>
  <!-- Navigasi, Login, Logout dan Menambahkan Content -->
  <ul class="nav">
    <li>
      <h3>waysGram</h3>
    </li>
    <li>
      <!-- Check Session dan Tampilkan Pilihan -->
      <?php if(!isset($_SESSION['login'])){ ?>
      <button class="login">Login</button>
      <?php }else{ ?>
      <button class="addContent">Tambahkan Content</button>
      <form action="" method="post">
      <button name="logOut">Log Out</button>
      </form>
      <?php }?>
    </li>
  </ul>
  <!-- Opsi -->
  <ul class="option">
    <!-- Opsi Untuk Login -->
    <li class="loginOption">
      <form action="" method="post">
        <h3>Login</h3>
        <label for="userEmail">User Email: </label>
        <input type="text" name="userEmail">
        <label for="userPass">User Password: </label>
        <input type="text" name="userPass">
        <div>
          <button name="inputLogin">Login</button>
          <button>Batalkan</button>
        </div>
      </form>
    </li>
    <!-- Opsi Menambahkan Content -->
    <li class="contentOption">
      <form  action="" method="post" enctype="multipart/form-data">
        <h3>Tambahkan Content</h3>
        <label for="contentName">Content:</label>
        <input type="text" name="contentName">
        <label for="contentImage">Image Content:</label>
        <input type="file" name="contentImage">
        <input type="hidden" value="<?= $_SESSION['user']?>" name="contentUser">
        <div>
          <button name="inputContents">Tambah Content</button>
          <button>Batalkan</button>
        </div>
      </form>
    </li>
  </ul>
  <ul class="main">
    <!-- Loop Semua Data -->
    <?php while($data = (mysqli_fetch_assoc($queryContent))):?>
    <li>
      <!-- Form untuk Mengedit Content -->
      <form class="edits" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="inputContentId" value="<?=$data['id']?>">
        <input name="inputContent" type="text" value="<?=$data['content']?>">
        <input name="inputImage" type="file">
        <button name="inputEdit">Konfirmasi</button>
        <button>Batalkan</button>
      </form>
      <img src="img/<?= $data['image']?>" alt="" srcset="">
      <h4><?= $data['content']?></h4>
      <?php if(isset($_SESSION['login'])):?>
        <?php if($_SESSION['user'] == $data['users_id']):?>
          <button class="edit">Edit</button>
          <form action="" method="post">
            <input type="hidden" name="deleteId" value="<?=$data['id']?>">
            <button name="delete">Delete</button>
          </form>
        <?php endif; ?>
      <?php endif;?>
    </li>
    <?php endwhile;?>
  </ul>
  <script src="script.js"></script>
</body>
</html>