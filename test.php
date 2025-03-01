<?php
include 'db_connection.php';

// Fetch categories from folders table
$sql = "SELECT id, name, path FROM folders WHERE archived = 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@flowbite/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.0/dist/flowbite.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the first category and display it by default
            const firstCategoryId = document.querySelector('.category-button').getAttribute('data-id');
            toggleCategory(firstCategoryId);
        });

        function toggleCategory(id) {
            // Hide all categories first
            const allCategories = document.querySelectorAll('.category-content');
            allCategories.forEach(function (category) {
                category.style.display = 'none';
            });

            // Show the selected category
            const selectedCategory = document.getElementById('category-' + id);
            if (selectedCategory) {
                selectedCategory.style.display = 'block';
            }
        }
    </script>
</head>
<body class="bg-gray-900 p-6">

<!-- Category Buttons -->
<div class="flex items-center justify-center py-4 md:py-8 flex-wrap mb-6">
    <?php 
    $firstCategoryId = null; // Store the first category ID to display it by default
    while ($row = $result->fetch_assoc()):
        if (!$firstCategoryId) {
            $firstCategoryId = $row['id']; // Set the first category ID
        }
    ?>
    <button type="button" class="category-button text-white border border-white bg-transparent hover:bg-white hover:text-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-full text-base font-medium px-5 py-2.5 text-center me-3 mb-3" data-id="<?php echo $row['id']; ?>" onclick="toggleCategory(<?php echo $row['id']; ?>)"><?php echo htmlspecialchars($row['name']); ?></button>
    <?php endwhile; ?>
</div>

<!-- Categories content (Hidden initially) -->
<div id="categories-content">
    <?php
    // Reset pointer to the first result for displaying content.
    mysqli_data_seek($result, 0);
    while ($row = $result->fetch_assoc()):
        // Remove the ../ from the path
        $path = str_replace('../', '', $row['path']);
    ?>
    <div id="category-<?php echo $row['id']; ?>" class="category-content" style="display: none;">
        <div class="relative">
            <!-- Full-screen carousel -->
            <div id="indicators-carousel-28-<?php echo $row['id']; ?>" class="relative w-full h-screen" data-carousel="static">
                <div class="relative h-full overflow-hidden rounded-lg">
                    <?php
                    $files = glob($path . "/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                    $firstItem = true;
                    foreach ($files as $index => $file):
                        if (file_exists($file)): ?>
                            <div class="duration-700 ease-in-out <?php echo $firstItem ? 'block' : 'hidden'; ?>" data-carousel-item <?php echo $firstItem ? 'data-carousel-item="active"' : ''; ?>>
                                <img src="<?php echo htmlspecialchars($file); ?>" class="absolute block w-full max-w-full h-auto object-cover top-0 left-0" alt="Image <?php echo $index + 1; ?>">
                            </div>
                            <?php $firstItem = false; ?>
                        <?php else: ?>
                            <p>File does not exist: <?php echo htmlspecialchars($file); ?></p>
                        <?php endif;
                    endforeach; ?>
                </div>

                <!-- Slider indicators -->
                <div class="absolute z-30 flex -translate-x-1/2 space-x-3 rtl:space-x-reverse bottom-5 left-1/2">
                    <?php foreach ($files as $index => $file): ?>
                        <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide <?php echo $index + 1; ?>" data-carousel-slide-to="<?php echo $index; ?>"></button>
                    <?php endforeach; ?>
                </div>

                <!-- Slider controls -->
                <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"></path>
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"></path>
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
            </div>

            <!-- Overlay category name (at the bottom of the image with white background) -->
            <div class="absolute bottom-0 left-0 w-full bg-white bg-opacity-80 text-gray-900 font-bold text-4xl p-4 z-10">
                <h2 class="text-4xl"><?php echo htmlspecialchars($row['name']); ?></h2>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
