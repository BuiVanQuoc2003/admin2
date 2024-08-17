<?php
include __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $variant_id = intval($_POST['id']);
    $color = $conn->real_escape_string($_POST['color']);
    $size = $conn->real_escape_string($_POST['size']);
    $quantity = intval($_POST['quantity']);
    $product_id = intval($_POST['product_id']);

    // Kiểm tra xem biến thể với màu sắc và kích thước này đã tồn tại chưa (ngoại trừ biến thể hiện tại)
    $sql_check = "SELECT * FROM product_variants WHERE product_id = $product_id AND color = '$color' AND size = '$size' AND id != $variant_id";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "Biến thể với màu sắc '$color' và kích thước '$size' đã tồn tại cho sản phẩm này.";
    } else {
        // Cập nhật thông tin biến thể
        $sql_update = "UPDATE product_variants SET color = '$color', size = '$size', quantity = $quantity WHERE id = $variant_id";

        if ($conn->query($sql_update) === TRUE) {
            // Chuyển hướng về trang chủ sau khi sửa thành công
            header("Location: index.php?page=variants&product_id=$product_id");
            exit();
        } else {
            echo "Cập nhật thất bại: " . $conn->error;
        }
    }
}

// Lấy thông tin biến thể để hiển thị trong form
$variant_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($variant_id == 0) {
    die("ID biến thể không hợp lệ.");
}

$sql_variant = "SELECT * FROM product_variants WHERE id = $variant_id";
$variant_result = $conn->query($sql_variant);
$variant = $variant_result->fetch_assoc();

if (!$variant) {
    die("Không tìm thấy biến thể.");
}
?>

<h1>Sửa biến thể</h1>
<form method="post" action="">
    <input type="hidden" name="id" value="<?php echo $variant_id; ?>">
    <input type="hidden" name="product_id" value="<?php echo $variant['product_id']; ?>">
    <label for="color">Màu sắc:</label>
    <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($variant['color']); ?>"><br><br>
    <label for="size">Kích thước:</label>
    <input type="text" id="size" name="size" value="<?php echo htmlspecialchars($variant['size']); ?>"><br><br>
    <label for="quantity">Số lượng:</label>
    <input type="number" id="quantity" name="quantity" value="<?php echo intval($variant['quantity']); ?>"><br><br>
    <input type="submit" value="Cập nhật">
</form>

<?php
$conn->close(); // Đóng kết nối cơ sở dữ liệu
?>
