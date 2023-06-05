<?php
    
    require_once "connect.php";
    $conn = @new mysqli($host,$user,$passw,$db_name);
    
    if($conn ->connect_errno!=0)
    {
        echo"Error: " . $conn ->connect_errno. " Opis: " . $conn ->connect_error;
    }
    else
    {
        echo"polaczono";
    }

if (!$conn) {
    die("Błąd połączenia: " . mysqli_connect_error());
}

// Sprawdzenie, czy otrzymano żądanie POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobranie identyfikatora rekordu do usunięcia
    $id_report = $_POST['id'];
    $id_meme = $_POST['id_meme'];
    
    $query = "DELETE FROM meme WHERE id_meme = '$id_meme'";
    $query1 = "DELETE FROM meme_report WHERE id_report = '$id_report'";
    $result = mysqli_query($conn, $query);
    $result1 = mysqli_query($conn, $query1);
    header("Location: admin.php");

    $result ->free_result();
    $result1 ->free_result();
}

// Zamykanie połączenia
mysqli_close($conn);
?>
