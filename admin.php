<!--
    Łączenie z bazą
-->
<?php
    require_once "connect.php";

    $conn = @new mysqli($host,$user,$passw,$db_name);
    
    if($conn ->connect_errno!=0)
    {
        echo"Error: " . $conn ->connect_errno. " Opis: " . $conn ->connect_error;
    }
    else
    {
    }
    ?>

<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
<link href="template.css" rel="stylesheet" />
<link href="admin.css" rel="stylesheet" />

</head>

<body>
<header class="header">
    <div class="container-header">
      <a href="#" class="logo">Mem<span class="logo-green">Hub</span> </a>
      <nav class="nav">
        <ul class="nav-list">
          <li class="nav--list-item">
            <a href="#" class="nav--list-link">Strona główna</a>
          </li>
          <li class="nav--list-item">
            <a href="#" class="nav--list-link">Generator</a>
          </li>
          <li class="nav--list-item">
            <a href="#" class="nav--list-link">Ranking</a>
          </li>
          <li class="nav--list-item">
            <a href="#" class="nav--list-link">Poczekalnia</a>
          </li>
          <li class="btn-nav btn--nav-blue">
            <a href="#" class="btn--nav-link nav--list-link">Dodaj mema</a>
          </li>
          <li class="btn-nav btn--nav-green">
            <a href="#" class="btn--nav-link nav--list-link">Logowanie</a>
          </li>
        </ul>
      </nav>
    </div>
  </header>


  <div class="meme-scraping">
    <form method="post" action="#">
      <input type="submit" value="Scrapowanie">
    </form>

  </div>
  <main>

  <div class="meme-acceptance">
  <h1>Zatwierdzanie Memów</h1>
<!--
    Wyświetlanie i zatwierdzanie memów
-->
    <?php
    $meme_to_accpet = "SELECT * FROM `meme` WHERE `accepted`= 0;";
    $result = @$conn -> query($meme_to_accpet);
    $to_accpet = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $result ->free_result();

    echo "<table class='table'>";
    echo "<tr><th>ID</th><th>Zdjecie</th><th colspan='2'>Akcja</th></tr>";
    foreach($to_accpet as $row) {
        echo "<tr>";
        echo "<td>" . $row['id_meme'] . "</td>";
        echo "<td> <img class='myImages' id='myImg' src='".$row['imgsource']."'alt ='zdjecie mema'/> </td>";
        echo "<td><form method='post' action='acceptmeme.php'>
              <input type='hidden' name='id' value='" . $row['id_meme'] . "'>
              <input type='submit' value='Akceptuj'></form></td>".
              "<td><form method='post' action='deletememe.php'>
              <input type='hidden' name='id' value='" . $row['id_meme'] . "'>
              <input type='submit' value='Usuń'></form></td>";
        echo "</tr>";
    }
    echo "</table>";
   
?>
</div>

<div class ="meme-report">
  <!--
    Wyświetlanie i obsługa zgłoszeń memów
  -->
<h1>Zgłoszone Memy</h1>
<?php

    $reported_meme ="SELECT meme_report.id_report, meme_report.body, account.nick, meme.imgsource, meme.id_meme
    FROM meme_report
    INNER JOIN account ON meme_report.id_user = account.id_user
    INNER JOIN meme ON meme_report.id_meme = meme.id_meme;";

    $result = @$conn -> query($reported_meme);
    $reported = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $result ->free_result();

    echo "<table class='table'>";
    echo "<tr><th>ID</th><th>Powod</th><th>Użytkownik</th><th>Zdjecie</th><th colspan='2'>Akcja</th></tr>";
    foreach($reported as $row) {
        echo "<tr>";
        echo "<td>" . $row['id_report'] . "</td>";
        echo "<td id='reason'>" . $row['body'] . "</td>";
        echo "<td>" . $row['nick'] . "</td>";
        echo "<td> <img class='myImages' id='myImg' src='".$row['imgsource']."'alt ='zdjecie mema'/> </td>";
        echo "<td><form method='post' action='memereportaccept.php'>
              <input type='hidden' name='id' value='" . $row['id_report'] . "'>
              <input type='submit' value='Akceptuj'></form></td>".
              "<td><form method='post' action='memereportdelete.php'>
              <input type='hidden' name='id' value='" . $row['id_report'] . "'>
              <input type='hidden' name='id_meme' value='" . $row['id_meme'] . "'>
              <input type='submit' value='Usuń'></form></td>";
        echo "</tr>";
    }
    echo "</table>";

?>
</div>

<div class="comment-report">
<h1>Zgłoszone Komentarze</h1>
<!--
    Wyświetlanie i obsługa zgłoszeń komentarzy
-->

<?php
     $reported_comments ="SELECT comment_report.id_report, comment_report.body, account.nick, comment.id_comment, comment.body as komentarz
     FROM comment_report 
     INNER JOIN account ON comment_report.id_user = account.id_user 
     INNER JOIN comment ON comment_report.id_comment = comment.id_comment;";
 
     $result = @$conn -> query($reported_comments);
     $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
     $result ->free_result();
 
     echo "<table class='table'>";
     echo "<tr><th>ID</th><th>Komentarz</th><th>Użytkownik</th><th>Powód</th><th colspan='2'>Akcja</th></tr>";
     foreach($comments as $row) {
         echo "<tr>";
         echo "<td>" . $row['id_report'] . "</td>";
         echo "<td>" . $row['komentarz'] . "</td>";
         echo "<td>" . $row['nick'] . "</td>";
         echo "<td>" . $row['body'] . "</td>";
         echo "<td><form method='post' action='commentreportaccept.php'>
               <input type='hidden' name='id' value='" . $row['id_report'] . "'>
               <input type='submit' value='Akceptuj'></form></td>".
               "<td><form method='post' action='commentreportdelete.php'>
               <input type='hidden' name='id' value='" . $row['id_report'] . "'>
               <input type='hidden' name='id_comment' value='" . $row['id_comment'] . "'>
               <input type='submit' value='Usuń'></form></td>";
         echo "</tr>";
       
     }
     echo "</table>";
?>
</div>

<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

<?php
 $conn -> close();
?>

    </main>
<footer class="footer">
    <div class="footer-container">
      <h3 class="footer-header">Mem<span class="footer-hub">Hub</span></h3>

      <ul class="footer-list">
        <li class="footer--list-item">
          <a class="footer--list-link" href="#">Kontakt</a>
        </li>
        <li class="footer--list-item">
          <a class="footer--list-link" href="#">Regulamin</a>
        </li>
      </ul>

      <p class="observe-us">Zaobserwuj nas na:</p>
      <div class="socials">
        <a aria-label="link do facebooka strony memhub" href="#" class="footer__social-media-link"><svg class="icon"
            xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff"
            stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
          </svg>
        </a>
        <a aria-label="link do instagrama strony memhub" href="#" class="footer__social-media-link"><svg class="icon"
            xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff"
            stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
          </svg>
        </a>
      </div>

      <div class="footer-line"></div>

      <div class="copyright">Copyright 2023. MemHub</div>
    </div>
  </footer>

</body>

<script>
var modal = document.getElementById('myModal');
var images = document.getElementsByClassName('myImages');
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");

for (var i = 0; i < images.length; i++) {
  var img = images[i];
  img.onclick = function(evt) {
    modal.style.display = "block";
    modalImg.src = this.src;
    captionText.innerHTML = this.alt;
  }
}

var span = document.getElementsByClassName("close")[0];

span.onclick = function() {
  modal.style.display = "none";
}
</script>

</html>