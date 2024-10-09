<?php
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'default_font_size' => 16,
    'default_font' => 'sarabun'
]);

// รับค่าจากฟอร์ม
$name = $_POST['name'];
$position = $_POST['position'];
$department = $_POST['department'];
$date = $_POST['date'];
$dates = $_POST['dates'];

// Convert position to numeric value
$positionNumeric = floatval($department); // Ensure the value is numeric

// Function to convert numbers to Thai words
function convertNumberToThai($number) {
    $thaiNumbers = [
        0 => '',
        1 => 'หนึ่ง',
        2 => 'สอง',
        3 => 'สาม',
        4 => 'สี่',
        5 => 'ห้า',
        6 => 'หก',
        7 => 'เจ็ด',
        8 => 'แปด',
        9 => 'เก้า'
    ];

    $units = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];

    if ($number == 0) return 'ศูนย์บาทถ้วน';

    $numberStr = strval($number);
    $numberLength = strlen($numberStr);
    $thaiWord = '';

    for ($i = 0; $i < $numberLength; $i++) {
        $digit = $numberStr[$i];
        $position = $numberLength - $i - 1; // Get position from the end

        if ($digit != '0') {
            if ($position == 1 && $digit == '1') {
                $thaiWord .= 'สิบ'; // Special case for 10
            } elseif ($position == 1 && $digit == '2') {
                $thaiWord .= 'ยี่สิบ'; // Special case for 20
            } elseif ($position == 0 && $digit == '1' && $numberLength > 1) {
                $thaiWord .= 'เอ็ด'; // Special case for 21, 31, etc.
            } else {
                $thaiWord .= $thaiNumbers[$digit];
                $thaiWord .= $units[$position];
            }
        }
    }

    $thaiWord .= 'บาทถ้วน';

    return $thaiWord;
}

$positionThai = convertNumberToThai($positionNumeric); // Convert to Thai text

// แปลงวันที่ให้อยู่ในรูปแบบที่ต้องการและเพิ่มปี 543
try {
    $startDate = new DateTime($date);
    $startDates = new DateTime($dates);
} catch (Exception $e) {
    die('Invalid date format');
}

// รับวันเดือนปี จาก $date
$currentDays = $startDate->format("d");
$currentMonths = $startDate->format("m");
$currentYears = $startDate->format("Y") + 543;

// รับวันเดือนปี จาก $dates
$currentDayss = $startDates->format("d");
$currentMonthss = $startDates->format("m");
$currentYearss = $startDates->format("Y") + 543;

$thaiMonths = [
    1 => 'มกราคม',
    2 => 'กุมภาพันธ์',
    3 => 'มีนาคม',
    4 => 'เมษายน',
    5 => 'พฤษภาคม',
    6 => 'มิถุนายน',
    7 => 'กรกฎาคม',
    8 => 'สิงหาคม',
    9 => 'กันยายน',
    10 => 'ตุลาคม',
    11 => 'พฤศจิกายน',
    12 => 'ธันวาคม'
];

// Convert numeric month to Thai month name
$currentThaiMonths = $thaiMonths[intval($currentMonths)];
$currentThaiMonthss = $thaiMonths[intval($currentMonthss)];

// กำหนด HTML ที่จะใส่ลงใน PDF พร้อมกับ CSS
$html = '<style>
    .field {
        position: absolute;
    }
    .days-field {
        width: 50px; /* กำหนดความกว้าง */
        text-align: right; /* จัดชิดขวา */
    }
</style>';

$html .= '<div class="field" style="top:367px;left:510px;">' . htmlspecialchars($name) . '</div>';
$html .= '<div class="field" style="top:390px;left:120px;">' . htmlspecialchars($position) . '</div>';
$html .= '<div class="field" style="top:390px;left:290px;">' . htmlspecialchars($department) . '</div>';
$html .= '<div class="field" style="top:415px;left:568px;">' .
    htmlspecialchars($currentDays) . '&nbsp;&nbsp;' .
    htmlspecialchars($currentThaiMonths) . '&nbsp;&nbsp;' .
    htmlspecialchars($currentYears) . '</div>';
$html .= '<div class="field" style="top:440px;left:260px;">' .
    htmlspecialchars($currentDayss) . '&nbsp;&nbsp;' .
    htmlspecialchars($currentThaiMonthss) . '&nbsp;&nbsp;' .
    htmlspecialchars($currentYearss) . '</div>';

// Add the Thai text of the position amount
$html .= '<div class="field" style="top:390px;left:375px;">(' . htmlspecialchars($positionThai) . ')</div>';

// ตั้งค่า template
$mpdf->SetDocTemplate('form4.pdf', true);
$mpdf->WriteHTML($html);
$mpdf->Output();

?>
