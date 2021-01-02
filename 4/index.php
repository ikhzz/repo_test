<?php
  // Database Parameter
  $userDb = "root";
  $passDb = "";
  $serverName = "localhost";
  $dbName = "mobileGanggu";

  // Buat Koneksi ke Localhost
  $connLh = mysqli_connect($serverName, $userDb, $passDb);
  // Buat Koneksi ke Database
  
  // Fungsi Membuat Database
  function createDb($conn, $user ,$pass, $dbName, $server){
    // Query Membuat Database Jika Database Belum Ada
    $query = "CREATE DATABASE IF NOT EXISTS mobileGanggu";
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
    $query1 = "CREATE TABLE role(
      id INT(6) AUTO_INCREMENT,
      names VARCHAR(30) NOT NULL,
      PRIMARY KEY(id)
    )";

    $query2 = "CREATE TABLE hero(
      id INT(6) AUTO_INCREMENT,
      name VARCHAR(30) NOT NULL,
      id_role INT(6),
      image VARCHAR(30) NOT NULL,
      deskripsi VARCHAR(150),
      PRIMARY KEY (id),
      FOREIGN KEY (id_role) REFERENCES role(id)
    )";

    mysqli_query($conn, $query1);
    if(mysqli_query($conn, $query2)){
      defaultData($conn);
      echo "table created";
    }
    // mysqli_close($conn);
  }

  // Fungsi Input Default Data
  function defaultData($conn){
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
    // Mengisi Data Default ke Tabel role
    $userQuery = "INSERT INTO role (names)
    VALUES ('Melee-Support'),
      ('Ranged-Support'),
      ('Ranged-Carry'),
      ('Melee-Carry')
    ";
    // Mengisi Data Defaul ke Tabel hero
    $contentQuery = "INSERT INTO hero (name, id_role, image, deskripsi) 
    VALUES ('Treant', 1, 'treant.jpeg', 'Far to the west, in the mountains beyond the Vale of Augury, lie the remains of an ancient power '), 
        ('Viper', 3, 'viper.jpeg', 'The malevolent familiar of a sadistic wizard who captured and hoped to tame him'), 
        ('Venge', 2, 'venge.jpeg', 'Even the most contented Skywrath is an ill-tempered creature, naturally inclined to seek revenge for the slightest insult. '), 
        ('Nyx', 4, 'nyx.jpeg', 'Deep in the Archive of Ultimyr, shelved between scholarly treatises on dragon cladistics and books of untranslatable spells'), 
        ('Bane', 2, 'bane.jpeg', 'When the gods have nightmares, it is Bane Elemental who brings them. Also known as Atropos, Bane was born from the midnight terrors '), 
        ('Winter Wyvern', 2, 'winterwyvern.jpeg', 'Like many great poets, Auroth just wants time to write, but the Winter Wyvern life full of interruptions. ')
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
  
  $queryFilter = mysqli_query($connDb, 'SELECT * FROM role');
  $queryFilter2 = mysqli_query($connDb, 'SELECT * FROM role');

  if(isset($_POST['filter']) && $_POST['role'] !== '*'){
    $roles = $_POST['role'];
    $queryContent = mysqli_query($connDb, "SELECT *, role.names FROM hero LEFT JOIN role ON hero.id_role = role.id WHERE id_role = '$roles' ");
  } else {
    $queryContent = mysqli_query($connDb, 'SELECT *, role.names FROM hero LEFT JOIN role ON hero.id_role = role.id ');
  }

  // Menunggu Permintaan Menambahkan Role
  if(isset($_POST['inputRole'])){
    $newRole = $_POST['newRole'];
    $queryRole = "INSERT INTO role (names) VALUES ('$newRole')";
    
    mysqli_query($connDb, $queryRole);
    header("Location: index.php");
  }
  // Menunggu Permintaan Menambahkan Hero
  if(isset($_POST['inputHero'])){
      // Mengecek Apakah ada Gambar Yang Akan di Input
      if($_FILES['heroImage']['size'] > 0){
        $heroName = $_POST['heroName'];
        $heroRole = $_POST['heroRole'];
        $heroImage = $_FILES['heroImage']['name'];
        $heroDesc = $_POST['heroDesc'];
        move_uploaded_file($_FILES['heroImage']['tmp_name'], 'img/'. $heroImage);
        $queryAddHero = "INSERT INTO hero (name, id_role, image, deskripsi)
        VALUES ('$heroName', $heroRole, '$heroImage', '$heroDesc')
        ";
        mysqli_query($connDb, $queryAddHero);
      }
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
  <!-- Navigasi, Opsi Konten dan Filter -->
  <ul class="nav">
    <li>
      <h3>Mobile Ganggu</h3>
    </li>
    <li>
      <!-- Check Session dan Tampilkan Pilihan -->
      <button class="addHero">Tambahkan Hero</button>
      <button class="addRole" >Tambahkan Role</button>
      <form action="" method="post">
      <label for="filterHero">Filter Hero:</label>
        <select name="role" id="role">
          <option value="*">Semua</option>
          <?php while($filter = (mysqli_fetch_assoc($queryFilter))):?>
            <option value="<?=$filter['id']?>"><?= $filter['names']?></option>
          <?php endwhile;?>
        </select>
        <button name='filter'>Pilih Filter</button>
      </form>
      
    </li>
  </ul>
  <!-- Opsi -->
  <ul class="option">
    <!-- Opsi Untuk Login -->
    <li class="roleOption">
      <form action="" method="post">
        <h3>Tambahkan Role</h3>
        <label for="newRole">Role Baru: </label>
        <input type="text" name="newRole">
        <div>
          <button name="inputRole">Konfirmasi Role</button>
          <button>Batalkan</button>
        </div>
      </form>
    </li>
    <!-- Opsi Menambahkan Hero -->
    <li class="heroOption">
      <form  action="" method="post" enctype="multipart/form-data">
        <h3>Tambahkan Hero</h3>
        <label for="heroName">Hero Name:</label>
        <input type="text" name="heroName">
        <label for="heroImage">Image Hero:</label>
        <input type="file" name="heroImage">
        <label for="heroRole">Filter Hero:</label>
        <select name="heroRole" id="heroRole">
          <?php while($filters = (mysqli_fetch_assoc($queryFilter2))):?>
            <option value="<?=$filters['id']?>"><?= $filters['names']?></option>
          <?php endwhile;?>
        </select>
        <label for="heroDesc">Deskripsi Hero</label>
        <input type="textarea" name="heroDesc">
        <div>
          <button name="inputHero">Konfirmasi Hero</button>
          <button>Batalkan</button>
        </div>
      </form>
    </li>
  </ul>
  <ul class="main">
    <!-- Loop Semua Data -->
    <?php while($data = (mysqli_fetch_assoc($queryContent))):?>
    <li>
      <!-- Detail Deskripsi Hero -->
      <div class="detail">
        <div class="close">x</div>
        <h4>Deskripsi: <?= $data['deskripsi']?></h4>
      </div>
      <img src="img/<?= $data['image']?>" alt="" srcset="">
      <h4><?= $data['name']?></h4>
      <p><?=$data['names']?></p>
      <button class="showDetails">Detail</button>
    </li>
    <?php endwhile;?>
  </ul>
  <script src="script.js"></script>
</body>
</html>