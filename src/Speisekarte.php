<?php
include 'config.php';
include "header.php";

// Produkt zum Warenkorb hinzufügen
if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']); // integer casting als zusätzliche Schutzschicht
    if (isset($_SESSION['warenkorb'][$product_id])) {
        $_SESSION['warenkorb'][$product_id]++;
    } else {
        $_SESSION['warenkorb'][$product_id] = 1;
    }
}

// Produkt aus dem Warenkorb entfernen
if (isset($_POST['remove_from_cart']) && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    unset($_SESSION['warenkorb'][$product_id]);
}

// Bestellung abschließen
if (isset($_POST['submit_order']) && isset($_POST['address']) && !empty($_SESSION['warenkorb'])) {
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $sql_insert_order = "INSERT INTO `ordering` (address) VALUES ('$address')";
    if (mysqli_query($conn, $sql_insert_order)) {
        $last_order_id = mysqli_insert_id($conn);
        foreach ($_SESSION['warenkorb'] as $product_id => $quantity) {
            $product_id_escaped = mysqli_real_escape_string($conn, $product_id);
            for ($i = 0; $i < $quantity; $i++) {
                $sql_insert_ordered_article = "INSERT INTO `ordered_articles` (f_article_id, f_order_id) VALUES ($product_id_escaped, $last_order_id)";
                mysqli_query($conn, $sql_insert_ordered_article);
            }
        }
        unset($_SESSION['warenkorb']);
        echo "<p>Bestellung erfolgreich abgeschlossen!</p>";
    }
}

?>

<main>
    <h2>Unsere Speisekarte</h2>
    <div class="speisekarte">
        <?php
        $sql = "SELECT * FROM article";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="pizza">';
                echo '<img src="' . htmlspecialchars($row['picture'], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '" style="width: 200px; height: auto; max-height: 200px; object-fit: cover; border-radius: 10px;">';
                echo '<h3>' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '</h3>';
                echo '<p>Preis: ' . htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') . '€</p>';
                echo '<form action="speisekarte.php" method="post">';
                echo '<input type="hidden" name="product_id" value="' . intval($row['id']) . '">';
                echo '<button type="submit" name="add_to_cart">In den Warenkorb</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "<p>Derzeit sind keine Pizzen in unserer Speisekarte.</p>";
        }
        ?>
    </div>

    <!-- Warenkorb Anzeige -->
    <h2>Ihr Warenkorb</h2>
    <div class="warenkorb">
        <?php
        $gesamtpreis = 0; // Variable für den Gesamtpreis des Warenkorbs

        if (!empty($_SESSION['warenkorb'])) {
            foreach ($_SESSION['warenkorb'] as $product_id => $quantity) {
                $sql_product = "SELECT * FROM article WHERE id = $product_id";
                $product_result = mysqli_query($conn, $sql_product);
                $product = mysqli_fetch_assoc($product_result);

                // Preis für dieses Produkt zum Gesamtpreis hinzufügen
                $gesamtpreis += $product['price'] * $quantity;

                echo '<div>';
                echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') . ' x' . intval($quantity);
                echo '<form action="speisekarte.php" method="post">';
                echo '<input type="hidden" name="product_id" value="' . intval($product_id) . '">';
                echo '<button type="submit" name="remove_from_cart">Entfernen</button>';
                echo '</form>';
                echo '</div>';
            }

            // Gesamtpreis anzeigen
            echo "<div><strong>Gesamtpreis:</strong> " . number_format($gesamtpreis, 2) . "€</div>"; // number_format wird verwendet, um den Preis mit 2 Dezimalstellen anzuzeigen
        } else {
            echo "<p>Ihr Warenkorb ist leer.</p>";
        }
        ?>
    </div>

    <!-- Adresse und Bestellung abschließen -->
    <h2>Bestellung abschließen</h2>
    <form action="speisekarte.php" method="post">
        <label for="address">Adresse:</label>
        <textarea name="address" required></textarea>
        <input type="submit" name="submit_order" value="Bestellung abschließen">
    </form>

</main>

<?php
include 'footer.php';
?>
