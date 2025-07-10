// Admin Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Handle sidebar toggle for mobile
    const sidebarToggle = document.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    }

    // Handle image preview for file uploads
    const pictureInput = document.querySelector('input[name="picture"]');
    if (pictureInput) {
        pictureInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.addEventListener('load', function() {
                    const previewContainer = document.querySelector('#picture-preview');
                    if (previewContainer) {
                        if (previewContainer.querySelector('img')) {
                            previewContainer.querySelector('img').src = reader.result;
                        } else {
                            const img = document.createElement('img');
                            img.src = reader.result;
                            img.classList.add('img-thumbnail', 'mt-2', 'rounded');
                            img.style.maxHeight = '150px';
                            previewContainer.appendChild(img);
                        }
                        previewContainer.classList.remove('d-none');
                    }
                });
                reader.readAsDataURL(file);
            }
        });
    }

    // Automatic closing of alerts
    const alerts = document.querySelectorAll('.alert-dismissible');
    if (alerts.length > 0) {
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const closeButton = alert.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.click();
                }
            }, 5000); // Close after 5 seconds
        });
    }

    // Add confirm dialog to delete buttons
    const deleteButtons = document.querySelectorAll('.delete-confirm');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    }
});
