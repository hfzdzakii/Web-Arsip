<?php
session_start();
include_once('yielder.php');
include_once('connectDb.php');

if (isset($_SESSION['login'])) {
    $_SESSION['login'] = true;
} else {
    echo "<meta http-equiv='refresh' content='0; url=login.php'>";
    die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

if ($akses == 'user' || !isset($_GET['id'])) {
    echo "<meta http-equiv='refresh' content='0; url=loggedDetailEArsip.php'>";
    die();
}

$id = $_GET['id'];
$judul = $_GET['judul'];

$yielder = new Yielder('', $nama, $akses);
$head = $yielder->getHead();
$tail = $yielder->getTail();
$logHeader = $yielder->getLoggedHeader();
$logSideBar = $yielder->getLoggedSideBar();

$query = $pdo->prepare("SELECT COUNT('kode') AS 'total' FROM `file` WHERE kode LIKE '%.".$id.".%';");
try {
    $query->execute();
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error " . $e->getMessage();
}
foreach ($rows as $row) {
    $terbaru = intval($row['total']) + 1;
    if (strlen($row['total']) == "1") {
        $kode = "KU.". $id . ".0". $terbaru;
    } else {
        $kode = "KU.". $id . ".". $terbaru;
    }
}

?>


<?php echo $head ?>
<div class="w-[100vw] h-[100vh] flex flex-col relative overflow-hidden">
    <?php echo $logHeader; ?>
    <div class="w-[100%] h-[80%] flex">
        <?php echo $logSideBar; ?>
        <div class="w-[85%] overflow-y-auto py-[40px] px-[80px] text-[20px] relative flex flex-col justify-center items-center">
            <div class="absolute right-[80px] top-[40px] w-fit">
                <p class="font-[700]">Home / <span class="text-[#6495ED]"><?php echo $hasil=isset($_GET['aksi'])? 'Edit' : 'Tambah'; ?> File</span></p>
            </div>
            <?php if(!isset($_GET['aksi'])) : ?>
                <p class="font-[700] text-[30px] self-start mb-[10px]">UPLOAD FILE</p>
                <p class="w-[100%] bg-[#FFD700] p-[10px]">Halaman Upload Data E-Arsip</p>            
                <form action="controller.php?id=<?php echo $id; ?>&judul=<?php echo $judul; ?> " method="post" class="bg-[#D9D9D9] p-[30px] w-[100%]" enctype="multipart/form-data">
                    <?php if (isset($_SESSION['error'])) : ?>
                        <p style="color: red; font-style: italic;"><?php echo $_SESSION['pesan'];
                                                                    unset($_SESSION['pesan']);
                                                                    unset($_SESSION['error']); ?></p>
                    <?php endif ?>
                    <?php if (isset($_SESSION['didit'])) : ?>
                        <p style="color: blue; font-style: italic;"><?php echo $_SESSION['pesan'];
                                                                    unset($_SESSION['pesan']);
                                                                    unset($_SESSION['didit']); ?></p>
                    <?php endif ?>
                    <table class="mt-[10px] relative">
                        <tr>
                            <td><label for="kode">Kode</label></td>
                            <td><span class="mx-[10px]">:</span></td>
                            <td><input type="text" name="kode" class="mb-[10px] ms-[20px]" value="<?php echo $kode ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="fileName">Nama File</label></td>
                            <td><span class="mx-[10px]">:</span></td>
                            <td><input type="text" name="fileName" class="mb-[10px] ms-[20px]" required></td>
                        </tr>
                        <tr>
                            <td><label for="filePDF">File Terkait</label></td>
                            <td><span class="mx-[10px]">:</span></td>
                            <td>
                                <input type="file" name="filePDF" id="uploadFile" class="absolute z-[-100]" required>
                                <span class="bg-[#007bff] cursor-pointer px-[8px] py-[5px] mb-[100px] ms-[20px]" id="buttonPilihFile">Pilih File</span>
                                <span id="fileName" class="ms-[10px]">Belum ada File yang Dipilih</span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <br>
                                <button type="submit" name="upload" class="bg-[#06CD1A] px-[20px] py-[5px] text-[#FFFFFF] rounded-full">Upload</button>
                                <span id="kembali" class="bg-[#FF0000] px-[20px] py-[5px] text-[#FFFFFF] rounded-full cursor-pointer">Kembali</span>
                            </td>
                        </tr>
                    </table>
                    <br>
                </form>
            <?php endif ?>
            <!-- =========================================================================================================================================================== -->
            <?php if(isset($_GET['aksi'])) : 
                $aksi = $_GET['aksi'];
                $kode = $_GET['kode'];

                $query = $pdo->prepare("SELECT * FROM `file` WHERE kode=:kode;");
                $query->bindParam(':kode', $kode);
                try {
                    $query->execute();
                    $hasil = $query->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
                $underscorePos = strpos($hasil[0]['path'], '_');
                $string = substr($hasil[0]['path'], $underscorePos + 1);
                $string = str_replace('_', ' ', $string);
            ?>
                <p class="font-[700] text-[30px] self-start mb-[10px]">EDIT FILE</p>
                <p class="w-[100%] bg-[#FFD700] p-[10px]">Halaman Edit Data E-Arsip</p>
                <form action="controller.php?id=<?php echo $id; ?>&judul=<?php echo $judul; ?>" method="post" class="bg-[#D9D9D9] p-[30px] w-[100%]" enctype="multipart/form-data">
                    <?php if (isset($_SESSION['error'])) : ?>
                        <p style="color: red; font-style: italic;"><?php echo $_SESSION['pesan'];
                                                                    unset($_SESSION['pesan']);
                                                                    unset($_SESSION['error']); ?></p>
                    <?php endif ?>
                    <?php if (isset($_SESSION['didit'])) : ?>
                        <p style="color: blue; font-style: italic;"><?php echo $_SESSION['pesan'];
                                                                    unset($_SESSION['pesan']);
                                                                    unset($_SESSION['didit']); ?></p>
                    <?php endif ?>
                    <table class="mt-[10px] relative">
                        <tr>
                            <td><label for="kode">Kode</label></td>
                            <td><span class="mx-[10px]">:</span></td>
                            <td><input type="text" name="kode" class="mb-[10px] ms-[20px] bg-gray-200" value="<?php echo $hasil[0]['kode'] ?>" readonly required></td>
                        </tr>
                        <tr>
                            <td><label for="fileName">Nama File</label></td>
                            <td><span class="mx-[10px]">:</span></td>
                            <td><input type="text" name="fileName" class="mb-[10px] ms-[20px]" value="<?php echo $hasil[0]['nama'] ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="filePDF">File Terkait</label></td>
                            <td><span class="mx-[10px]">:</span></td>
                            <td>
                                <input type="file" name="filePDF" id="uploadFile" class="absolute z-[-100]">
                                <span class="bg-[#007bff] cursor-pointer px-[8px] py-[5px] mb-[100px] ms-[20px]" id="buttonPilihFile">Pilih File</span>
                                <span id="fileName" class="ms-[10px]"><?php echo $string ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <br>
                                <button type="submit" name="editFile" class="bg-[#06CD1A] px-[20px] py-[5px] text-[#FFFFFF] rounded-full">Edit</button>
                                <span id="kembali" class="bg-[#FF0000] px-[20px] py-[5px] text-[#FFFFFF] rounded-full cursor-pointer">Kembali</span>
                            </td>
                        </tr>
                    </table>
                    <br>
                </form>
            <?php endif ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#kembali').on('click', function() {
            window.location.href = 'loggedDetailEArsip.php?id=<?php echo $id ?>&judul=<?php echo $judul ?>'
        })
        $('#logot').on('click', function() {
            $.ajax({
                url: 'controller.php',
                method: 'POST',
            }).done(function() {
                window.location.href = 'login.php'
            });
        });
        $('#buttonPilihFile').on('click', function() {
            $('#uploadFile').click()
        })
        $('#uploadFile').on('change', function(e) {
            var nama = e.target.files[0].name
            var type = nama.split('.')
            var allowed = "pdf"
            if (type.includes(allowed)) {
                $('#fileName').text(nama)
            } else {
                $('#fileName').text("Hanya File .pdf yang diterima")
                $('#uploadFile').val('')
            }
        })
    });
</script>
<?php echo $tail ?>