<?php
include __DIR__ . '/../config/db.php';

$id = $_GET['id'];

// Kiểm tra xem danh mục có chứa sản phẩm nào không
$check_sql = "SELECT COUNT(*) AS product_count FROM products WHERE category_id = $id";
$check_result = $conn->query($check_sql);
$check_row = $check_result->fetch_assoc();

if ($check_row['product_count'] > 0) {
    // Nếu danh mục còn sản phẩm, hiển thị thông báo
    echo "Không thể xóa danh mục vì vẫn còn sản phẩm trong danh mục!";
} else {
    // Nếu không còn sản phẩm nào, tiếp tục xóa danh mục
    $sql = "DELETE FROM categories WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Xóa danh mục thành công!";
        header("Location: /admin2/index.php?page=categories");
        exit();
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

// Đóng kết nối
$conn->close();
?>
