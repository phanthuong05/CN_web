<?php
// TODO 1: Khởi động session
session_start();

// TODO 2: Kiểm tra người dùng đã gửi form chưa
if (isset($_POST['username']) && isset($_POST['password'])) {

    // TODO 3: Lấy dữ liệu POST
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    // TODO 4: Kiểm tra đăng nhập giả lập
    if ($user === 'admin' && $pass === '123') {

        // TODO 5: Lưu vào SESSION
        $_SESSION['logged_user'] = $user;

        // TODO 6: Chuyển hướng sang welcome.php
        header("Location: welcome.php");
        exit;
    } else {
        // Thất bại → quay lại login (kèm ?error=1)
        header("Location: login.html?error=1");

    
        exit;
    }

} else {
    // TODO 7: Người dùng truy cập trực tiếp file này → đá về login
    header("Location: login.html");
    exit;
}
?>
