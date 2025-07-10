document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.querySelector('.toggle-password');
    const password = document.getElementById('password');
    
    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }

    // Form validation
    const form = document.getElementById('loginForm');
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                // Here you would typically handle the form submission
                // For demo purposes, we'll just show an alert
                event.preventDefault();
                alert('Login form submitted!');
                // Uncomment the line below to actually submit the form
                // form.submit();
            }
            
            form.classList.add('was-validated');
        }, false);
    }

    // Add smooth hover effect to form inputs
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        // Add focus styles
        control.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        // Remove focus styles
        control.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });

    // Auto-hide carousel controls on small screens
    function handleCarouselControls() {
        const carousel = document.querySelector('.carousel');
        if (window.innerWidth <= 767.98) {
            carousel.setAttribute('data-bs-touch', 'true');
            carousel.setAttribute('data-bs-interval', '3000');
        } else {
            carousel.setAttribute('data-bs-touch', 'false');
            carousel.setAttribute('data-bs-interval', '5000');
        }
    }

    // Run on load and resize
    window.addEventListener('load', handleCarouselControls);
    window.addEventListener('resize', handleCarouselControls);

    // Add animation to form on load
    const loginForm = document.querySelector('.col-md-6.d-flex');
    if (loginForm) {
        loginForm.style.opacity = '0';
        loginForm.style.transform = 'translateY(20px)';
        loginForm.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
        
        // Trigger the animation after a short delay
        setTimeout(() => {
            loginForm.style.opacity = '1';
            loginForm.style.transform = 'translateY(0)';
        }, 300);
    }
});
