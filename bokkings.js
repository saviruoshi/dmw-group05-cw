document.addEventListener('DOMContentLoaded', () => {
    // Mobile menu toggle
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    }

    // Form validation
    const bookingForm = document.getElementById('booking-form');
    const formError = document.getElementById('form-error');

    if (bookingForm && formError) {
        bookingForm.addEventListener('submit', (e) => {
            const roomType = document.getElementById('room_type').value;
            const guestName = document.getElementById('guest_name').value;
            const email = document.getElementById('email').value;
            const checkIn = document.getElementById('check_in').value;
            const checkOut = document.getElementById('check_out').value;
            const guests = document.getElementById('guests').value;

            if (!roomType || !guestName || !email || !checkIn || !checkOut || !guests) {
                e.preventDefault();
                formError.classList.remove('hidden');
            } else {
                formError.classList.add('hidden');
            }
        });
    }
});