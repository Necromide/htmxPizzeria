<?php
include 'config.php';  // Die Konfigurationsdatei wird eingebunden
include "header.php";  // Hier wird der Header geladen

// Status-Update-Logik
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status'], $_POST['ordered_article_id'])) {
    $status = $_POST['status'];
    $ordered_article_id = $_POST['ordered_article_id'];

    $update_sql = "UPDATE ordered_articles SET status=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $status, $ordered_article_id);
    $stmt->execute();
}

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
            echo '<div class="order-item">';
            echo '<p>Pizza: ' . $row['name'] . ' - Bestellnummer: ' . $row['order_id'] . '</p>';
            echo '<form action="baecker.php" method="post">';
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
include 'footer.php';  // Der Footer wird eingebunden
?>
