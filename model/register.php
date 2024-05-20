<?php
include 'include/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Register</title>

    </head>
    <body>
        <div class="container">
            <?php
            if (isset($_POST['register'])) {
                //menghindari cross site scripting / XSS
                $username =  htmlspecialchars($_POST['username']);
                $password =  htmlspecialchars(md5($_POST['password']));
                $level =  $_POST['level'];
                $tgl_dibuat = date('Y-m-d');
                
                $cekDuplikasi = mysqli_fetch_array(mysqli_query($koneksi, "SELECT username FROM t_user WHERE username = '$username'"));
                if ($cekDuplikasi > 0) {
                    echo "<script>alert('Username tidak tersedia, silahkan coba username lain.'); location.href='register.php'</script>";
                } else {
                    if ($level == 'admin' || $level == 'petugas') {
                        $insert = mysqli_query($koneksi, "INSERT INTO t_user(username, password, level, tgl_akun_dibuat) VALUES ('$username', '$password', '$level', '$tgl_dibuat')");
                        echo "<script>alert('Selamat registrasi akun ".$level." berhasil.'); location.href='index.php';</script>";
                    } else {
                        $insert = mysqli_query($koneksi, "INSERT INTO t_user(username, password, level, tgl_akun_dibuat) VALUES ('$username', '$password', '$level', '$tgl_dibuat')");
                        echo "<script>alert('Selamat registrasi berhasil, silahkan login.'); location.href='login.php';</script>";
                    }
                }
            }
            ?>

            <form method="post">
                <!-- FIELD USERNAME -->
                <input class="form-control" type="text" name="username" placeholder="Masukkan username" required oninvalid="this.setCustomValidity('Username tidak boleh kosong')" oninput="setCustomValidity('')" autofocus/>
                <label>Username</label>
                <!-- /FIELD USERNAME -->
                
                <!-- FIELD PASSWORD -->
                <input class="form-control" type="password" name="password" id="password" placeholder="Masukkan kata sandi" required oninvalid="this.setCustomValidity('Password tidak boleh kosong')" oninput="setCustomValidity('')"/>
                <label>Password</label>
                <!-- /FIELD PASSWORD -->

                <!-- FIELD HAK AKSES -->
                <select name="level" class="form-control" <?php echo (empty($_SESSION['user'])) ? 'hidden' : ''; ?>>
                    <?php if ($_SESSION['user']['level'] == 'admin') { ?>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                    <?php } else { ?>
                        <option value="peminjam" <?php echo (empty($_SESSION['user'])) ? 'selected' : ''; ?>>Peminjam</option>
                    <?php } ?>
                </select>
                <!-- /FIELD HAK AKSES -->


                <label <?php echo (empty($_SESSION['user'])) ? 'hidden' : ''; ?>>
                    Jabatan 
                    (Hak akses spesial admin)
                </label>

                <!-- TOMBOL -->
                <button class="btn btn-dark" type="submit" name="register" value="register">Register</button>
                <?php if (!empty($_SESSION['user']) && $_SESSION['user']['level'] == 'admin') { ?>
                <a href="index.php" class="btn btn-danger"><i class="fa-solid fa-right-from-bracket"></i> Kembali</a>
                <?php } ?>

            </form>
        </div>
    </body>
</html>