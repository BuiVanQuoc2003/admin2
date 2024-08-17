<?php
include __DIR__ . '/../config/db.php';

$id = $_GET['id'];

// Lấy thông tin sản phẩm hiện tại
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Không tìm thấy sản phẩm!";
    exit();
}

// Lấy các biến thể của sản phẩm
$sql_variants = "SELECT * FROM product_variants WHERE product_id = $id";
$variants_result = $conn->query($sql_variants);
$variants = [];
if ($variants_result->num_rows > 0) {
    while ($row = $variants_result->fetch_assoc()) {
        $variants[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];

    // Kiểm tra trùng lặp tên sản phẩm trong cùng danh mục
    $sql_check = "SELECT * FROM products WHERE name = '$name' AND category_id = '$category_id' AND id != $id";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "Lỗi: Tên sản phẩm đã tồn tại trong danh mục này!";
    } else {
        // Cập nhật thông tin sản phẩm
        $sql = "UPDATE products SET name='$name', category_id='$category_id', price='$price', discount='$discount' WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            // Cập nhật các biến thể
            foreach ($_POST['variants'] as $variant) {
                $variant_id = $variant['id'];
                $color = $variant['color'];
                $size = $variant['size'];
                $quantity = $variant['quantity'];
                if ($variant_id == 0) {
                    // Thêm biến thể mới
                    $sql_variant = "INSERT INTO product_variants (product_id, color, size, quantity) 
                                    VALUES ('$id', '$color', '$size', '$quantity')";
                } else {
                    // Cập nhật biến thể hiện có
                    $sql_variant = "UPDATE product_variants SET color='$color', size='$size', quantity='$quantity' WHERE id='$variant_id'";
                }
                $conn->query($sql_variant);
            }

            echo "Cập nhật sản phẩm và biến thể thành công!";
            header("Location: /admin2/index.php?page=products");
            exit();
        } else {
            echo "Lỗi: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Lấy danh sách danh mục để hiển thị trong form
$sql = "SELECT * FROM categories";
$categories = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sửa sản phẩm</title>
</head>
<body>
    <h1>Sửa sản phẩm</h1>
    <form method="post" action="">
        <label for="name">Tên sản phẩm:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>"><br>
        <label for="category_id">Danh mục:</label><br>
        <select id="category_id" name="category_id">
            <?php
            if ($categories->num_rows > 0) {
                while($row = $categories->fetch_assoc()) {
                    $selected = $row['id'] == $product['category_id'] ? "selected" : "";
                    echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                }
            }
            ?>
        </select><br>
        <label for="price">Giá:</label><br>
        <input type="text" id="price" name="price" value="<?php echo $product['price']; ?>"><br>
        <label for="discount">Giảm giá (%):</label><br>
        <input type="text" id="discount" name="discount" value="<?php echo $product['discount']; ?>"><br><br>

        <h3>Biến thể sản phẩm</h3>
        <div id="variants">
            <?php
            foreach ($variants as $index => $variant) {
                echo "<div class='variant'>";
                echo "<input type='hidden' name='variants[$index][id]' value='" . $variant['id'] . "'>";
                echo "<label for='color'>Màu sắc:</label><br>";
                echo "<input type='text' name='variants[$index][color]' value='" . $variant['color'] . "'><br>";
                echo "<label for='size'>Kích thước:</label><br>";
                echo "<input type='text' name='variants[$index][size]' value='" . $variant['size'] . "'><br>";
                echo "<label for='quantity'>Số lượng:</label><br>";
                echo "<input type='text' name='variants[$index][quantity]' value='" . $variant['quantity'] . "'><br><br>";
                echo "</div>";
            }
            ?>
        </div>
        <button type="button" onclick="addVariant()">Thêm biến thể</button><br><br>

        <input type="submit" value="Cập nhật">
    </form>
    <br>
    <a href="index.php">Trở lại danh sách</a>

    <script>
        let variantIndex = <?php echo count($variants); ?>;
        function addVariant() {
            const variantsDiv = document.getElementById('variants');
            const newVariantDiv = document.createElement('div');
            newVariantDiv.className = 'variant';
            newVariantDiv.innerHTML = `
                <input type='hidden' name='variants[${variantIndex}][id]' value='0'>
                <label for='color'>Màu sắc:</label><br>
                <input type='text' name='variants[${variantIndex}][color]'><br>
                <label for='size'>Kích thước:</label><br>
                <input type='text' name='variants[${variantIndex}][size]'><br>
                <label for='quantity'>Số lượng:</label><br>
                <input type='text' name='variants[${variantIndex}][quantity]'><br><br>
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
