<?php
include __DIR__ . '/../config/db.php';


// Lấy danh sách sản phẩm từ cơ sở dữ liệu
$sql = "SELECT p.id, p.name, c.name AS category_name, p.price, p.discount 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id";
$result = $conn->query($sql);
?>

<h1>Danh sách sản phẩm</h1>
<a href="index.php?page=add_product">Thêm sản phẩm</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Danh mục</th>
        <th>Giá</th>
        <th>Giảm giá (%)</th> 
        <th>Biến thể</th>
        <th>Hành động</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["category_name"] . "</td>";
            echo "<td>" . $row["price"] . "</td>";
            echo "<td>" . $row["discount"] . "</td>";
            echo "<td><a href='index.php?page=variants&product_id=" . $row["id"] . "'>Quản lý biến thể</a></td>";
            echo "<td>
                    <a href='index.php?page=edit_product&id=" . $row["id"] . "'>Sửa</a> | 
                    <a href='index.php?page=delete_product&id=" . $row["id"] . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\");'>Xóa</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Không có sản phẩm nào</td></tr>";
    }
    ?>
</table>

<?php
$conn->close(); // Đóng kết nối cơ sở dữ liệu
?>
