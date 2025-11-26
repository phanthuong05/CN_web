<?php
// Tên tệp tin CSV
const CSV_FILE = 'accounts.csv';

/**
 * Hàm đọc nội dung tệp CSV và trả về một mảng dữ liệu.
 */
function read_csv_data() {
    $data = [];
    
    // Kiểm tra tệp tin có tồn tại không
    if (!file_exists(CSV_FILE)) {
        return "Lỗi: Không tìm thấy tệp " . CSV_FILE;
    }

    // Mở tệp tin để đọc
    // Sử dụng 'r' để đọc
    if (($handle = fopen(CSV_FILE, "r")) !== FALSE) {
        
        // fgetcsv() tự động phân tích cú pháp các dòng CSV
        // 1000: độ dài tối đa của dòng
        // ,: dấu phân cách (delimiter)
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $data[] = $row;
        }
        fclose($handle);
    } else {
        return "Lỗi: Không thể mở tệp tin " . CSV_FILE;
    }
    
    return $data;
}

$csv_data = read_csv_data();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiển thị Dữ liệu Tài khoản CSV</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>Danh Sách Tài Khoản Người Dùng (Đọc từ CSV)</h1>

        <?php 
        if (is_string($csv_data)) {
            // Hiển thị thông báo lỗi nếu có
            echo "<p style='color: red; text-align: center;'>$csv_data</p>";
        } else if (empty($csv_data)) {
            // Hiển thị nếu tệp rỗng
            echo "<p style='text-align: center;'>Tệp tin CSV không có dữ liệu.</p>";
        } else {
            // Dòng đầu tiên là tiêu đề (header)
            $header = array_shift($csv_data); 
            
            // Bắt đầu vẽ bảng HTML
            echo "<table>";
            
            // Vẽ tiêu đề (Header)
            echo "<thead><tr>";
            foreach ($header as $col_name) {
                echo "<th>" . htmlspecialchars($col_name) . "</th>";
            }
            echo "</tr></thead>";
            
            // Vẽ Nội dung (Body)
            echo "<tbody>";
            foreach ($csv_data as $row) {
                echo "<tr>";
                foreach ($row as $cell) {
                    echo "<td>" . htmlspecialchars($cell) . "</td>";
                }
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }
        ?>
    </div>

</body>
</html>