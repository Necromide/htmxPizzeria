<?php
include "config.php";  // Hier wird die Session gestartet und weitere allgemeine Einstellungen geladen
include "header.php";  // Hier wird der Header geladen
?>

<main>
    <h2>Kontakt</h2>
    <p>Wir freuen uns auf Ihre Nachricht! Sie können uns über das untenstehende Formular oder direkt per Telefon oder E-Mail erreichen.</p>

    <h3>Kontaktformular:</h3>
    <form action="senden.php" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">E-Mail:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="message">Nachricht:</label><br>
        <textarea id="message" name="message" rows="4" required></textarea><br><br>

        <input type="submit" value="Senden">
    </form>
</main>

<?php
include "footer.php";  // Hier wird der Footer geladen
?>
