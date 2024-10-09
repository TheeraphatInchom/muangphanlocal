<?php
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'default_font_size' => 16,
    'default_font' => 'sarabun'
]);

// รับค่าจากฟอร์ม
$date_vt = htmlspecialchars($_POST['date_vt']);
$name_vt = htmlspecialchars($_POST['name_vt']);
$dates_vt = htmlspecialchars($_POST['dates_vt']);
$datee_vt = htmlspecialchars($_POST['datee_vt']);
$position = htmlspecialchars($_POST['position']);
$affiliation = htmlspecialchars($_POST['affiliation']);
$contact = htmlspecialchars($_POST['contact']);
$assigned = htmlspecialchars($_POST['assigned']);

// ตรวจสอบค่าวันที่
// if (!$dates_vt || !$datee_vt) {
//     die('Invalid date input');
// }

// แปลงวันที่ให้อยู่ในรูปแบบที่ต้องการและเพิ่มปี 543
try {
    $currentDate = new DateTime($date_vt);
    $startDate = new DateTime($dates_vt);
    $endDate = new DateTime($datee_vt);
} catch (Exception $e) {
    die('Invalid date format');
}

//$dateFormatted_s = $startDate->format("d/m/") . ($startDate->format("Y") + 543);
//$dateFormatted_e = $endDate->format("d/m/") . ($endDate->format("Y") + 543);

// นับจำนวนวันระหว่างวันที่
$daysDiff = $endDate->diff($startDate)->days + 1;

// รับวันเดือนปี จาก $dateA_s
$currentDay = $currentDate->format("d");
$currentMonth = $currentDate->format("m");
$currentYear = $currentDate->format("Y") + 543;

// รับวันเดือนปี จาก $date_s
$currentDays = $startDate->format("d");
$currentMonths = $startDate->format("m");
$currentYears = $startDate->format("Y") + 543;

// รับวันเดือนปี จาก $date_e
$currentDaye = $endDate->format("d");
$currentMonthe = $endDate->format("m");
$currentYeare = $endDate->format("Y") + 543;
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
$currentThaiMonth = $thaiMonths[intval($currentMonth)];
$currentThaiMonths = $thaiMonths[intval($currentMonths)];
$currentThaiMonthe = $thaiMonths[intval($currentMonthe)];
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

$html .= '<div class="field" style="top:235px;left:220px;">' . $name_vt . '</div>';
$html .= '<div class="field" style="top:298px;left:250px;">' .
    $currentDays . '&nbsp;&nbsp;' .
    $currentThaiMonths  . '&nbsp;&nbsp;' .
    $currentYears . '</div>';
$html .= '<div class="field" style="top:125px;left:450px;">' .
    $currentDay . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
    $currentThaiMonth  . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
    $currentYear . '</div>';
$html .= '<div class="field" style="top:298px;left:480px;">' .
    $currentDaye . '&nbsp;&nbsp;' .
    $currentThaiMonthe  . '&nbsp;&nbsp;' .
    $currentYeare . '</div>';
$html .= '<div class="field days-field" style="top:298px;left:630px;">' . $daysDiff . '</div>';
$html .= '<div class="field" style="top:234px;left:428px;">' . $position . '</div>';
$html .= '<div class="field" style="top:234px;left:650px;">' . $affiliation . '</div>';
$html .= '<div class="field" style="top:330px;left:270px;">' . $contact . '</div>';
$html .= '<div class="field" style="top:395px;left:230px;">' . $assigned . '</div>';
$html .= '<div class="field" style="top:458px;left:150px;">' . $name_vt . '</div>';
$html .= '<div class="field" style="top:525px;left:150px;">' . $assigned . '</div>';
$html .= '<div class="field" style="top:610px;left:520px;">' . $name_vt . '</div>';
$html .= '<div class="field" style="top:640px;left:520px;">' . $name_vt . '</div>';

// ตั้งค่า template
$mpdf->SetDocTemplate('form1.pdf', true);
$mpdf->WriteHTML($html);
$mpdf->Output();
