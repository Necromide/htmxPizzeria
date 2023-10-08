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

if ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="order" id="order-' . $row['order_id'] . '">';
    echo '<p>Bestellnummer: ' . $row['order_id'] . ' - Lieferadresse: ' . $row['address'] . '</p>';
    echo '<form action="lieferant.php" method="post" hx-post="lieferant.php" hx-target="#order-' . $row['order_id'] . '" hx-swap="outerHTML">';
    echo '<select name="status">';
    echo '<option value="1"' . '>Fertig zum Liefern</option>';
    echo '<option value="2"' . '>Geliefert</option>';
    echo '</select>';
    echo '<input type="hidden" name="order_id" value="' . $row['order_id'] . '">';
    echo '<input type="submit" value="Status aktualisieren">';
    echo '</form>';
    echo '</div>';
}
?>
