<?php
session_start();
include_once('yielder.php');

if (isset($_SESSION['login'])) {
    $_SESSION['login'] = true;
} else {
    echo "<meta http-equiv='refresh' content='0; url=login.php'>";
    die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

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
            <div class="absolute right-[80px] top-[40px]">
                <p class="font-[700]"><span class="text-[#6495ED]">Home</span> / E-Arsip</p>
            </div>
            <p class="font-[700] text-[30px]">Dashboard</p>
            <p class="my-[25px]">Kode Klasifikasi Arsip</p>
            <p class="mb-[10px]">KU.01 Gaji Karyawan</p>
            <p class="mb-[10px]">KU.02 Kerumahtanggaan</p>
            <p class="mb-[10px]">KU.03 Pajak</p>
            <p class="mb-[10px]">KU.04 Hutang</p>
            <p class="mb-[10px]">KU.05 Anggaran</p>
            <p class="mb-[10px]">KU.06 Piutang</p>
            <p class="mb-[10px]">KU.07 Pinjaman Koperasi Karyawan</p>
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