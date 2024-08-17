<?php
include __DIR__ . '/../config/db.php';


// Lấy danh sách danh mục từ cơ sở dữ liệu
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Danh sách danh mục</title>
</head>
<body>
    <h1>Danh sách danh mục</h1>
    <a href="index.php?page=add_category">Thêm danh mục</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>

        <?php
        // Kiểm tra và hiển thị các danh mục
        if ($result->num_rows > 0) {
            // Hiển thị từng hàng dữ liệu
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                echo "<td>
                        <a href='index.php?page=edit_category&id=" . $row["id"] . "'>Sửa</a> | 
                        <a href='index.php?page=delete_category&id=" . $row["id"] . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\");'>Xóa</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Không có danh mục nào</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
