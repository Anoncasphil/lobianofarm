<?php

    include("../db_connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql_rates = "SELECT * FROM rates WHERE status='active'";
    $result_rates = $conn->query($sql_rates);
    
    $sql_amenities = "SELECT * FROM addons WHERE status='active'";
    $result_amenities = $conn->query($sql_amenities);
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
            <div id="customer_details_input" class="flex flex-col w-[80%] h-[40%] mt-[10%]">
                <!-- Name Inputs -->
                <div id="name_input" class="flex justify-between w-full gap-5">
                    <div class="flex flex-col w-[48%]">
                        <label class="mb-1 text-sm font-medium">First Name</label>
                        <input type="text" class="w-full h-10 rounded-md border border-gray-300 px-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="flex flex-col w-[48%]">
                        <label class="mb-1 text-sm font-medium">Last Name</label>
                        <input type="text" class="w-full h-10 rounded-md border border-gray-300 px-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
            
                <!-- Electronic Inputs -->
                <div id="electronic_input" class="flex justify-between w-full h-[50%] gap-5 mt-3">
                    <div class="flex flex-col w-[48%]">
                        <label class="mb-1 text-sm font-medium">Email</label>
                        <input type="email" class="w-full h-10 rounded-md border border-gray-300 px-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="flex flex-col w-[48%]">
                        <label class="mb-1 text-sm font-medium">Mobile Number</label>
                        <input type="tel" class="w-full h-10 rounded-md border border-gray-300 px-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
            </div>
            
            <div id="rates_amenities_container" class="w-full">
                <div id="scroll_container" class="w-full h-[120vh]">
                    <div id="rates_container" class="flex flex-col w-full h-[50%]">
                        <h1 class="text-4xl">Rates</h1>
                        <div id="scroll_rates_container" class="flex flex-row items-center w-full h-full overflow-y-auto gap-5">
                        <?php while($rate = $result_rates->fetch_assoc()) { 
                            // Fetch and encode image data as base64
                            $imageData = base64_encode($rate['picture']);
                        ?>
                            <div id="rate_card" class="flex flex-col justify-center items-center w-[35%] sm:w-[25%] h-[90%] shrink-0 shadow-lg rounded-2xl">
                                <div id="rate_card_pic" class="flex items-center justify-center relative w-full h-[60%] rounded-xl">
                                    <!-- Embed the Base64 image directly in the src -->
                                    <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" loading="lazy" class="w-full h-[160px] rounded-xl" alt="Rate Image">
                                </div>
                                <div id="rate_description" class="flex flex-col w-full h-[40%]">
                                    <h1 id="rates_name" class="text-xl font-bold"><?php echo $rate['name']; ?></h1>
                                    <p id="rates_hour" class="text-sm"><i class="fa-solid fa-clock text-gray-500"></i> <?php echo $rate['hoursofstay']; ?> hours</p>
                                    <p id="rates_price" class="text-lg"><?php echo '₱' . number_format($rate['price'], 2); ?></p>
                                    <p id="rates_tag" class="text-xs text-gray-500">Includes taxes & fees</p>
                                </div>
                                <div class="flex w-full h-[30%] justify-center items-center">
                                <button id="select_btn" class="w-[200px] h-[40px] bg-[#0092C0] rounded-lg mt-3" data-name="<?php echo $rate['name']; ?>" data-price="<?php echo $rate['price']; ?>">
                                    Select
                                </button>
                                </div>
                            </div>
                        <?php } ?>

                        </div>
                    </div>

                    <div id="amenities_container" class="flex flex-col w-full h-[50%] mt-[3%]">
                        <h1 class="text-4xl">Amenities</h1>
                        <div id="scroll_amenities_container" class="flex flex-row items-center w-full h-full overflow-y-auto gap-5">
                        <?php while($amenity = $result_amenities->fetch_assoc()) { 
                            // Fetch and encode image data as base64
                            $imageData = base64_encode($amenity['picture']); // Update column name here
                            ?>
                            <div id="amenity_card" class="flex flex-col justify-center items-center w-[35%] sm:w-[25%] h-[90%] shrink-0 shadow-lg rounded-2xl">
                                <div id="amenity_card_pic" class="flex items-center justify-center relative w-full h-[60%] rounded-xl">
                                    <!-- Embed the Base64 image directly in the src -->
                                    <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" loading="lazy" class="w-full h-[160px] rounded-xl" alt="Amenity Image">
                                </div>
                                <div id="amenity_description" class="flex flex-col w-full h-[40%]">
                                    <h1 id="amenity_name" class="text-xl font-bold"><?php echo $amenity['name']; ?></h1>
                                    <p id="amenity_price" class="text-lg"><?php echo '₱' . number_format($amenity['price'], 2); ?></p>
                                    <p id="amenity_tag" class="text-xs text-gray-500">Includes taxes & fees</p>
                                </div>
                                <div class="flex w-full h-[30%] justify-center items-center">
                                <button id="select_btn" class="w-[200px] h-[40px] bg-[#0092C0] rounded-lg mt-3" data-name="<?php echo $amenity['name']; ?>" data-price="<?php echo $amenity['price']; ?>">Select</button>
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

        <div id="calendar_side" class="flex flex-col w-[30%] h-full">
    <div id="calendar_container" class="flex justify-center w-full mt-[3%]">
        <div id="calendar"></div>
    </div>
    <div id="invoice_container" class="flex flex-col justify-self-center self-center w-[80%] mt-[3%] rounded-lg border-2 p-2">
        <table id="invoice" class="w-[90%] px-10 mx-5">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <!-- Items will be dynamically added here -->
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td id="totalPrice">₱0.00</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <button id="select_btn" class="mx-10 w-[83%] h-11 rounded-xl bg-[#37863B] mt-[5%]">Book <i class="fa-sharp fa-solid fa-arrow-right ml-1"></i></button>
</div>
    </div>
    <div class="h-[20%] flex bg-red-400"></div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../scripts/payment.js"></script>
    <script src="https://kit.fontawesome.com/26528a6def.js" crossorigin="anonymous"></script>
</body>
</html>
