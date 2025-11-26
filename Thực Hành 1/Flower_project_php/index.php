<?php 
    // Tải dữ liệu hoa
    include 'data.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách 14 Loài Hoa Tuyệt Đẹp (PHP)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>🌸 14 Loại Hoa Tuyệt Đẹp Cho Mùa Xuân Hè 🌸</h1>
    <div id="flower-list" class="grid">
        
        <?php foreach ($flowers as $flower): ?>
        
            <div class="card">
                <img src="<?= $flower['image'] ?>" alt="<?= $flower['name'] ?>">
                <h3><?= $flower['name'] ?></h3>
                <p><?= $flower['desc'] ?></p>
            </div>
        
        <?php endforeach; ?>
        <a href="admin.php">Trang Quản Trị </a>
    </div>
</body>
</html>