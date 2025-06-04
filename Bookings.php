<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   if (isset($_POST['submit'])) {
        // Get form data
           
            $room_type = $row["room_type"];
            $guest_name = $row["guest_name"];
            $email = $row["email"];
            $phone = $row["phone"];
            $check_in = $row["check_in"];
            $check_out = $row["check_out"];
            $guests = $row["guests"];
            $special_requests = $row["special_requests"];

        // Validate dates
        $checkin_date = new DateTime($check_in);
        $checkout_date = new DateTime($check_out);
        
        if ($checkout_date <= $checkin_date) {
            throw new Exception("Check-out date must be after check-in date.");
        }

        // Check if room is available for the selected dates
        $availability_sql = "SELECT COUNT(*) FROM bookings 
                           WHERE room_type = :room_type 
                           AND (
                               (check_in <= :check_in AND check_out > :check_in) OR
                               (check_in < :check_out AND check_out >= :check_out) OR
                               (check_in >= :check_in AND check_out <= :check_out)
                           )";
        
        $availability_stmt = $pdo->prepare($availability_sql);
        $availability_stmt->bindParam(':room_type', $room_type);
        $availability_stmt->bindParam(':check_in', $check_in);
        $availability_stmt->bindParam(':check_out', $check_out);
        $availability_stmt->execute();
        
        $existing_bookings = $availability_stmt->fetchColumn();
        
        if ($existing_bookings > 0) {
            throw new Exception("Room is not available for the selected dates. Please choose different dates.");
        }
        
    
        
    } 
}
?>

