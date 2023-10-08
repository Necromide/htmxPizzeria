<?php
$ordered_article_id = $_POST['ordered_article_id'];
$sql = "SELECT ordered_articles.id AS ordered_article_id, article.name, ordering.id AS order_id, ordered_articles.status 
        FROM ordered_articles 
        JOIN article ON ordered_articles.f_article_id = article.id 
        JOIN ordering ON ordered_articles.f_order_id = ordering.id
        WHERE ordered_articles.id = $ordered_article_id";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

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
?>

