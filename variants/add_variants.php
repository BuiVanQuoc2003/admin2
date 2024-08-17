<?php
include __DIR__ . '/../config/db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $color = $conn->real_escape_string($_POST['color']);
    $size = $conn->real_escape_string($_POST['size']);
    $quantity = intval($_POST['quantity']);
    $product_id = intval($_GET['product_id']); // Giả sử product_id được truyền qua URL

    // Kiểm tra xem biến thể đã tồn tại chưa
    $check_sql = "SELECT * FROM product_variants WHERE product_id = $product_id AND color = '$color' AND size = '$size'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        echo "Biến thể này đã tồn tại.";
    } else {
        // Thêm biến thể mới vào cơ sở dữ liệu
        $sql = "INSERT INTO product_variants (product_id, color, size, quantity) VALUES ($product_id, '$color', '$size', $quantity)";

        if ($conn->query($sql) === TRUE) {
            echo "Thêm biến thể thành công.";
            header("Location: index.php?page=variants&product_id=$product_id");

        } else {
            echo "Lỗi: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Thêm biến thể</title>
</head>
<body>
    <h1>Thêm biến thể cho sản phẩm</h1>
    <form method="post" action="">
        <label for="color">Màu sắc:</label><br>
        <input type="text" id="color" name="color" required><br>
        <label for="size">Kích thước:</label><br>
        <input type="text" id="size" name="size" required><br>
        <label for="quantity">Số lượng:</label><br>
        <input type="number" id="quantity" name="quantity" required><br><br>
        <input type="submit" value="Thêm">
    </form>
    <br>
    <a href="index.php?product_id=<?php echo $product_id; ?>">Trở lại danh sách biến thể</a>
</body>
</html>
