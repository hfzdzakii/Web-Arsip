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

if ($akses != 'superadmin') {
    echo "<meta http-equiv='refresh' content='0; url=loggedDashboard.php'>";
    die();
}

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
        <div class="w-[85%] overflow-y-auto py-[40px] px-[80px] text-[20px] relative flex flex-col justify-center items-center">
            <div class="absolute right-[80px] top-[40px] w-fit">
                <p class="font-[700]">Home / <span class="text-[#6495ED]">Tambah User</span></p>
            </div>
            <p class="font-[700] text-[30px] self-start mb-[10px]">Tambah User</p>
            <p class="w-[100%] bg-[#FFD700] p-[10px]">Halaman Tambah Data User</p>
            <form action="controller.php" method="post" class="bg-[#D9D9D9] p-[30px] w-[100%]">
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
                <table class="mt-[10px]">
                    <tr>
                        <td><label for="username">Username</label></td>
                        <td><span class="mx-[10px]">:</span></td>
                        <td><input type="text" name="username" class="mb-[10px] ms-[20px]" required></td>
                    </tr>
                    <tr>
                        <td><label for="password1">Password</label></td>
                        <td><span class="mx-[10px]">:</span></td>
                        <td><input type="password" name="password1" class="mb-[10px] ms-[20px]" required></td>
                    </tr>
                    <tr>
                        <td><label for="password2">Konfirmasi Passord</label></td>
                        <td><span class="mx-[10px]">:</span></td>
                        <td><input type="password" name="password2" class="mb-[10px] ms-[20px]" required></td>
                    </tr>
                    <tr>
                        <td><label for="akses">Hak Akses</label></td>
                        <td><span class="mx-[10px]">:</span></td>
                        <td>
                            <select required name="akses" class="mb-[10px] ms-[20px]">
                                <option value="" disabled selected>Pilih Akses</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            <button type="submit" name="daftar" class="bg-[#06CD1A] px-[20px] py-[5px] text-[#FFFFFF] rounded-full">Daftar</button>
                            <span id="kembali" class="bg-[#FF0000] px-[20px] py-[5px] text-[#FFFFFF] rounded-full cursor-pointer">Kembali</span>
                        </td>
                    </tr>
                </table>
                <br>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#kembali').on('click', function() {
            window.location.href = 'loggedManajemen.php'
        })
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