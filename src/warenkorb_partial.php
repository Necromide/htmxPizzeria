<div id="warenkorb-content">

    <?php
    // Voraussetzung ist, dass $_SESSION['warenkorb'] und die $conn-Verbindung bereits initialisiert wurden.

    $gesamtpreis = 0;

    if (!empty($_SESSION['warenkorb'])) {
        foreach ($_SESSION['warenkorb'] as $product_id => $quantity) {
            $sql_product = "SELECT * FROM article WHERE id = $product_id";
            $product_result = mysqli_query($conn, $sql_product);
            $product = mysqli_fetch_assoc($product_result);

            // Preis für dieses Produkt zum Gesamtpreis hinzufügen
            $gesamtpreis += $product['price'] * $quantity;

            echo '<div>';
            echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') . ' x' . intval($quantity);
            echo '<form action="speisekarte.php" method="post" hx-post="speisekarte.php" hx-target="#warenkorb-content" hx-swap="innerHTML">';
            echo '<input type="hidden" name="product_id" value="' . intval($product_id) . '">';
            echo '<button type="submit" name="remove_from_cart">Entfernen</button>';
            echo '</form>';
            echo '</div>';
        }

        // Gesamtpreis anzeigen
        echo "<div><strong>Gesamtpreis:</strong> " . number_format($gesamtpreis, 2) . "€</div>";
    } else {
        echo "<p>Ihr Warenkorb ist leer.</p>";
    }
    ?>

</div>
