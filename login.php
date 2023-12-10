<?php
session_start();
include_once('yielder.php');
$_SESSION['state'] = 'login'; //ubah ini nanti
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

$yielder = new Yielder($_SESSION['state']);
$head = $yielder->getHead();
$tail = $yielder->getTail();
$menu = $yielder->getMenu();
?>

<?php echo $head ?>
<div class="w-[100vw] h-[100vh] flex flex-col relative">
    <div class="w-[100%] h-[20%] bg-[#B22222] flex justify-between px-10 relative">
        <a href="index.php">
            <h1 class="text-[#FFD700] text-[40px] font-[700]">CALO <br> DEPARTEMENT</h1>
        </a>
        <?php echo $menu ?>
    </div>
    <div class="w-[100%] h-[20%] bg-[#D9D9D9] pl-32 pt-8 font-[700] text-[25px]">
        <span class="<?php echo $res = ($beranda) ? 'text-[#6495ED]' : '' ?>">Beranda</span>/<span class="<?php echo $res = ($login) ? 'text-[#6495ED]' : '' ?>">Login</span>
    </div>
    <div class="flex flex-col justify-center w-[100%] h-[60%] pl-16">
        <form action="controller.php" method="post" class="w-[450px] h-[300px] bg-[#D9D9D9] flex flex-col justify-center items-center px-14">
            <div class="w-[100%] flex justify-between">
                <label for="username" class="font-[700] text-[25px]">Username</label>
                <input type="text" name="usrname" required>
            </div><br>
            <div class="w-[100%] flex justify-between">
                <label for="password" class="font-[700] text-[25px]">Password</label>
                <input type="password" name="pass" required>
            </div><br>
            <?php if (isset($_SESSION['error'])) : ?>
                <div><?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?></div>
            <?php endif ?>
            <button type="submit" name="loginSubmit" class="text-[25px] bg-[#6495ED] px-10 py-1">Login</button>
        </form>
    </div>
    <img src="images/mainImage.png" alt="Gambar Utama" class="absolute bottom-0 z-[-10]" width="100%">
</div>
<?php echo $tail ?>