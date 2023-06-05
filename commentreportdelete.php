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
    $id_comment = $_POST['id_comment'];
    

    $query = "DELETE FROM comment WHERE id_comment = '$id_comment'";
    $query1 = "DELETE FROM comment_report WHERE id_report = '$id_report'";
    $result1 = mysqli_query($conn, $query1);
    $result = mysqli_query($conn, $query);
    header("Location: admin.php");
    $result ->free_result();
    $result1 ->free_result();
}

// Zamykanie połączenia
mysqli_close($conn);
?>
