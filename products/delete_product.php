<?php
include __DIR__ . '/../config/db.php';


$id = $_GET['id'];

// Xóa các biến thể của sản phẩm trước
$sql_variants = "DELETE FROM product_variants WHERE product_id = $id";
$conn->query($sql_variants);

// Xóa sản phẩm
$sql = "DELETE FROM products WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Xóa sản phẩm thành công!";
    header("Location: /admin2/index.php?page=products");
    exit();
} else {
    echo "Lỗi: " . $sql . "<br>" . $conn->error;
}

// Đóng kết nối
$conn->close();
?>
