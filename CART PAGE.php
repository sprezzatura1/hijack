

<?php
$dsn = 'mysql:host=localhost;dbname=shopunique'; // DSN
$username = 'root'; // Database user
$password = ''; // Database password
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]; // Options

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    // Use $pdo for database queries
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Check if add to cart button is clicked
if (isset($_POST['add_to_cart'])) {
    // Get product id from post request
    $id = $_POST['id'];
    // Check if cart session variable exists
    if (isset($_SESSION['cart'])) {
        // Check if product is already in cart
        if (array_key_exists($id, $_SESSION['cart'])) {
            // Increment product quantity by one
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            // Add product to cart with quantity one
            $_SESSION['cart'][$id] = array('quantity' => 1);
        }
    } else {
        // Create cart session variable and add product to cart with quantity one
        $_SESSION['cart'] = array($id => array('quantity' => 1));
    }
}
// Check if remove from cart button is clicked
if (isset($_POST['remove_from_cart'])) {
    // Get product id from post request
    $id = $_POST['id'];
    // Check if cart session variable exists
    if (isset($_SESSION['cart'])) {
        // Check if product is in cart
        if (array_key_exists($id, $_SESSION['cart'])) {
            // Decrement product quantity by one
            $_SESSION['cart'][$id]['quantity']--;
            // If product quantity is zero, remove product from cart
            if ($_SESSION['cart'][$id]['quantity'] == 0) {
                unset($_SESSION['cart'][$id]);
            }
        }
    }
}
// Check if update cart button is clicked
if (isset($_POST['update_cart'])) {
    // Get product id and quantity from post request
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];
    // Check if cart session variable exists
    if (isset($_SESSION['cart'])) {
        // Check if product is in cart
        if (array_key_exists($id, $_SESSION['cart'])) {
            // Update product quantity in cart
            $_SESSION['cart'][$id]['quantity'] = $quantity;
            // If product quantity is zero, remove product from cart
            if ($_SESSION['cart'][$id]['quantity'] == 0) {
                unset($_SESSION['cart'][$id]);
            }
        }
    }
}
// Check if checkout button is clicked
if (isset($_POST['checkout'])) {
    // Redirect to payment gateway or confirmation page
    header("Location: payment.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Page</title>
    <!-- Custom CSS -->
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        input[type=number] {
            width: 50px;
        }
        input[type=submit] {
            width: 100px;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Cart Page</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
        <?php
        // Initialize total price to zero
        // Check if cart session variable exists
        $total_price = 0;
        if (isset($_SESSION['cart'])) {
            // Loop through cart items
            foreach ($_SESSION['cart'] as $item) {
                // Get product details from database
                $sql = "SELECT * FROM shopunique WHERE id = :featured, men, women, new";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':featured, men, women, new', $item['featured, men, women, new']);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                // Calculate subtotal price
                $subtotal_price = $product['price'] * $item['quantity'];
                // Add subtotal price to total price
                $total_price += $subtotal_price;
                // Display cart item
                ?>
                <tr>
                    <td><?php echo $product['name']; ?></td>
                    <td>$<?php echo $product['price']; ?></td>
                    <td>
                        <form method="post" action="cart.php">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="0">
                            <input type="submit" name="update_cart" value="Update">
                        </form>
                    </td>
                    <td>$<?php echo $subtotal_price; ?></td>
                    <td>
                        <form method="post" action="cart.php">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <input type="submit" name="remove_from_cart" value="Remove">
                        </form>
                    </td>
                </tr>
                <?php
            }
        } else {
            // Display empty cart message
            echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
        }
        ?>
        <tr>
            <td colspan="3" class="total">Total:</td>
            <td class="total">$<?php echo $total_price; ?></td>
            <td>
                <form method="post" action="cart.php">
                    <input type="submit" name="checkout" value="Checkout">
                </form>
            </td>
        </tr>
    </table>
</body>
</html>
