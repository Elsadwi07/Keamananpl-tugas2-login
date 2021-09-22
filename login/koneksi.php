<?php
//setting default timezone
date_default_timezone_set('Asia/Jakarta');

//start session
session_start();

//membuat koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "login");

if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
}

// FUNCTION LOGIN
function login($data)
{
    global $conn;

    $email = $_POST["email"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM tb_login WHERE email = '$email' ") or die(mysqli_error($conn));

    // CEK USERNAME APAKAH ADA PADA TABEL TB_REGIS_MHS
    if (mysqli_num_rows($result) === 1) {

        // CEK APAKAH PASSWORD BENAR 
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row["password"])) {

            // SET SESSION LOGIN
            $_SESSION["login"] = true;

            // SET SESSION USER
            $_SESSION["id_user"] = $row["id_user"];
        } else {
            return false;
        }
    }
    return mysqli_affected_rows($conn);
}

// FUNCTION REGISTER
function registrasi($data)
{
    global $conn;

    $username = strtolower(stripcslashes($data["username"]));
    $email = strtolower(stripcslashes($data["email"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);


    // CEK EMAIL SUDAH ADA ATAU BELUM
    $result = mysqli_query($conn, "SELECT email FROM tb_login WHERE email = '$email' ");

    // CHECK EMAIL
    if (mysqli_fetch_assoc($result)) {
        echo "<script>
		alert('Email sudah terdaftar !');
		</script>";

        return false;
    }

    // ENSKRIPSI PASSWORD
    $passwordValid =  password_hash($password, PASSWORD_DEFAULT);

    // TAMBAHKAN USER BARU KEDATABASE
    $query = "INSERT INTO tb_login (email, username, password ) 
	VALUES('$email', '$username', '$passwordValid')";

    mysqli_query($conn, $query) or die(mysqli_error($conn));

    return mysqli_affected_rows($conn);
}

// MEMBUAT FUNCTION SHOW DATA (READ)
function query($query)
{
    global $conn;

    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $boxs = [];

    // AMBIL DATA (FETCH) DARI VARIABEL RESULT DAN MASUKKAN KE ARRAY
    while ($box = mysqli_fetch_assoc($result)) {
        $boxs[] = $box;
    }
    return $boxs;
}