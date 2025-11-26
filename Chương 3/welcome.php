<?php
// TODO 1: Khởi động session
session_start();

// TODO 2: Kiểm tra SESSION có tồn tại không
if (isset($_SESSION['logged_user'])) {

    // TODO 3: Lấy username từ SESSION
    $loggedInUser = htmlspecialchars($_SESSION['logged_user'], ENT_QUOTES, 'UTF-8');

    // TODO 4: In lời chào
    echo "<h1>Chào mừng trở lại, $loggedInUser!</h1>";
    echo "<p>Bạn đã đăng nhập thành công.</p>";

    // TODO 5: Link đăng xuất
    echo '<p><a href="logout.php">Đăng xuất</a></p>';

} else {
    // TODO 6: Nếu chưa đăng nhập → quay về login
    header("Location: login.html");
    exit;
}
?>
