<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aikaiwa";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk mengambil data dari tabel apikey
function getAPIKey() {
    global $conn;

    // Query SQL untuk mengambil data
    $sql = "SELECT * FROM apikey LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Mengambil baris data pertama
        $row = $result->fetch_assoc();
        return $row;
    } else {
        return null;
    }
}
?>