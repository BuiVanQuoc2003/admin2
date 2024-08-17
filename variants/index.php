<?php
include __DIR__ . '/../config/db.php';


$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id == 0) {
    die("Product ID không hợp lệ.");
}

// Lấy danh sách các biến thể của sản phẩm
$sql_variants = "SELECT * FROM product_variants WHERE product_id = $product_id";
$variants_result = $conn->query($sql_variants);

// Lấy thông tin sản phẩm
$sql_product = "SELECT * FROM products WHERE id = $product_id";
$product_result = $conn->query($sql_product);
$product = $product_result->fetch_assoc();
?>

<h1>Biến thể của sản phẩm: <?php echo htmlspecialchars($product['name']); ?></h1>
<a href="index.php?page=add_variants&product_id=<?php echo $product_id; ?>">Thêm biến thể</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Màu sắc</th>
        <th>Kích thước</th>
        <th>Số lượng</th>
        <th>Hành động</th>
    </tr>

    <?php
    if ($variants_result->num_rows > 0) {
        while($variant = $variants_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $variant["id"] . "</td>";
            echo "<td>" . htmlspecialchars($variant["color"]) . "</td>";
            echo "<td>" . htmlspecialchars($variant["size"]) . "</td>";
            echo "<td>" . intval($variant["quantity"]) . "</td>";
            echo "<td>
                    <a href='index.php?page=edit_variants&id=" . $variant["id"] . "&product_id=" . $product_id . "'>Sửa</a> | 
                    <a href='index.php?page=delete_variants&id=" . $variant["id"] . "&product_id=" . $product_id . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\");'>Xóa</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Không có biến thể nào</td></tr>";
    }
    ?>
</table>

<?php
$conn->close(); // Đóng kết nối cơ sở dữ liệu
?>
