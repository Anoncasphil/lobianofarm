document.addEventListener("DOMContentLoaded", function () {
    let successMessage = localStorage.getItem("folderSuccessMessage");
    if (successMessage) {
        showMessage(successMessage);
        localStorage.removeItem("folderSuccessMessage"); // Clear message after displaying
    }
});

function showMessage(message) {
    $("#alert-message").text(message);
    $("#info-alert").removeClass("hidden").fadeIn();

    setTimeout(() => {
        $("#info-alert").fadeOut();
    }, 3000);
}

$(document).ready(function () {
    $("#addFolderForm").submit(function (event) {
        event.preventDefault();

        let name = $("#folderName").val().trim();
        let description = $("#folderDescription").val().trim();

        if (!name) {
            showMessage("Folder name cannot be empty.");
            return;
        }

        $.post("add_folder.php", { name, description }, function (response) {
            try {
                let res = typeof response === "object" ? response : JSON.parse(response);
                if (res.success) {
                    localStorage.setItem("folderSuccessMessage", res.message);
                    location.reload();
                } else {
                    showMessage("Error: " + res.message);
                }
            } catch (error) {
                console.error("JSON Parsing Error:", error, response);
                showMessage("Invalid response from server.");
            }
        }).fail(function (xhr) {
            console.error("AJAX Error:", xhr.responseText);
            showMessage("Failed to add folder.");
        });
    });
});

// Toggle folder modal visibility
function toggleAddFolderModal() {
    $("#addFolderModal").toggleClass("hidden");
}

function redirectToUpload(folderId) {
    // Store the folder ID in localStorage
    localStorage.setItem('selectedFolderId', folderId);

    // Redirect to the upload images page with the folder ID as a query parameter
    window.location.href = `upload_images.php?folder_id=${folderId}`;
}

// Archive folder logic
let selectedFolderId = null;

function openArchiveModal(event, folderId) {
    event.stopPropagation(); // Prevent unintended actions
    selectedFolderId = folderId;
    $("#deleteModal").removeClass("hidden");
}

function closeModal() {
    $("#deleteModal").addClass("hidden");
    selectedFolderId = null;
}

function archiveFolder() {
    if (!selectedFolderId) {
        showMessage("Error: No folder selected.");
        return;
    }

    $.post("archive_folder.php", { folder_id: selectedFolderId }, function (response) {
        if (response.success) {
            localStorage.setItem("folderSuccessMessage", response.message);
            location.reload();
        } else {
            showMessage("Error: " + response.message);
        }
    }).fail(function (xhr) {
        console.error("AJAX Error:", xhr.responseText);
        showMessage("Failed to archive folder.");
    });

    closeModal();
}

// Filter folders
function filterFolders(filter) {
    window.location.href = `?filter=${filter}`;
}
