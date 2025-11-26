<?php 
    // Tải dữ liệu hoa
    include 'data.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Quản lý Danh sách Hoa (PHP)</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h1>Quản lý Danh sách Hoa (PHP)</h1>

    <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ảnh</th>
                <th>Tên Hoa</th>
                <th>Mô Tả</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            
            <?php foreach ($flowers as $index => $f): ?>
            
            <tr>
                <td><?= $index + 1 ?></td> 
                <td><img src="<?= $f['image'] ?>" width="80" height="80" style="object-fit: cover; border-radius: 4px;"></td>
                <td><?= $f['name'] ?></td>
                <td><?= $f['desc'] ?></td>
                <td>
                    <button onclick="alert('Sửa hoa index: <?= $index ?>')">Sửa</button>
                    <button onclick="confirm('Xóa hoa index: <?= $index ?>?')">Xóa</button>
                </td>
            </tr>
            
            <?php endforeach; ?>
            
        </tbody>
    </table>
</body>
</html>