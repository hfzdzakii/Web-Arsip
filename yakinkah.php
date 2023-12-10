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

if ($akses != 'superadmin' || !isset($_GET['aksi'])) {
    // echo !isset($_GET['aksi']);
    echo "<meta http-equiv='refresh' content='0; url=loggedDashboard.php'>";
    die();
}

$aksi = $_GET['aksi'];

$yielder = new Yielder('', $nama, $akses);
$head = $yielder->getHead();
$tail = $yielder->getTail();
$logHeader = $yielder->getLoggedHeader();
$logSideBar = $yielder->getLoggedSideBar();
?>


<?php echo $head ?>
<div class="w-[100vw] h-[100vh] flex flex-col relative overflow-hidden">
    <?php echo $logHeader; ?>
    <div class="w-[100%] h-[80%] flex">
        <?php echo $logSideBar; ?>
        <div class="w-[85%] overflow-y-auto py-[40px] px-[80px] text-[20px] relative">
            <div class="absolute right-[80px] top-[40px] w-fit">
                <p class="font-[700]">Home / <span class="text-[#6495ED]">Manajemen User</span></p>
            </div>
            <?php if ($aksi == 'deleteUser') {
                $user = $_GET['user'];
            ?>
                <p class="w-[40%] bg-[#FFD700] p-[10px] mt-[50px]">- Delete User</p>
                <div class="bg-[#D9D9D9] p-[30px] w-[40%]">
                    <div>Anda yakin akan <strong>menghapus</strong> data <br>
                        user atas nama <?php echo ucfirst($user) ?>?</div>
                    <div class="flex justify-around mt-[20px]">
                        <a class="bg-[#06CD1A] px-[20px] py-[5px] text-[#FFFFFF] rounded-full" href="controller.php?user=<?php echo $user; ?>&aksi=deleteUser">Delete</a>
                        <a class="bg-[#FF0000] px-[20px] py-[5px] text-[#FFFFFF] rounded-full" href="loggedManajemen.php">Kembali</a>
                    </div>
                </div>
            <?php } else if($aksi == 'deleteFile'){
                $kode = $_GET['kode'];
                $id= $_GET['id'];
                $judul = $_GET['judul'];

                $query = $pdo->prepare("SELECT nama FROM `file` WHERE kode ='".$kode."';");
                try {
                    $query->execute();
                    $Hasil = $query->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            ?>
                <p class="w-[40%] bg-[#FFD700] p-[10px] mt-[50px]">- Delete File</p>
                <div class="bg-[#D9D9D9] p-[30px] w-[40%]">
                    <div>Anda yakin akan <strong>menghapus</strong> File <br>
                        dengan nama <?php echo ucfirst($Hasil[0]['nama']) ?>?</div>
                    <div class="flex justify-around mt-[20px]">
                        <a class="bg-[#06CD1A] px-[20px] py-[5px] text-[#FFFFFF] rounded-full" href="controller.php?kode=<?php echo $kode; ?>&aksi=deleteFile&id=<?php echo $id ?>&judul=<?php echo $judul ?>">Delete</a>
                        <a class="bg-[#FF0000] px-[20px] py-[5px] text-[#FFFFFF] rounded-full" href="loggedDetailEArsip.php?id=<?php echo $id ?>&judul=<?php echo $judul ?>">Kembali</a>
                    </div>
                </div>
            <?php } else {
                echo "<meta http-equiv='refresh' content='0; url=loggedDashboard.php'>";
            } ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#logot').on('click', function() {
            $.ajax({
                url: 'controller.php',
                method: 'POST',
            }).done(function() {
                window.location.href = 'login.php'
            });
        });
    });
</script>
<?php echo $tail ?>