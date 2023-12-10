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

if (!isset($_GET['id']) && !isset($_GET['judul'])) {
    echo "<meta http-equiv='refresh' content='0; url=loggedEArsip.php'>";
    die();
}

$id = $_GET['id'];
$judul = $_GET['judul'];
$modJudul = str_replace("_", " ", $judul);

$yielder = new Yielder('', $nama, $akses);
$head = $yielder->getHead();
$tail = $yielder->getTail();
$logHeader = $yielder->getLoggedHeader();
$logSideBar = $yielder->getLoggedSideBar();

$query = $pdo->prepare("SELECT * FROM `file` WHERE kode LIKE '%.".$id.".%';");
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
        <div class="w-[85%] overflow-hidden py-[40px] px-[80px] text-[20px] relative">
            <div class="absolute right-[80px] top-[40px]">
                <?php echo $hasil = $akses == 'user' ? '' :  '<button id="tambahFile" class="mt-[20px] bg-cyan-400 hover:bg-cyan-500 px-[20px] py-[10px] mb-[20px] rounded-full">Tambah File</button>
                                                                <button id="kembali" class="mt-[20px] bg-red-400 hover:bg-red-500 px-[20px] py-[10px] mb-[20px] rounded-full">Kembali</button>' ?>
            </div>
            <p class="font-[700] text-[30px]"><?php echo "KU. " . $id . " " . $modJudul; ?></p>
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
            <table class="mt-[20px] table-auto min-w-[65%]">
                <thead>
                    <tr class="border-b-2 border-black ">
                        <th>Kode</th>
                        <th>Nama File</th>
                        <th>File</th>
                        <?php echo $hasil = $akses == 'user' ? '' :  '<th>Aksi</th>'; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo $row['kode'] ?></td>
                            <td><?php echo $row['nama'] ?></td>
                            <td><a href=<?php echo "uploaded/".$row['path']."" ?>>Download</a></td>
                            <?php echo $hasil = $akses == 'user' ? '' :  '<td><a href="insertFile.php?aksi=editFile&kode='.$row['kode'].'&id='.$id.'&judul='.$judul.'">Edit</a>/<a href="yakinkah.php?aksi=deleteFile&kode='.$row['kode'].'&id='.$id.'&judul='.$judul.'">Delete</a></td>'; ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
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
        $('#tambahFile').on('click', function() {
            window.location.href = 'insertFile.php?id=<?php echo $id; ?>&judul=<?php echo $judul; ?>'
        })
        $('#kembali').on('click', function() {
            window.location.href = 'loggedEArsip.php'
        })
    });
</script>
<?php echo $tail ?>