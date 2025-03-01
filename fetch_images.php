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
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script>
        function toggleCategory(id) {
            // Hide all categories first
            const allCategories = document.querySelectorAll('.category-content');
            allCategories.forEach(function (category) {
                category.style.display = 'none';
            });

            if (id === 'all') {
                // If "All Categories" is clicked, show all categories
                allCategories.forEach(function (category) {
                    category.style.display = 'block';
                });
            } else {
                // Otherwise, show the selected category
                const selectedCategory = document.getElementById('category-' + id);
                if (selectedCategory) {
                    selectedCategory.style.display = 'block';
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">Categories</h1>

        <!-- Category Buttons -->
        <div class="flex items-center justify-center py-4 md:py-8 flex-wrap">
            <button type="button" class="text-blue-700 hover:text-white border border-blue-600 bg-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-full text-base font-medium px-5 py-2.5 text-center me-3 mb-3 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:bg-gray-900 dark:focus:ring-blue-800" onclick="toggleCategory('all')">All Categories</button>
            <?php while ($row = $result->fetch_assoc()): ?>
                <button type="button" class="text-gray-900 border border-white hover:border-gray-200 dark:border-gray-900 dark:bg-gray-900 dark:hover:border-gray-700 bg-white focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-full text-base font-medium px-5 py-2.5 text-center me-3 mb-3 dark:text-white dark:focus:ring-gray-800" onclick="toggleCategory(<?php echo $row['id']; ?>)"><?php echo htmlspecialchars($row['name']); ?></button>
            <?php endwhile; ?>
        </div>

        <!-- Categories content (Hidden initially) -->
        <div id="categories-content">
            <?php
            // Reset pointer to the first result for displaying content.
            mysqli_data_seek($result, 0);
            while ($row = $result->fetch_assoc()):
                // Clean up path and remove any relative "../" from the folder path
                $folderPath = rtrim($row['path'], '/'); // Remove trailing slash if exists
                // Ensure no "../" is in the folder path
                $folderPath = str_replace('../', '', $folderPath);

                // Fetch images for each category (folder)
                $folderId = $row['id']; // Folder ID
                $imageSql = "SELECT image_path FROM images WHERE folder_id = $folderId";
                $imageResult = $conn->query($imageSql);

                // Check if the category has images
                if ($imageResult->num_rows > 0):
                    ?>
                    <div id="category-<?php echo $row['id']; ?>" class="category-content" style="display: none;">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4"><?php echo htmlspecialchars($row['name']); ?> Gallery</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php
                            // Loop through images for this category
                            while ($imageRow = $imageResult->fetch_assoc()):
                                // Clean image path from any ../
                                $imagePath = str_replace('../', '', $imageRow['image_path']);
                                // Concatenate the folder path with the image path to get the full path
                                $imageUrl = $folderPath . '/' . basename($imagePath); // Append only the filename
                            ?>
                                <div class="rounded-lg shadow-lg overflow-hidden">
                                    <img class="w-full h-64 object-cover" src="/<?php echo $imageUrl; ?>" alt="Image <?php echo $imageRow['image_path']; ?>">
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php
                endif;
            endwhile;
            ?>
        </div>
    </div>

    <script>
        // Initialize Swiper after DOM is loaded
        document.addEventListener("DOMContentLoaded", function() {
            var swiper = new Swiper(".centered-slide-carousel", {
                centeredSlides: true,
                loop: true,
                spaceBetween: 30,  // Adds space between slides
                slidesPerView: 3,  // Display 3 slides (previous, center, next)
                slideToClickedSlide: true,
                autoplay: {
                    delay: 3000,  // Change slide every 3 seconds
                    disableOnInteraction: false,  // Continue autoplay after user interaction
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                breakpoints: {
                    1920: {
                        slidesPerView: 4,
                        spaceBetween: 30
                    },
                    1028: {
                        slidesPerView: 2,
                        spaceBetween: 10
                    },
                    990: {
                        slidesPerView: 1,
                        spaceBetween: 0
                    }
                }
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
