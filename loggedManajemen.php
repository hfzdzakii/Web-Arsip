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

$query = $pdo->prepare("SELECT * FROM `user` WHERE `akses` != 'superadmin';");
try {
    $query->execute();
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error " . $e->getMessage();
}

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
            <p class="font-[700] text-[30px]">Manajemen Data User</p>
            <button id="tambahUser" class="mt-[20px] bg-cyan-400 hover:bg-cyan-500 px-[20px] py-[10px] mb-[20px] rounded-full">Tambah User</button>
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
            <table class="mt-[20px] table-auto w-[50%]">
                <thead>
                    <tr class="border-b-2 border-black">
                        <th>Username</th>
                        <th>Akses</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row) : ?>
                        <tr>
                            <td><?php echo ucfirst($row['username']) ?></td>
                            <td><?php echo ucfirst($row['akses']) ?></td>
                            <td><a href="yakinkah.php?user=<?php echo $row['username']; ?>&aksi=deleteUser">Delete</a></td>
                            <!-- controller.php?user=<?php //echo $row['username']; ?>&aksi=deleteUser -->
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#tambahUser').on('click', function() {
            window.location.href = 'insertUser.php'
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