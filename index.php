<?php
// Xác định trang hiện tại dựa trên tham số "page" trong URL
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Kiểm tra giá trị của $page để xác định file cần include
switch ($page) {
    case 'products':
        $pageFile = 'products/index.php';
        break;
    case 'categories':
        $pageFile = 'categories/index.php';
        break;
    case 'variants':
        $pageFile = 'variants/index.php';
        break;       
    case 'orders':
        $pageFile = 'orders.php';
        break;
    case 'users':
        $pageFile = 'users.php';
        break;
    case 'settings':
        $pageFile = 'settings.php';
        break;


    case 'add_product':
        $pageFile = 'products/add_product.php';
        break;
    case 'edit_product':
        $pageFile = 'products/edit_product.php';
        break;
    case 'delete_product':
        $pageFile = 'products/delete_product.php';
        break;


    case 'add_category':
        $pageFile = 'categories/add_category.php';
        break;
    case 'edit_category':
        $pageFile = 'categories/edit_category.php';
    break;
    case 'delete_category':
        $pageFile = 'categories/delete_category.php';
    break;


    case 'add_variants':
        $pageFile = 'variants/add_variants.php';
        break;
    case 'edit_variants':
        $pageFile = 'variants/edit_variants.php';
    break;
    case 'delete_variants':
        $pageFile = 'variants/delete_variants.php';
    break;

    
    default:
        $pageFile = 'dashboard.php';
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="index.php?page=dashboard">Trang Chủ</a></li>
            <li><a href="index.php?page=categories">Danh Mục</a></li>
            <li><a href="index.php?page=products">Sản Phẩm</a></li>
            <li><a href="index.php?page=orders">Đơn Hàng</a></li>
            <li><a href="index.php?page=users">Người DÙng</a></li>
            <li><a href="index.php?page=settings">Settings</a></li>
            <li><a href="index.php?page=logout">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <?php
        // Kiểm tra file tồn tại trước khi include
        if (file_exists($pageFile)) {
            include($pageFile);
        } else {
            echo "<h1>Page not found</h1>";
        }
        ?>
    </div>
</body>
</html>
