<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "fasilkom_akademik";

$koneksi    = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi antara localhost dan PHP berhasil atau tidak
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}

$nim        = "";
$nama       = "";
$alamat     = "";
$jurusan    = "";
$sukses     = "";
$error      = "";

if(isset($_GET['op'])){ //penangkapan op
    $op = $_GET['op']; //tangkap variabel
}else{
    $op = "";
}
if($op == 'delete'){
    $id         = $_GET['id'];
    $sql1       = "delete from mahasiswa where id = '$id'";
    $q1         = mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses = "Berhasil hapus data";
    }else{
        $error  = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "select * from mahasiswa where id = '$id';";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $nim = $r1['nim'];
    $nama = $r1['nama'];
    $alamat = $r1['alamat'];
    $jurusan = $r1['jurusan'];

    if ($nim == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $jurusan = $_POST['jurusan'];
       
    if ($nim && $nama && $alamat && $jurusan) {
        if ($op == 'edit'){
            $sql1       = "UPDATE mahasiswa set nim = '$nim',nama='$nama',alamat = '$alamat',jurusan='$jurusan' where id = '$id'";
            $q1         = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        }else{
            $sql1 = "INSERT INTO mahasiswa (nim, nama, alamat, jurusan) VALUES ('$nim', '$nama', '$alamat', '$jurusan')";
            $q1 = mysqli_query($koneksi, $sql1);
    
            if ($q1) {
                $sukses = "Berhasil menambahkan data baru.";
            } else {
                $error = "Gagal menambahkan data, coba lagi.";
            }
        }
        header("refresh:4;url=index.php");
    } else {
        $error = "Lengkapi data Anda.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa Fasilkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="mx-auto">
        <!-- masukkan data -->
        <div class="card">
            <div class="card-header">
                Buat / Edit Data Mahasiswa
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <?php if ($sukses): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses; ?>
                </div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <select class="form-control" id="jurusan" name="jurusan">
                            <option value="">--- Pilih Jurusan ---</option>
                            <option value="si-reg" <?php if ($jurusan == "si-reg") echo "selected"; ?>>Sistem Informasi-Reguler</option>
                            <option value="si-bil" <?php if ($jurusan == "si-bil") echo "selected"; ?>>Sistem Informasi-Bilingual</option>
                            <option value="sk-reg" <?php if ($jurusan == "sk-reg") echo "selected"; ?>>Sistem Komputer-Reguler</option>
                            <option value="sk-bil" <?php if ($jurusan == "sk-bil") echo "selected"; ?>>Sistem Komputer-Bilingual</option>
                            <option value="ti-reg" <?php if ($jurusan == "ti-reg") echo "selected"; ?>>Teknik Informatika-Reguler</option>
                            <option value="ti-bil" <?php if ($jurusan == "ti-bil") echo "selected"; ?>>Teknik Informatika-Bilingual</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan data" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
        <!-- keluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-primary">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Jurusan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                        <tbody>
                            <?php 
                                $sql2 = "SELECT * FROM mahasiswa order by id desc";
                                $q2 = mysqli_query($koneksi, $sql2);
                                $urut = 1;
                                while($r2 = mysqli_fetch_array($q2)){
                                    $id = $r2['id'];
                                    $nim = $r2['nim'];
                                    $nama = $r2['nama'];
                                    $alamat = $r2['alamat'];
                                    $jurusan = $r2['jurusan'];

                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $urut++ ?></th>
                                        <td scope="row"><?php echo $nim ?></td>
                                        <td scope="row"><?php echo $nama ?></td>
                                        <td scope="row"><?php echo $alamat ?></td>
                                        <td scope="row"><?php echo $jurusan ?></td>
                                        <td scope="row">
                                            <a href="index.php?op=edit&id=<?php echo $id?>">
                                                <button type="button" name="edit" class="btn btn-warning">Edit</button>
                                            </a>
                                            <a href="index.php?op=delete&id=<?php echo $id?>" onclick="return confirm('Anda yakin akan menghapus data?')">
                                                <button type="button" class="btn btn-danger">Hapus</button>
                                            </a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            ?>
                        </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    
</body>
</html>