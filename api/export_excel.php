<?php
// filepath: /c:/xampp_main/htdocs/lobianofarm/export_excel.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adlogin.php");
    exit;
}

require_once '../db_connection.php';

// Get the period parameter
$period = isset($_GET['period']) ? $_GET['period'] : 'yearly';

// First, install PhpSpreadsheet if you haven't already
// You'll need to run: composer require phpoffice/phpspreadsheet

// Require the Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// ==== COPY THE SAME FUNCTIONS FROM SALES.PHP ====
// Function to get yearly sales (previously "overall")
function getYearlySales($conn) {
    // Get sales data grouped by year starting from 2025
    $query = "SELECT 
              YEAR(check_out_date) AS year,
              SUM(total_price) AS total_sales,
              COUNT(*) AS reservation_count
              FROM reservations 
              WHERE status IN ('Completed', 'completed', 'COMPLETED')
              AND YEAR(check_out_date) >= 2025
              GROUP BY year
              ORDER BY year ASC";  
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sales_data = [];
    $total_sales = 0;
    
    while ($row = $result->fetch_assoc()) {
        $year_sales = (float)($row['total_sales'] ?? 0);
        $total_sales += $year_sales;
        
        $sales_data[] = [
            'period' => $row['year'],
            'value' => $row['year'],
            'sales' => $year_sales,
            'count' => $row['reservation_count']
        ];
    }
    
    return [
        'period_data' => $sales_data,
        'total_sales' => $total_sales
    ];
}

// Function to get monthly sales data (previously "yearly")
function getMonthlySales($conn) {
    $current_year = date('Y');
    
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
    
    $sales_data = [];
    $total_year_sales = 0;
    
    foreach ($months as $month_num => $month_name) {
        $start_date = sprintf('%04d-%02d-01', $current_year, $month_num);
        $end_date = date('Y-m-t', strtotime($start_date));
        
        $query = "SELECT SUM(total_price) as month_sales, COUNT(*) as reservation_count
                 FROM reservations 
                 WHERE status IN ('Completed', 'completed', 'COMPLETED')
                 AND check_out_date BETWEEN ? AND ?";
                 
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $month_sales = (float)($row['month_sales'] ?? 0);
        $reservation_count = $row['reservation_count'] ?? 0;
        $total_year_sales += $month_sales;
        
        $sales_data[] = [
            'period' => $month_name,
            'value' => $month_num,
            'sales' => $month_sales,
            'count' => $reservation_count
        ];
    }
    
    return [
        'period_data' => $sales_data,
        'total_sales' => $total_year_sales
    ];
}

// Get data based on selected period
switch($period) {
    case 'yearly':
        $sales_data = getYearlySales($conn);
        $title = "Yearly Sales Report";
        $period_label = "Year";
        break;
    case 'monthly':
        $sales_data = getMonthlySales($conn);
        $title = "Monthly Sales Report - " . date('Y');
        $period_label = "Month";
        break;
    default:
        $sales_data = getYearlySales($conn);
        $title = "Yearly Sales Report";
        $period_label = "Year";
        break;
}

// Create a new spreadsheet
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()
    ->setCreator("Lobiano Farm Resort")
    ->setLastModifiedBy("Lobiano Farm Admin")
    ->setTitle($title)
    ->setSubject("Sales Report")
    ->setDescription("Sales data export from Lobiano Farm Resort");

// Get active sheet
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Sales Report');

// Add header row
$sheet->setCellValue('A1', "Lobiano Farm Resort");
$sheet->setCellValue('A2', $title);
$sheet->setCellValue('A3', "Generated on: " . date('Y-m-d H:i:s'));

// Format headers
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A3')->getFont()->setItalic(true);

// Add a bit of space
$sheet->setCellValue('A5', $period_label);
$sheet->setCellValue('B5', "Reservations");
$sheet->setCellValue('C5', "Sales (₱)");

// Style the column headers
$sheet->getStyle('A5:C5')->getFont()->setBold(true);
$sheet->getStyle('A5:C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A5:C5')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A5:C5')->getFont()->getColor()->setARGB('FFFFFFFF');

// Add data rows
$row = 6;
foreach ($sales_data['period_data'] as $data) {
    $sheet->setCellValue('A' . $row, $data['period']);
    $sheet->setCellValue('B' . $row, $data['count']);
    $sheet->setCellValue('C' . $row, $data['sales']);
    
    // Format sales column as currency
    $sheet->getStyle('C' . $row)
        ->getNumberFormat()
        ->setFormatCode('_("₱"* #,##0.00_);_("₱"* \(#,##0.00\);_("₱"* "-"??_);_(@_)');
    
    $row++;
}

// Add total row
$total_row = $row;
$sheet->setCellValue('A' . $total_row, 'TOTAL');
$sheet->setCellValue('B' . $total_row, array_sum(array_column($sales_data['period_data'], 'count')));
$sheet->setCellValue('C' . $total_row, $sales_data['total_sales']);

// Format total row
$sheet->getStyle('A' . $total_row . ':C' . $total_row)->getFont()->setBold(true);
$sheet->getStyle('A' . $total_row . ':C' . $total_row)->getBorders()
    ->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
$sheet->getStyle('C' . $total_row)
    ->getNumberFormat()
    ->setFormatCode('_("₱"* #,##0.00_);_("₱"* \(#,##0.00\);_("₱"* "-"??_);_(@_)');

// Auto-size columns
foreach (range('A', 'C') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Create writer and output
$writer = new Xlsx($spreadsheet);

// Set headers for file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . str_replace(' ', '_', $title) . '_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');

// Save to output
$writer->save('php://output');
exit;