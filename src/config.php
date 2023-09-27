<?php
// Session wird gestartet
session_start();

// Warenkorb-Initialisierung (wenn er noch nicht existiert)
if (!isset($_SESSION['warenkorb'])) {
    $_SESSION['warenkorb'] = array();
}

// Konfigurationsvariablen für die Datenbankverbindung
$host = 'localhost'; // Der Hostname deines Servers (in den meisten Fällen "localhost")
$db = 'pizzaservice_2020'; // Name der Datenbank
$user = 'root'; // Benutzername (bei XAMPP meist "root")
$pass = ''; // Passwort (bei XAMPP oft leer)

// Aufstellung einer Verbindung zur Datenbank mit mysqli
$conn = mysqli_connect($host, $user, $pass, $db);

// Prüfe, ob die Verbindung fehlerfrei hergestellt wurde
if (!$conn) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}

// Setzt den Zeichensatz für die Verbindung auf UTF-8, um Sonderzeichen korrekt zu behandeln
mysqli_set_charset($conn, "utf8");

// Weitere allgemeine Einstellungen oder Funktionen können hier hinzugefügt werden
?>
