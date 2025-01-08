<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user's reviews
$reviews_query = $conn->prepare("SELECT * FROM reviews WHERE user_id = ? ORDER BY created_at DESC");
$reviews_query->bind_param("i", $_SESSION['user_id']);
$reviews_query->execute();
$reviews = $reviews_query->get_result();

// Fetch user's reservations
$reservations_query = $conn->prepare("SELECT * FROM reservation WHERE user_id = ? ORDER BY reservation_check_in_date DESC");
$reservations_query->bind_param("i", $_SESSION['user_id']);
$reservations_query->execute();
$reservations = $reservations_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body class="bg-gray-100">
    <div class="max-w-6xl mx-auto p-8">
        <div class="flex items-center justify-between mb-8">
            <a href="main_page_logged.php" class="flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <h1 class="text-3xl font-bold">My History</h1>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-4 border-b">
            <ul class="flex flex-wrap -mb-px" id="tabs" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="reviews-tab" data-tabs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">My Reviews</button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg" id="reservations-tab" data-tabs-target="#reservations" type="button" role="tab" aria-controls="reservations" aria-selected="false">Reservation History</button>
                </li>
            </ul>
        </div>

        <!-- Reviews Tab -->
        <div id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
            <div class="flex flex-col w-full gap-4">
                <?php while($review = $reviews->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 w-full">
                        <h3 class="font-bold text-lg mb-2 break-words"><?php echo htmlspecialchars($review['title']); ?></h3>
                        <p class="text-gray-600 mb-4 break-words whitespace-pre-wrap overflow-hidden">
                            <?php echo htmlspecialchars($review['review_text']); ?>
                        </p>
                        <div class="flex items-center justify-between border-t pt-4 mt-2">
                            <span class="text-yellow-400 flex items-center gap-1">
                                <?php echo $review['rating']; ?> ★
                            </span>
                            <span class="text-gray-400 text-sm">
                                <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                            </span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Reservations Tab -->
        <div id="reservations" class="hidden" role="tabpanel" aria-labelledby="reservations-tab">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Check In</th>
                            <th scope="col" class="px-6 py-3">Check Out</th>
                            <th scope="col" class="px-6 py-3">Total Amount</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php while($reservation = $reservations->fetch_assoc()): ?>
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4"><?php echo date('M d, Y', strtotime($reservation['reservation_check_in_date'])); ?></td>
                                <td class="px-6 py-4"><?php echo date('M d, Y', strtotime($reservation['reservation_check_out_date'])); ?></td>
                                <td class="px-6 py-4">₱<?php echo number_format($reservation['total_amount'], 2); ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs
                                        <?php echo $reservation['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo ucfirst($reservation['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Tab switching logic
        const tabButtons = document.querySelectorAll('[role="tab"]');
        const tabPanels = document.querySelectorAll('[role="tabpanel"]');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Reset all tabs
                tabButtons.forEach(btn => {
                    btn.classList.remove('border-blue-600', 'text-blue-600');
                    btn.classList.add('border-transparent');
                });
                tabPanels.forEach(panel => panel.classList.add('hidden'));

                // Activate clicked tab
                button.classList.remove('border-transparent');
                button.classList.add('border-blue-600', 'text-blue-600');
                document.querySelector(button.dataset.tabsTarget).classList.remove('hidden');
            });
        });

        // Activate first tab by default
        tabButtons[0].click();
    </script>
</body>
</html>