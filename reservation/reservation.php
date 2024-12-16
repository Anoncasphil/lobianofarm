<?php
    session_start();
    include("../db_connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql_rates = "SELECT * FROM rates WHERE status='active'";
    $result_rates = $conn->query($sql_rates);
    
    $sql_addons = "SELECT * FROM addons WHERE status='active'";
    $result_addons = $conn->query($sql_addons);

    if (isset($_POST['Book_me_now_pls'])) {
        // Sanitize and assign form input
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $mobile_number = $_POST['mobile_number'];
        $total_amount = $_POST['total_amount'];
        $reservation_check_in_date = $_POST['reservation_check_in_date'];
        $reservation_check_out_date = $_POST['reservation_check_out_date'];
        $rate_ids = explode(',', $_POST['rate_id']); // Split multiple rate IDs
        $addons_id = explode(',', $_POST['addons_id']); // Split multiple addon ID

        // Store the input data in session variables
        $_SESSION['rate_id'] = $rate_id;
        $_SESSION['addons_id'] = $addons_id;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['email'] = $email;
        $_SESSION['mobile_number'] = $mobile_number;
        $_SESSION['total_amount'] = $total_amount;
        $_SESSION['reservation_check_in_date'] = $reservation_check_in_date;
        $_SESSION['reservation_check_out_date'] = $reservation_check_out_date;

    
        if (!is_numeric($mobile_number)) {
            echo "<script>alert('Invalid mobile number. Please enter numbers only.');</script>";
            exit();
        }
    
        foreach ($rate_ids as $rate_id) {
            // Loop through each selected addon
            foreach ($addons_id as $addon_id) {
                // Insert into the reservation table for each combination of rate and addon
                $stmt = $conn->prepare("INSERT INTO reservation (first_name, last_name, email, mobile_number, total_amount, reservation_check_in_date, reservation_check_out_date, rate_id, addons_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }

                // Bind the parameters and execute the statement
                $stmt->bind_param(
                    "sssssssii", 
                    $first_name,
                    $last_name,
                    $email,
                    $mobile_number,
                    $total_amount,
                    $reservation_check_in_date,
                    $reservation_check_out_date,
                    $rate_id,
                    $addon_id
                );

                if (!$stmt->execute()) {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            }
        }

        $conn->close();
        echo "<script>alert('Reservation successfully created!');</script>";
        header("Location: ../landing_page_customer/send_payment.php");
        exit();
    }
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../src/calendar.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../src/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
</head>
<body class="flex justify-center items-center">
    <a href="main_page_logged.html" class="absolute top-10 left-10 text-gray-700 hover:text-gray-900 text-lg">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      


<div id="form_wrapper" class="w-[95%] h-[95%] flex justify-center">
    <form action="" method="post" class="flex flex-col w-full h-full">
    <div id="main_container" class="flex flex-row justify-self-center w-[95%] h-[95%]">
        <div id="input_container" class="flex flex-col h-full w-[70%]">
            <!-- Customer Details Start -->
            <div id="customer_details_input" class="flex flex-col w-[80%] h-[40%] mt-[10%]">
                <!-- Name Input Start -->
                <div id="name_input" class="flex justify-between w-full gap-5">
                    <div class="flex flex-col w-[48%]">
                        <label class="mb-1 text-sm font-medium">First Name</label>
                        <input type="text" name="first_name" class="w-full h-10 rounded-md border border-gray-300 px-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>
                    <div class="flex flex-col w-[48%]">
                        <label class="mb-1 text-sm font-medium">Last Name</label>
                        <input type="text" name="last_name" class="w-full h-10 rounded-md border border-gray-300 px-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>
                </div>
                <!-- Name Input End -->
            
                <!-- Electronic Inputs Start -->
                <div id="electronic_input" class="flex justify-between w-full h-[50%] gap-5 mt-3">
                    <div class="flex flex-col w-[48%]">
                        <label class="mb-1 text-sm font-medium">Email</label>
                        <input type="email" name="email"class="w-full h-10 rounded-md border border-gray-300 px-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>
                    <div class="flex flex-col w-[48%]">
                        <label class="mb-1 text-sm font-medium">Mobile Number</label>
                        <input type="tel" name="mobile_number"class="w-full h-10 rounded-md border border-gray-300 px-2 focus:outline-none focus:ring-2 focus:ring-blue-400" maxlength="11" required>
                    </div>
                </div>
                <!-- Electronic Inputs End -->
            </div>
            <!-- Customer Details End -->
       

            <!-- Rates and Amenities Container -->
            <div id="rates_addons_container" class="w-full">
                <div id="scroll_rates_container" class="w-full h-[110vh]">

                    <!-- Rates Container Start -->
                    <div id="rates_container" class="flex flex-col w-full h-[50%]">
                        <h1 class="text-4xl">Rates</h1>

                        <!-- Rates Scrollable Container Start -->
                        <div id="scroll_rates_container" class="flex flex-row items-center w-full h-full overflow-y-auto gap-5">
                        <?php while($rate = $result_rates->fetch_assoc()) { 
                           // Fetch and encode image data as base64
                            $imageData = base64_encode($rate['picture']);
                        ?>
                            <!-- RATES CARD START -->
                            <div id="rates_card" class="flex flex-col justify-center items-center w-[35%] sm:w-[25%] h-[90%] shrink-0 shadow-lg rounded-2xl">
                                <div id="rates_card_pic" class="flex items-center justify-center relative w-full h-[30%] sm:h-[60%] rounded-xl">
                                <!-- Embed the Base64 image directly in the src -->
                                <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" loading="lazy" class="w-full h-[160px] rounded-xl" alt="Rate Image">
                                </div>

                                <!-- RATES Description(name, price, hours, tag) -->
                                <div id="rates_description" class="flex flex-col w-full h-[40%]">
                                    <h1 id="rates_name" class="text-xl font-bold"><?php echo $rate['name']; ?></h1>
                                    <p id="rates_hour" class="text-sm"><i class="fa-solid fa-clock text-gray-500"></i> <?php echo $rate['hoursofstay']; ?> hours</p>
                                    <p id="rates_price" class="text-lg"><?php echo '₱' . number_format($rate['price'], 2); ?></p>
                                    <p id="rates_tag" class="text-xs text-gray-500">Includes taxes & fees</p>
                                </div>

                                <div class="flex w-full h-[30%] justify-center items-center">
                                    <button type="button" id="rate_btn" class="w-[80%] h-[70%] bg-[#0092C0] rounded-lg rate-btn" data-rate-id="<?php echo $rate['id']; ?>">
                                        Select Rate
                                    </button>
                                </div>
                                
                            </div>
                             <!-- RATES CARD END -->
                        <?php } ?>

                        </div>
                        <!-- Rates Scrollable Container Start -->

                    </div>
                    <!-- Rates Container End -->
                    

                    <!-- Amenities Container -->
                    <div id="addons_container" class="flex flex-col w-full h-[50%] mt-[3%]">
                        <h1 class="text-4xl">Amenities</h1>
                        <div id="scroll_addons_container" class="flex flex-row items-center w-full h-full overflow-y-auto gap-5">
                        <?php while($addons = $result_addons->fetch_assoc()) { 
                            // Fetch and encode image data as base64
                            $imageData = base64_encode($addons['picture']); // Update column name here
                            ?>
                        <!-- AMENITIES CARD START -->
                            <div id="addons_card" class="flex flex-col justify-center items-center w-[35%] sm:w-[25%] h-[90%] shrink-0 shadow-lg rounded-2xl">
                                <div id="addons_card_pic" class="flex items-center justify-center relative w-full h-[30%] sm:h-[60%] rounded-xl">
                                <!-- Embed the Base64 image directly in the src -->
                                <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" loading="lazy" class="w-full h-[160px] rounded-xl" alt="Amenity Image">
                                </div>
                                <!-- Amenities Description(name, price, hours, tag) -->
                                <div id="addons_description" class="flex flex-col w-full h-[40%]">
                                    <h1 id="addons_name" class="text-xl font-bold"><?php echo $addons['name']; ?></h1>
                                    <p id="addons_price" class="text-lg"><?php echo '₱' . number_format($addons['price'], 2); ?></p>
                                    <p id="addons_tag" class="text-xs text-gray-500">Includes taxes & fees</p>
                                </div>
                                <div class="flex w-full h-[30%] justify-center items-center">
                                    <button type="button" id="addons_btn" class="w-[80%] h-[70%] bg-[#0092C0] rounded-lg" data-addons-id="<?php echo $addons['id']; ?>">
                                        Select Addons
                                    </button>
                                </div>
                            </div>
                        <?php } ?>

                        </div>
                    </div>
                    <!-- AMENITIES CARD END -->


                </div>
            </div>
            <?php
            $conn->close();
            ?>
        </div>

        <!-- Right side -->
        <!-- Calendar Side -->
        <div id="calendar_side" class="flex flex-col w-[30%] h-full">
            <div id="calendar_container" class="flex justify-center w-full mt-[3%]">
                    <div id="calendar"></div>
            </div>
            



            <!-- INVOICE CONTAINER -->
            <div id="invoice_container" class="flex flex-col justify-self-center self-center w-[80%] mt-[3%] rounded-lg border-2 p-2">
                <table id="invoice" class="w-[90%] px-10 mx-5">
                    <thead>
                        <tr>
                            <th class="text-left">Item</th>
                            <th class="text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Items will be dynamically added here -->
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2">
                            <td class="text-left">Total</td>
                            <td class="text-right" id="totalPrice">₱0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- INVOICE END -->
            <input type="hidden" name="reservation_check_in_date" id="reservation_check_in_date">
            <input type="hidden" name="reservation_check_out_date" id="reservation_check_out_date">
            <input type="hidden" name="total_amount" id="total_amount">
            <input type="hidden" name="rate_id" id="rate_id">
            <input type="hidden" name="addons_id" id="addons_id">


            
            <button type="submit" id="Booking" name="Book_me_now_pls" class="mx-10 w-[83%] h-11 rounded-xl bg-[#37863B] mt-[5%]">Book <i class="fa-sharp fa-solid fa-arrow-right ml-1"></i></button>
        </div>
        <!-- Calendar Side End -->

    </div>
    </form>
</div>

    
    <div class="h-[20%] flex bg-red-400"></div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../scripts/payment.js"></script>
    <script src="https://kit.fontawesome.com/26528a6def.js" crossorigin="anonymous"></script>

</body>
</html>