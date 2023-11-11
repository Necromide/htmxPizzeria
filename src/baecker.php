<?php
include 'config.php';  // Die Konfigurationsdatei wird eingebunden

// Status-Update-Logik
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status'], $_POST['ordered_article_id'])) {
    $status = $_POST['status'];
    $ordered_article_id = $_POST['ordered_article_id'];

    $update_sql = "UPDATE ordered_articles SET status=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $status, $ordered_article_id);
    $stmt->execute();
}

// Überprüfen, ob es sich um eine HTMX-Anfrage handelt
if (isset($_SERVER['HTTP_HX_REQUEST'])) {
    // Nur den relevanten Codeabschnitt für die HTMX-Anfrage ausgeben und dann beenden
    include "update_order_item.php";
    exit();
}

// Wenn es keine HTMX-Anfrage ist, Header einbinden
include "header.php";
?>

<main>
    <h2>Bäcker Dashboard</h2>

    <?php
    $sql = "SELECT ordered_articles.id AS ordered_article_id, article.name, ordering.id AS order_id, ordered_articles.status 
            FROM ordered_articles 
            JOIN article ON ordered_articles.f_article_id = article.id 
            JOIN ordering ON ordered_articles.f_order_id = ordering.id
            WHERE ordered_articles.status != 2";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="order-item" id="order-item-' . $row['ordered_article_id'] . '">';
            echo '<p>Pizza: ' . $row['name'] . ' - Bestellnummer: ' . $row['order_id'] . '</p>';
            echo '<form action="baecker.php" method="post" hx-post="baecker.php" hx-target="#order-item-' . $row['ordered_article_id'] . '" hx-swap="outerHTML">';
            echo '<select name="status">';
            echo '<option value="0"' . ($row['status'] == 0 ? ' selected' : '') . '>In Bearbeitung</option>';
            echo '<option value="1"' . ($row['status'] == 1 ? ' selected' : '') . '>Fertig zum Liefern</option>';
            echo '</select>';
            echo '<input type="hidden" name="ordered_article_id" value="' . $row['ordered_article_id'] . '">';
            echo '<input type="submit" value="Status aktualisieren">';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo "<p>Keine aktuellen Pizzabestellungen.</p>";
    }
    ?>

</main>

<?php
// Wenn es keine HTMX-Anfrage ist, Footer einbinden
include 'footer.php';
?>