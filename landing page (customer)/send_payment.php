<?php
    include("../db_connection.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
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
</head>
<body class="flex justify-center items-center h-screen">

    <div id="main_container" class="flex flex-row justify-self-center w-[95%] h-[95%]">
        <div id="input_container" class="flex flex-col h-full w-[70%] items-center">
            <div id="customer_details_input" class="flex flex-col w-[80%] h-[20%] mt-[3%] mb-[2%]">
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
            
            <div id="payment_container" class="w-[80%] h-full">
                
                <div id="proof_payment_container" class="flex flex-row w-full h-[60%]">
                    <div id="attach_picture" class="flex flex-col justify-center w-[50%] h-full">
                        
                        <p class="mb-1">Attach the proof of payment*</p>

                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 ">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                                </div>
                                <input id="dropzone-file" type="file" class="hidden" />
                            </label>
                        </div> 

                    </div>

                    <div id="qr_code" class="flex flex-col text-left bottom-0 items-center justify-center w-[50%] h-full">
                        <p class="mb-1 flex mr-[]">Scan the attached QR*</p>
                        <img src="../src/images/qr_sample.png" class="w-[70%] h-64">
                    </div>
                    
                    
                </div>
            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../scripts/payment.js"></script>
    <script src="https://kit.fontawesome.com/26528a6def.js" crossorigin="anonymous"></script>

</body>
</html>
