<?php
include __DIR__ . '/../config/db.php';

$id = $_GET['id'];

// Lấy thông tin danh mục hiện tại
$sql = "SELECT * FROM categories WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $category = $result->fetch_assoc();
} else {
    echo "Không tìm thấy danh mục!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Kiểm tra trùng lặp tên danh mục với danh mục khác
    $check_sql = "SELECT * FROM categories WHERE name = '$name' AND id != $id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Tên danh mục đã tồn tại!";
    } else {
        // Cập nhật danh mục
        $sql = "UPDATE categories SET name='$name', description='$description' WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "Cập nhật danh mục thành công!";
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
    <title>Sửa danh mục</title>
</head>
<body>
    <h1>Sửa danh mục</h1>
    <form method="post" action="index.php?page=edit_category&id=<?php echo $id; ?>">
        <label for="name">Tên danh mục:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $category['name']; ?>"><br>
        <label for="description">Mô tả:</label><br>
        <textarea id="description" name="description"><?php echo $category['description']; ?></textarea><br><br>
        <input type="submit" value="Cập nhật">
    </form>
    <br>
    <a href="index.php">Trở lại danh sách</a>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
