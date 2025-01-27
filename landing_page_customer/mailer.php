<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <script src='../scripts/mail.js'></script>
    <title>Reservation Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            width: 100%;
            background-color: #ffffff;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 15px;
        }

        p {
            text-align: center;
            color: #888;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 30px;
        }

        .details-grid {
            margin-top: 15px;
        }

        .details-grid label {
            font-weight: bold;
            color: #333;
            display: block;
        }

        .details-grid input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #333;
            font-size: 14px;
            cursor: not-allowed;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .total-section {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }

        .total-amount {
            color: #007bff;
            text-align: right;
        }

        .invoice-number {
            color: #e74c3c;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 40px;
            color: #888;
        }
    </style>
</head>
<body>

<div class='email-wrapper'>
    <div class='container'>
        <h2>Reservation Details</h2>
        <p>Text here</p>

        <!-- Customer Information Section -->
        <div class='section-title'>Customer Information</div>
        <div class='details-grid'>
            <label>First Name</label>
            <input type='text' id='first-name-p' disabled>
            
            <label>Last Name</label>
            <input type='text' id='last-name-p' disabled>
            
            <label>Email</label>
            <input type='email' id='email-p' disabled>
            
            <label>Mobile Number</label>
            <input type='text' id='mobile-number-p' disabled>
        </div>

        <!-- Reservation Dates Section -->
        <div class='section-title'>Reservation Dates</div>
        <div class='details-grid'>
            <label>Check-in Date</label>
            <input type='date' id='check-in-date' disabled>
            
            <label>Check-out Date</label>
            <input type='date' id='check-out-date' disabled>
            
            <label>Check-in Time</label>
            <input type='time' id='check-in-time' disabled>
            
            <label>Check-out Time</label>
            <input type='time' id='check-out-time' disabled>
        </div>

        <!-- Invoice Section -->
        <div class='section-title'>Invoice</div>
        <div class='details-grid'>
            <label>Invoice Date</label>
            <input type='text' id='invoice-date' disabled>
            
            <label>Invoice Number</label>
            <input type='text' id='invoice-no' class='invoice-number' disabled>
        </div>

        <!-- Items Table Section -->
        <div class='section-title'>Items</div>
        <table class='table'>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Item</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody id='invoice-items'>
                <!-- Dynamic rows will be added here by JS -->
            </tbody>
        </table>

        <!-- Total Amount Section -->
        <div class='total-section'>
            Total: <span id='total-price' class='total-amount'>₱0</span>
        </div>

        <!-- Footer Section -->
        <div class='footer'>
            <p>&copy; 2025 Your Company. All rights reserved.</p>
        </div>
    </div>
</div>

</body>
</html>
