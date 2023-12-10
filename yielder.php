<?php
class Yielder
{
    private $head;
    private $tail;
    private $menu;
    private $logHeader;
    private $logSideBar;

    public function __construct($state = '', $nama = '', $akses = '')
    {
        $this->headContent();
        $this->tailContent();
        $this->menuContent($state);
        $this->loggedHeader($nama, $akses);
        $this->loggedSideBar($akses);
    }

    private function headContent()
    {
        ob_start();
        echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <script src="https://cdn.tailwindcss.com"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
                <title>Document</title>
            </head>
            <body>';
        $this->head = ob_get_clean();
    }

    private function tailContent()
    {
        ob_start();
        echo '</body>
            </html>';
        $this->tail = ob_get_clean();
    }

    private function menuContent($state)
    {
        $hasil = ($state  == 'beranda') ? 'bg-[#DEB887]' : '';
        $hasil3 = ($state  == 'login') ? 'bg-[#DEB887]' : '';
        ob_start();
        echo '<div class="flex bg-[#D9D9D9] absolute bottom-0 right-10">
                <a href="beranda.php">
                    <div class="px-14 py-3 text-[25px] ' . $hasil . ' ">Beranda</div>
                </a>
                <a href="login.php">
                    <div class="px-14 py-3 text-[25px] ' . $hasil3 . ' ">Login</div>
                </a>
            </div>';
        $this->menu = ob_get_clean();
    }

    private function loggedHeader($nama, $akses)
    {
        ob_start();
        echo '<div class="w-[100%] h-[20%] bg-[#B22222] flex justify-between items-center px-10 relative">
            <h1 class="text-[#FFD700] text-[40px] font-[700]">CALO <br> DEPARTEMENT</h1>
            <div class="text-white text-[25px]">Log As : ' . $nama . ' (' . $akses . ')</div>
            </div>';
        $this->logHeader = ob_get_clean();
    }

    private function loggedSideBar($akses)
    {
        $siapa = ($akses == 'superadmin') ? '<a class="mb-[20px]" href="loggedManajemen.php">Manajemen User</a>' : '';
        ob_start();
        echo '<div class="w-[15%] h-[100%] bg-[#D9D9D9] flex flex-col justify-between p-[20px] text-[25px]">
            <div class="w-[100%] font-[700]">E-Arsip</div>
            <div class="w-[100%] flex flex-col">
                <a class="mb-[20px]" href="loggedDashboard.php">Dashboard</a>
                <a class="mb-[20px]" href="loggedEArsip.php">Data E-Arsip</a>
                '. $siapa .'
            </div>
            <div class="w-[100%] cursor-pointer" id="logot">Logout</div>
            </div>';
        $this->logSideBar = ob_get_clean();
    }

    public function getHead()
    {
        return $this->head;
    }

    public function getTail()
    {
        return $this->tail;
    }

    public function getMenu()
    {
        return $this->menu;
    }

    public function getLoggedHeader()
    {
        return $this->logHeader;
    }

    public function getLoggedSideBar()
    {
        return $this->logSideBar;   
    }
}
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body> -->

<!-- </body>
</html> -->