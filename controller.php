<?php
session_start();
include_once('connectDb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['loginSubmit'])) {
        $username = strtolower(stripslashes($_POST['usrname']));
        $password = $_POST['pass'];
        // cek username di DB
        $cek_user = $pdo->prepare("SELECT * FROM `user` WHERE `username` = '$username';");
        try {
            $cek_user->execute();
            if ($cek_user->rowCount() == 1) {
                // cek password
                $baris = $cek_user->fetchAll(PDO::FETCH_ASSOC);
                if (password_verify($password, $baris[0]['password'])) {
                    $_SESSION['login'] = true;
                    $_SESSION['username'] = $baris[0]['username'];
                    $_SESSION['akses'] = $baris[0]['akses'];
                    echo "<meta http-equiv='refresh' content='0; url=loggedDashboard.php'>";
                    die();
                }
            }
        } catch (PDOException $e) {
            echo "Error " . $e->getMessage();
        }
        $_SESSION['error'] = 'Username dan Password Tidak Cocok';
        echo "<meta http-equiv='refresh' content='0; url=login.php'>";
        die();
    } else if (isset($_POST['daftar'])) {
        $_SESSION['pesan'] = '';

        $username = strtolower(stripslashes($_POST['username']));
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];
        $akses = $_POST['akses'];

        // cek duplikat username di DB
        $cek_user = $pdo->prepare("SELECT `username` FROM `user` WHERE `username` = '$username';");
        try {
            $cek_user->execute();
            if ($cek_user->fetchAll(PDO::FETCH_ASSOC)) {
                $_SESSION['error'] = true;
                $_SESSION['pesan'] = "Username sudah digunakan";
                goto end;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // cek kemiripan password dan konfirmasi password
        if ($password1 !== $password2) {
            $_SESSION['error'] = true;
            $_SESSION['pesan'] = "Konfirmasi Password tidak sesuai!";
            goto end;
        }

        // enkripsi password
        $password = password_hash($password1, PASSWORD_DEFAULT);

        // masukan data ke DB
        $query = $pdo->prepare("INSERT INTO `user` (`username`, `password`, `akses`) VALUES(:usrname, :pass, :akses);");
        $query->bindParam(':usrname', $username);
        $query->bindParam(':pass', $password);
        $query->bindParam(':akses', $akses);

        // cek kesuksesan data masuk DB
        try {
            $query->execute();
            $_SESSION['didit'] = true;
            $_SESSION['pesan'] = "User Baru berhasil ditambahkan!";
            goto end;
        } catch (PDOException $e) {
            $_SESSION['error'] = true;
            $_SESSION['pesan'] = "Ada sesuatu yang salah!";
            echo $e->getMessage();
            goto end;
        }
        end:
        echo "<meta http-equiv='refresh' content='0; url=insertUser.php'>";
    } else if (isset($_POST['upload'])) {
        $_SESSION['pesan'] = '';

        $id = $_GET['id'];
        $judul = $_GET['judul'];

        $kode = $_POST['kode'];
        $fileName = $_POST['fileName'];
            $filePDF = $_FILES['filePDF'];
            $cleanName = str_replace(' ', '_', $filePDF['name']);
        $pdfPath = time() . "_" . $cleanName;

        $query = $pdo->prepare("INSERT INTO `file` VALUES (:kode, :nama, :pathe);");
        $query->bindParam(':kode', $kode);
        $query->bindParam(':nama', $fileName);
        $query->bindParam(':pathe', $pdfPath);

        try {
            $query->execute();
            $uploadPath = 'uploaded/' . $pdfPath;
            move_uploaded_file($filePDF['tmp_name'], $uploadPath);
            $_SESSION['didit'] = true;
            $_SESSION['pesan'] = "File Baru berhasil ditambahkan!";
            goto endd;
        } catch (PDOException $e) {
            $_SESSION['error'] = true;
            $_SESSION['pesan'] = "Ada sesuatu yang salah!";
            echo $e->getMessage();
            goto endd;
        }
        endd:
        echo "<meta http-equiv='refresh' content='0; url=loggedDetailEArsip.php?id=" . $id . "&judul=" . $judul . "'>";
    } else if (isset($_POST['editFile'])) {
        $_SESSION['pesan'] = '';

        $id = $_GET['id'];
        $judul = $_GET['judul'];

        $kode = $_POST['kode'];
        $fileName = $_POST['fileName'];

        $query = $pdo->prepare("UPDATE `file` SET nama=:nama WHERE kode=:kode;");

        if (strlen($_FILES['filePDF']['name']) != 0) {
                $filePDF = $_FILES['filePDF'];
                $cleanName = str_replace(' ', '_', $filePDF['name']);
            $pdfPath = time() . "_" . $cleanName;
            $query = $pdo->prepare("UPDATE `file` SET nama=:nama, `path`=:pathe WHERE kode=:kode;");
            $query->bindParam(':pathe', $pdfPath);

        }

        $query->bindParam(':nama', $fileName);
        $query->bindParam(':kode', $kode);

        try {
            if (strlen($_FILES['filePDF']['name']) != 0) {
                $uploadPath = 'uploaded/' . $pdfPath;
                move_uploaded_file($filePDF['tmp_name'], $uploadPath);

                $path = $pdo->prepare("SELECT `path` FROM `file` WHERE kode=:code");
                $path->bindParam(':code', $kode);
                try {
                    $path->execute();
                    $Path = $path->fetchAll(PDO::FETCH_ASSOC);
                    $fullPath = 'uploaded/' . $Path[0]['path'];
                    unlink($fullPath);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }
            $query->execute();
            $_SESSION['didit'] = true;
            $_SESSION['pesan'] = "File berhasil diedit!";
        } catch (PDOException $e) {
            $_SESSION['error'] = true;
            $_SESSION['pesan'] = "Ada sesuatu yang salah!";
            echo $e->getMessage();
        }
        echo "<meta http-equiv='refresh' content='0; url=loggedDetailEArsip.php?id=" . $id . "&judul=" . $judul . "'>";
    } else {
        unset($_SESSION['login']);
        unset($_SESSION['username']);
        unset($_SESSION['akses']);
        echo "<meta http-equiv='refresh' content='0; url=login.php'>";
        die();
    }
} else {
    if (isset($_GET['aksi'])) {
        $aksi = $_GET['aksi'];
        $_SESSION['pesan'] = '';
        if ($aksi == 'deleteUser') {
            $user = $_GET['user'];
            $query = $pdo->prepare("DELETE FROM user WHERE `user`.`username` = '$user';");
            try {
                $query->execute();
                $_SESSION['didit'] = true;
                $_SESSION['pesan'] = "User berhasil dihapus!";
            } catch (PDOException $e) {
                $_SESSION['error'] = true;
                $_SESSION['pesan'] = "Ada sesuatu yang salah!";
                echo $e->getMessage();
            }
            echo "<meta http-equiv='refresh' content='0; url=loggedManajemen.php'>";
        } else if ($aksi == 'deleteFile') {
            $kode = $_GET['kode'];
            $id = $_GET['id'];
            $judul = $_GET['judul'];

            // Hapus File Local
            $path = $pdo->prepare("SELECT `path` FROM `file` WHERE kode=:code");
            $path->bindParam(':code', $kode);
            try {
                $path->execute();
                $Path = $path->fetchAll(PDO::FETCH_ASSOC);
                $fullPath = 'uploaded/' . $Path[0]['path'];
                unlink($fullPath);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            // Delete Data DB
            $query = $pdo->prepare("DELETE FROM `file` WHERE kode='" . $kode . "'");
            try {
                $query->execute();
                $_SESSION['didit'] = true;
                $_SESSION['pesan'] = "File berhasil dihapus!";
            } catch (PDOException $e) {
                $_SESSION['error'] = true;
                $_SESSION['pesan'] = "Ada sesuatu yang salah!";
                echo $e->getMessage();
            }
            echo "<meta http-equiv='refresh' content='0; url=loggedDetailEArsip.php?id=" . $id . "&judul=" . $judul . "'>";
        } else {
            unset($_SESSION['login']);
            unset($_SESSION['username']);
            unset($_SESSION['akses']);
            echo "<meta http-equiv='refresh' content='0; url=login.php'>";
            die();
        }
    } else {
        unset($_SESSION['login']);
        unset($_SESSION['username']);
        unset($_SESSION['akses']);
        echo "<meta http-equiv='refresh' content='0; url=login.php'>";
        die();
    }
}
