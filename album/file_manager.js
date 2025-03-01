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
    localStorage.setItem("selectedFolderId", folderId);

    // Redirect to the upload images page with the folder ID as a query parameter
    window.location.href = `image_management.php?folder_id=${folderId}`;
}


function toggleModal(event, modalId, folderId = null) {


    console.log("Toggling modal:", modalId, "for folder:", folderId); // Debugging log

    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error("Modal not found:", modalId);
        return;
    }

    if (folderId !== null) {
        selectedFolderId = folderId;
    }

    modal.classList.toggle('hidden');
}


function restoreFolder(event) {
    if (!selectedFolderId) {
        alert("Error: No folder selected.");
        return;
    }

    $.post("restore_folder.php", { folder_id: selectedFolderId }, function (response) {
        try {
            let res = typeof response === "object" ? response : JSON.parse(response);
            if (res.success) {
                localStorage.setItem("alertMessage", res.message); // ✅ Store message
                location.reload();
            } else {
                alert("Error: " + res.message);
            }
        } catch (error) {
            console.error("JSON Parsing Error:", error, response);
            alert("Invalid response from server.");
        }
    }).fail(function (xhr) {
        console.error("AJAX Error:", xhr.responseText);
        alert("Failed to restore folder.");
    });

    toggleModal("restoreModal");
}

function deleteFolder(event) {
    if (!selectedFolderId) {
        alert("Error: No folder selected.");
        return;
    }

    $.post("delete_folder.php", { folder_id: selectedFolderId }, function (response) {
        try {
            let res = typeof response === "object" ? response : JSON.parse(response);
            if (res.success) {
                localStorage.setItem("alertMessage", res.message); // ✅ Store message
                location.reload();
            } else {
                alert("Error: " + res.message);
            }
        } catch (error) {
            console.error("JSON Parsing Error:", error, response);
            alert("Invalid response from server.");
        }
    }).fail(function (xhr) {
        console.error("AJAX Error:", xhr.responseText);
        alert("Failed to delete folder.");
    });

    toggleModal("deleteModal");
}

// ✅ Function to display alert on page load
document.addEventListener("DOMContentLoaded", function () {
    let alertMessage = localStorage.getItem("alertMessage");
    if (alertMessage) {
        document.getElementById("alert-message").textContent = alertMessage;
        document.getElementById("info-alert").classList.remove("hidden");
        localStorage.removeItem("alertMessage"); // Clear message after showing
    }
});


// Function to open the Edit Folder Modal
function openEditFolderModal(folderId, folderName, folderDescription) {
    // Populate modal inputs with existing folder data
    document.getElementById("editFolderId").value = folderId;
    document.getElementById("editFolderName").value = folderName;
    document.getElementById("editFolderDescription").value = folderDescription;

    // Show the modal
    document.getElementById("editFolderModal").classList.remove("hidden");
}

function updateFolder(event) {
    event.preventDefault(); // Prevent form submission

    let folderId = document.getElementById("editFolderId").value;
    let folderName = document.getElementById("editFolderName").value.trim();
    let folderDescription = document.getElementById("editFolderDescription").value.trim();

    if (!folderId || !folderName) {
        alert("Folder name is required.");
        return;
    }

    $.ajax({
        url: "update_folder.php",
        type: "POST",
        data: { id: folderId, name: folderName, description: folderDescription },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                localStorage.setItem("alertMessage", response.message); // ✅ Store message before reload
                location.reload();
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText);
            alert("AJAX Error: " + error);
        }
    });
}

// ✅ Function to display alert on page load
document.addEventListener("DOMContentLoaded", function () {
    let alertMessage = localStorage.getItem("alertMessage");
    if (alertMessage) {
        document.getElementById("alert-message").textContent = alertMessage;
        document.getElementById("info-alert").classList.remove("hidden");
        localStorage.removeItem("alertMessage"); // Clear message after showing
    }
});


// Function to close the Edit Folder Modal
function closeEditFolderModal() {
    document.getElementById("editFolderModal").classList.add("hidden");
}

function filterFolders(filterValue) {
    // Redirect to the same page with a different filter value
    window.location.href = `album.php?filter=${filterValue}`;
}
