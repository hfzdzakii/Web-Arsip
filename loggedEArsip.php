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
        <div class="w-[85%] overflow-hidden py-[40px] px-[80px] text-[20px] relative">
            <div class="absolute right-[80px] top-[40px]">
                <p class="font-[700]">Home / <span class="text-[#6495ED]">E-Arsip</span></p>
            </div>
            <p class="font-[700] text-[30px]">Data E-Arsip</p>
            <div class="flex flex-wrap w-[100%] justify-center">
                <div id="folder1" class="flex flex-col items-center justify-center cursor-pointer">
                    <img class="m-[50px] mb-[0px]" width="100" height="100" src="https://img.icons8.com/ios/100/folder-invoices--v1.png" alt="folder-invoices--v1" />
                    <p>KU. 01</p>
                </div>
                <div id="folder2" class="flex flex-col items-center justify-center cursor-pointer">
                    <img class="m-[50px] mb-[0px]" width="100" height="100" src="https://img.icons8.com/ios/100/folder-invoices--v1.png" alt="folder-invoices--v1" />
                    <p>KU. 02</p>
                </div>
                <div id="folder3" class="flex flex-col items-center justify-center cursor-pointer">
                    <img class="m-[50px] mb-[0px]" width="100" height="100" src="https://img.icons8.com/ios/100/folder-invoices--v1.png" alt="folder-invoices--v1" />
                    <p>KU. 03</p>
                </div>
                <div id="folder4" class="flex flex-col items-center justify-center cursor-pointer">
                    <img class="m-[50px] mb-[0px]" width="100" height="100" src="https://img.icons8.com/ios/100/folder-invoices--v1.png" alt="folder-invoices--v1" />
                    <p>KU. 04</p>
                </div>
                <div id="folder5" class="flex flex-col items-center justify-center cursor-pointer">
                    <img class="m-[50px] mb-[0px]" width="100" height="100" src="https://img.icons8.com/ios/100/folder-invoices--v1.png" alt="folder-invoices--v1" />
                    <p>KU. 05</p>
                </div>
                <div id="folder6" class="flex flex-col items-center justify-center cursor-pointer">
                    <img class="m-[50px] mb-[0px]" width="100" height="100" src="https://img.icons8.com/ios/100/folder-invoices--v1.png" alt="folder-invoices--v1" />
                    <p>KU. 06</p>
                </div>
                <div id="folder7" class="flex flex-col items-center justify-center cursor-pointer">
                    <img class="m-[50px] mb-[0px]" width="100" height="100" src="https://img.icons8.com/ios/100/folder-invoices--v1.png" alt="folder-invoices--v1" />
                    <p>KU. 07</p>
                </div>
            </div>
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
        $('#folder1').on('click', function() {
            window.location.href = 'loggedDetailEArsip.php?id=01&judul=Gaji_Pegawai'
        })
        $('#folder2').on('click', function() {
            window.location.href = 'loggedDetailEArsip.php?id=02&judul=Kerumahtanggaan'
        })
        $('#folder3').on('click', function() {
            window.location.href = 'loggedDetailEArsip.php?id=03&judul=Pajak'
        })
        $('#folder4').on('click', function() {
            window.location.href = 'loggedDetailEArsip.php?id=04&judul=Hutang'
        })
        $('#folder5').on('click', function() {
            window.location.href = 'loggedDetailEArsip.php?id=05&judul=Anggaran'
        })
        $('#folder6').on('click', function() {
            window.location.href = 'loggedDetailEArsip.php?id=06&judul=Piutang'
        })
        $('#folder7').on('click', function() {
            window.location.href = 'loggedDetailEArsip.php?id=07&judul=Pinjaman_Koperasi'
        })
    });
</script>
<?php echo $tail ?>