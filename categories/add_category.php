<?php
include __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Kiểm tra trùng lặp
    $check_sql = "SELECT * FROM categories WHERE name = '$name'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Danh mục đã tồn tại!";
    } else {
        // Thêm danh mục mới
        $sql = "INSERT INTO categories (name, description) VALUES ('$name', '$description')";

        if ($conn->query($sql) === TRUE) {
            echo "Thêm danh mục thành công!";
            header("Location: /admin2/index.php?page=categories");
            exit();
        } else {
            echo "Lỗi: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm danh mục</title>
</head>
<body>
    <h1>Thêm danh mục</h1>
    <form method="post" action="index.php?page=add_category">
        <label for="name">Tên danh mục:</label><br>
        <input type="text" id="name" name="name"><br>
        <label for="description">Mô tả:</label><br>
        <textarea id="description" name="description"></textarea><br><br>
        <input type="submit" value="Thêm">
    </form>
    <br>
    <a href="index.php">Trở lại danh sách</a>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
