<?php
session_start();
include_once('yielder.php');
$_SESSION['state'] = 'dashboard'; //ubah ini nanti
$beranda = $login = false;

if (isset($_SESSION['login'])) {
    echo "<meta http-equiv='refresh' content='0; url=loggedDashboard.php'>";
    die();
}
if ($_SESSION['state'] == 'beranda') {
    $beranda = true;
}
if ($_SESSION['state'] == 'login') {
    $login = true;
}

$yielder = new Yielder($_SESSION['state'],'','');
$head = $yielder->getHead();
$tail = $yielder->getTail();
$menu = $yielder->getMenu();
?>

<?php echo $head ?>
<div class="w-[100vw] h-[100vh] flex flex-col relative">
    <div class="w-[100%] h-[20%] bg-[#B22222] flex justify-between px-10 relative">
        <h1 class="text-[#FFD700] text-[40px] font-[700]">CALO <br> DEPARTEMENT</h1>
        <?php echo $menu ?>
    </div>
    <div class="w-[100%] h-[20%] bg-[#D9D9D9] pl-32 pt-8 font-[700] text-[25px]">
        <span class="<?php echo $res = ($beranda) ? 'text-[#6495ED]' : '' ?>">Beranda</span>/<span class="<?php echo $res = ($login) ? 'text-[#6495ED]' : '' ?>">Login</span>
    </div>
    <img src="images/mainImage.png" alt="Gambar Utama" class="absolute bottom-0 z-[-10]" width="100%">
</div>
<?php echo $tail ?>