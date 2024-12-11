<?php
include("../db_connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch rates data
$sql_rates = "SELECT * FROM rates WHERE status='active'"; // Replace with your actual table name
$result_rates = $conn->query($sql_rates);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../styles/calendar.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
</head>
<body class="flex justify-center items-center">
    <a href="main_page_logged.html" class="absolute top-10 left-10 text-gray-700 hover:text-gray-900 text-lg">
        <i class="fa-solid fa-arrow-left"></i>
    </a>

    <div id="main_container" class="flex flex-row justify-self-center w-[95%] h-[95%]">
        <div id="input_container" class="flex flex-col h-full w-[70%]">
            <!-- Your other HTML content -->

            <div id="rates_amenities_container" class="w-full">
                <div id="scroll_container" class="w-full h-[110vh]">
                    <div id="rates_container" class="flex flex-col w-full h-[50%]">
                        <h1 class="text-4xl">Rates</h1>
                        <div id="scroll_rates_container" class="flex flex-row items-center w-full h-full overflow-y-auto gap-5">
                            <?php while($rate = $result_rates->fetch_assoc()) { 
                                // Check if the picture is a BLOB and encode it to Base64
                                $imageData = base64_encode($rate['picture']);
                            ?>
                                <div id="rate_card" class="flex flex-col justify-center items-center w-[35%] sm:w-[25%] h-[90%] shrink-0 shadow-lg rounded-2xl">
                                    <div id="rate_card_pic" class="flex items-center justify-center relative w-full h-[30%] sm:h-[60%] rounded-xl">
                                        <!-- Embed the Base64 image in the img tag -->
                                        <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" loading="lazy" class="sm:w-full h-full rounded-xl" alt="Rate Image">
                                    </div>
                                    <div id="rate_description" class="flex flex-col w-full h-[40%]">
                                        <h1 id="rates_name" class="text-xl font-bold"><?php echo $rate['name']; ?></h1>
                                        <p id="rates_hour" class="text-sm"><i class="fa-solid fa-clock text-gray-500"></i> <?php echo $rate['hoursofstay']; ?></p>
                                        <p id="rates_price" class="text-lg"><?php echo '₱' . number_format($rate['price'], 2); ?></p>
                                        <p id="rates_tag" class="text-xs text-gray-500">Includes taxes & fees</p>
                                    </div>
                                    <div class="flex w-full h-[30%] justify-center items-center">
                                        <button id="select_btn" class="w-[80%] h-[70%] bg-[#0092C0] rounded-lg">Select</button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $conn->close();
            ?>
        </div>

        <!-- Your other HTML content -->

    </div>
</body>
</html>
