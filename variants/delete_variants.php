<?php
include __DIR__ . '/../config/db.php';

$variant_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($variant_id == 0 || $product_id == 0) {
    die("ID biến thể hoặc sản phẩm không hợp lệ.");
}

// Xóa biến thể
$sql_delete = "DELETE FROM product_variants WHERE id = $variant_id";

if ($conn->query($sql_delete) === TRUE) {
    // Chuyển hướng về trang chủ sau khi xóa thành công
    header("Location: index.php?page=variants&product_id=$product_id");
    exit();
} else {
    echo "Xóa thất bại: " . $conn->error;
}

$conn->close(); // Đóng kết nối cơ sở dữ liệu
?>
