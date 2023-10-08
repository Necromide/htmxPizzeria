<?php
include 'config.php';

// Produkt zum Warenkorb hinzufügen
if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    if (isset($_SESSION['warenkorb'][$product_id])) {
        $_SESSION['warenkorb'][$product_id]++;
    } else {
        $_SESSION['warenkorb'][$product_id] = 1;
    }
}

// Produkt aus dem Warenkorb entfernen
if (isset($_POST['remove_from_cart']) && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    if ($_SESSION['warenkorb'][$product_id] > 1) {
        $_SESSION['warenkorb'][$product_id]--;
    } else {
        unset($_SESSION['warenkorb'][$product_id]);
    }
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
        echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                var modal = document.getElementById("confirmation-modal");
                modal.style.display = "block";
            });
        </script>';
    }
}

// Überprüfen, ob es sich um eine HTMX-Anfrage handelt
if (isset($_SERVER['HTTP_HX_REQUEST'])) {
    include 'warenkorb_partial.php';
    exit();
}

include "header.php";
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
                echo '<form action="speisekarte.php" method="post" hx-post="speisekarte.php" hx-target="#warenkorb-content" hx-swap="innerHTML">';
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
    <div class="warenkorb" id="warenkorb-content">
        <?php
        include 'warenkorb_partial.php';
        ?>
    </div>

    <!-- Adresse und Bestellung abschließen -->
    <h2>Bestellung abschließen</h2>
    <form action="speisekarte.php" method="post">
        <label for="address">Adresse:</label>
        <textarea name="address" required></textarea>
        <input type="submit" name="submit_order" value="Bestellung abschließen">
    </form>

    <!-- Modal für Bestätigung -->
    <div id="confirmation-modal" style="display:none; position:fixed; top:30%; left:30%; width:30%; height:25%; z-index:1000; background-color: rgba(0,0,0,0.8); color: white; padding: 20px; border-radius: 10px; text-align: center;">
        <h2 style="font-size: 24px;">Bestellung bestätigt!</h2>
        <p style="font-size: 18px; margin-bottom: 20px;">Ihre Bestellung wurde erfolgreich aufgenommen.</p>
        <button onclick="document.getElementById('confirmation-modal').style.display='none';" style="padding: 10px 20px; background-color: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 18px;">OK</button>
    </div>



</main>

<?php
include 'footer.php';
?>
