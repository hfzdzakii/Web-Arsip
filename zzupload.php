<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['upload'])) {
        echo 'Name: ' . $_FILES['pdffile']['name'] . '<br>';
        echo 'Type: ' . $_FILES['pdffile']['type'] . '<br>';
        echo 'Size: ' . $_FILES['pdffile']['size'] . ' bytes<br>';
        echo 'Temporary location: ' . $_FILES['pdffile']['tmp_name'];
        echo '<br/>';

        $arr = explode('.', $_FILES['pdffile']['name']);
        $allowed = array('pdf');
        if (in_array($arr[1], $allowed)) {
            echo $arr[1] . ', boleh masuk';
        } else {
            echo $arr[1] . ', tidak boleh masuk';
        }

        $nama = 'uploaded/' . time() . '_' . $_FILES['pdffile']['name'];

        // move_uploaded_file($_FILES['pdffile']['tmp_name'], $nama);
    } else {
        echo "mboh";
    }
}
