<?php
include __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];

    // Thêm sản phẩm vào cơ sở dữ liệu
    $sql = "INSERT INTO products (name, category_id, price, discount) 
            VALUES ('$name', '$category_id', '$price', '$discount')";

    if ($conn->query($sql) === TRUE) {
        $product_id = $conn->insert_id; // Lấy ID của sản phẩm vừa thêm

        // Thêm các biến thể cho sản phẩm
        foreach ($_POST['variants'] as $variant) {
            $color = $variant['color'];
            $size = $variant['size'];
            $quantity = $variant['quantity'];

            // Kiểm tra xem biến thể này đã tồn tại chưa
            $check_sql = "SELECT * FROM product_variants WHERE product_id = '$product_id' AND color = '$color' AND size = '$size'";
            $result = $conn->query($check_sql);

            if ($result->num_rows == 0) {
                // Nếu biến thể chưa tồn tại, thêm mới
                $sql_variant = "INSERT INTO product_variants (product_id, color, size, quantity) 
                                VALUES ('$product_id', '$color', '$size', '$quantity')";
                $conn->query($sql_variant);
            } else {
                echo "Biến thể với màu '$color' và kích thước '$size' đã tồn tại cho sản phẩm này.<br>";
            }
        }

        echo "Thêm sản phẩm và biến thể thành công!";
        header("Location: /admin2/index.php?page=products");
        exit();
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

// Lấy danh sách danh mục để hiển thị trong form
$sql = "SELECT * FROM categories";
$categories = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm sản phẩm</title>
</head>
<body>
    <h1>Thêm sản phẩm</h1>
    <form method="post" action="">
        <label for="name">Tên sản phẩm:</label><br>
        <input type="text" id="name" name="name"><br>
        <label for="category_id">Danh mục:</label><br>
        <select id="category_id" name="category_id">
            <?php
            if ($categories->num_rows > 0) {
                while($row = $categories->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                }
            }
            ?>
        </select><br>
        <label for="price">Giá:</label><br>
        <input type="text" id="price" name="price"><br>
        <label for="discount">Giảm giá (%):</label><br>
        <input type="text" id="discount" name="discount"><br><br>

        <h3>Biến thể sản phẩm</h3>
        <div id="variants">
            <div class="variant">
                <label for="color">Màu sắc:</label><br>
                <input type="text" name="variants[0][color]"><br>
                <label for="size">Kích thước:</label><br>
                <input type="text" name="variants[0][size]"><br>
                <label for="quantity">Số lượng:</label><br>
                <input type="text" name="variants[0][quantity]"><br><br>
            </div>
        </div>
        <button type="button" onclick="addVariant()">Thêm biến thể</button><br><br>

        <input type="submit" value="Thêm">
    </form>
    <br>
    <a href="index.php">Trở lại danh sách</a>

    <script>
        let variantIndex = 1;
        function addVariant() {
            const variantsDiv = document.getElementById('variants');
            const newVariantDiv = document.createElement('div');
            newVariantDiv.className = 'variant';
            newVariantDiv.innerHTML = `
                <label for="color">Màu sắc:</label><br>
                <input type="text" name="variants[${variantIndex}][color]"><br>
                <label for="size">Kích thước:</label><br>
                <input type="text" name="variants[${variantIndex}][size]"><br>
                <label for="quantity">Số lượng:</label><br>
                <input type="text" name="variants[${variantIndex}][quantity]"><br><br>
            `;
            variantsDiv.appendChild(newVariantDiv);
            variantIndex++;
        }
    </script>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
