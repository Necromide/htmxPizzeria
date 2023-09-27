<?php
include 'config.php';  // Die Konfigurationsdatei wird eingebunden
include "header.php";  // Hier wird der Header geladen

// Status-Update-Logik
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status'], $_POST['order_id'])) {
    $status = $_POST['status'];
    $order_id = $_POST['order_id'];

    $update_sql = "UPDATE ordered_articles SET status=? WHERE f_order_id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $status, $order_id);
    $stmt->execute();
}

?>

<main>
    <h2>Lieferant Dashboard</h2>

    <?php
    $sql = "SELECT ordering.id AS order_id, ordering.address 
            FROM ordering 
            WHERE EXISTS (
                SELECT 1 
                FROM ordered_articles 
                WHERE ordered_articles.f_order_id = ordering.id 
                GROUP BY f_order_id 
                HAVING COUNT(status) = SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END)
            )";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="order">';
            echo '<p>Bestellnummer: ' . $row['order_id'] . ' - Lieferadresse: ' . $row['address'] . '</p>';
            echo '<form action="lieferant.php" method="post">';
            echo '<select name="status">';
            echo '<option value="1"' . '>Fertig zum Liefern</option>';
            echo '<option value="2"' . '>Geliefert</option>';
            echo '</select>';
            echo '<input type="hidden" name="order_id" value="' . $row['order_id'] . '">';
            echo '<input type="submit" value="Status aktualisieren">';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo "<p>Keine aktuellen Lieferungen.</p>";
    }
    ?>

</main>

<?php
include 'footer.php';  // Der Footer wird eingebunden
?>
