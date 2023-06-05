<?php
// Połączenie z bazą danych

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
// Sprawdzenie połączenia
if (!$conn) {
    die("Błąd połączenia: " . mysqli_connect_error());
}

// Sprawdzenie, czy otrzymano żądanie POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobranie identyfikatora rekordu do usunięcia
    $id = $_POST['id'];
    
    $query = "UPDATE meme SET accepted = 1 WHERE id_meme = '$id'";
    $result = mysqli_query($conn, $query);
    header("Location: admin.php");
    $result ->free_result();
}

// Zamykanie połączenia
mysqli_close($conn);
?>
