<?php
// Tên tệp dữ liệu bài thi
const QUIZ_FILE = 'Quiz.txt';

/**
 * Hàm đọc và phân tích cú pháp tệp Quiz.txt
 * Trả về một mảng chứa các đối tượng câu hỏi
 */
function parse_quiz_data() {
    $quiz_data = [];
    if (!file_exists(QUIZ_FILE)) {
        die("Lỗi: Không tìm thấy tệp " . QUIZ_FILE);
    }

    $content = file_get_contents(QUIZ_FILE);
    
    // Tách các khối câu hỏi dựa trên hai lần xuống dòng (\n\n)
    $question_blocks = preg_split('/\n\s*\n/', trim($content));
    
    foreach ($question_blocks as $index => $block) {
        $lines = array_filter(explode("\n", trim($block)), 'trim');
        if (empty($lines)) continue;

        $question_text = array_shift($lines);
        $options = [];
        $answer_str = '';

        foreach ($lines as $line) {
            if (preg_match('/^[A-Z]\./', $line)) {
                // Là một lựa chọn (A., B., C...)
                $options[] = $line;
            } elseif (str_starts_with(trim($line), 'ANSWER:')) {
                // Là đáp án
                $answer_str = trim(str_replace('ANSWER:', '', $line));
            }
        }
        
        // Chuẩn hóa đáp án thành mảng (dù là single hay multi-choice)
        $correct_answers = explode(',', $answer_str);
        $correct_answers = array_map('trim', $correct_answers);
        $correct_answers = array_filter($correct_answers); // Lọc bỏ khoảng trắng

        $is_multi_choice = count($correct_answers) > 1;

        $quiz_data[] = [
            'id' => $index,
            'question' => $question_text,
            'options' => $options,
            'correct_answers' => $correct_answers,
            'is_multi_choice' => $is_multi_choice,
        ];
    }

    return $quiz_data;
}

/**
 * Hàm chấm điểm và hiển thị kết quả
 * @param array $quiz_data - Dữ liệu bài thi đã được phân tích
 */
function score_and_display_results($quiz_data) {
    $total_questions = count($quiz_data);
    $correct_count = 0;
    
    // Lấy tất cả câu trả lời của người dùng từ POST request
    $user_answers = $_POST;

    echo '<div class="results">';
    echo '<h2>🎉 KẾT QUẢ CUỐI CÙNG 🎉</h2>';
    
    foreach ($quiz_data as $index => $item) {
        $question_id = 'q' . $index;
        
        // Lấy câu trả lời của người dùng (có thể là chuỗi hoặc mảng nếu là checkbox)
        $user_selection = isset($user_answers[$question_id]) ? $user_answers[$question_id] : [];
        if (!is_array($user_selection)) {
            $user_selection = [$user_selection]; // Đưa single-choice vào mảng để dễ so sánh
        }
        
        // Sắp xếp để so sánh chính xác (quan trọng cho multi-choice)
        sort($user_selection);
        sort($item['correct_answers']);

        // Kiểm tra tính chính xác
        $is_correct = ($user_selection === $item['correct_answers']);

        if ($is_correct) {
            $correct_count++;
        }
        
        // Hiển thị kết quả chi tiết
        $class = $is_correct ? 'correct' : 'incorrect';
        echo "<div class='question-card $class'>";
        
        // Hiển thị câu hỏi (và ghi chú Multi-choice)
        $multi_note = $item['is_multi_choice'] ? ' <span style="font-style: italic; color: #555;">(Chọn nhiều đáp án)</span>' : '';
        echo "<h4>Câu " . ($index + 1) . ": " . $item['question'] . $multi_note . "</h4>";
        
        // Hiển thị các lựa chọn và đáp án
        echo "<div class='options'>";
        foreach ($item['options'] as $option_text) {
            $option_value = $option_text[0]; // Lấy chữ cái A, B, C
            
            $label_class = '';
            // 1. Tô màu đáp án đúng
            if (in_array($option_value, $item['correct_answers'])) {
                $label_class .= ' correct-answer';
            }
            // 2. Tô màu đáp án sai đã chọn
            if (in_array($option_value, $user_selection) && !in_array($option_value, $item['correct_answers'])) {
                $label_class .= ' user-incorrect';
            }
            
            echo "<label class='{$label_class}'>" . $option_text . "</label>";
        }
        echo "</div>";
        
        // Tổng hợp kết quả
        echo "<p><strong>Đáp án đúng:</strong> " . implode(', ', $item['correct_answers']) . "</p>";
        if (!$is_correct) {
            echo "<p><strong>Bạn đã chọn:</strong> " . (empty($user_selection) ? 'Chưa chọn' : implode(', ', $user_selection)) . "</p>";
        }
        
        echo "</div>";
    }

    $percentage = ($total_questions > 0) ? round(($correct_count / $total_questions) * 100, 2) : 0;
    
    echo "<h3>Tổng điểm: $correct_count / $total_questions</h3>";
    echo "<p>Tỷ lệ đúng: $percentage%</p>";
    echo '</div>';
}

/**
 * Hàm hiển thị Form bài thi
 * @param array $quiz_data - Dữ liệu bài thi đã được phân tích
 */
function display_quiz_form($quiz_data) {
    echo '<form method="POST" action="quiz.php">';
    echo '<div id="quiz-body">';
    
    foreach ($quiz_data as $index => $item) {
        $question_id = 'q' . $index;
        $input_type = $item['is_multi_choice'] ? 'checkbox' : 'radio';
        $input_name = $item['is_multi_choice'] ? $question_id . '[]' : $question_id;

        echo '<div class="question-card">';
        
        // Hiển thị câu hỏi (và ghi chú Multi-choice)
        $multi_note = $item['is_multi_choice'] ? ' <span style="font-style: italic; color: #555;">(Chọn nhiều đáp án)</span>' : '';
        echo "<h4>Câu " . ($index + 1) . ": " . $item['question'] . $multi_note . "</h4>";

        echo '<div class="options">';
        foreach ($item['options'] as $option_text) {
            $option_value = $option_text[0]; // Lấy chữ cái A, B, C...
            
            echo "<label>";
            echo "<input type='$input_type' name='$input_name' value='$option_value'>";
            echo $option_text;
            echo "</label>";
        }
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '<button type="submit" id="submit-btn">Nộp bài & Xem kết quả</button>';
    echo '</form>';
}

// Bắt đầu quy trình chính
$quiz_data = parse_quiz_data();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài Thi Trắc Nghiệm Android (PHP)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="quiz-container">
        <h1>Bài Thi Trắc Nghiệm Android</h1>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nếu có POST request, tức là người dùng đã nộp bài
            score_and_display_results($quiz_data);
        } else {
            // Lần đầu tải trang, hiển thị form
            display_quiz_form($quiz_data);
        }
        ?>
    </div>

</body>
</html>