<?php
    include 'db.php';
   
    $booking_data = null;
    $message = '';
    
    // Get booking data if ID is provided
    if (isset($_GET['booking_id'])) {
        $id = (int)$_GET['booking_id'];
        $sql = "SELECT * FROM bookings WHERE booking_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $booking_data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        if (!$booking_data) {
            $message = "Booking not found!";
        }
    }

    // Handle form submission
    if (isset($_POST['update_booking'])) {
        $booking_id = (int)$_POST['booking_id'];
        $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);
        $guest_name = mysqli_real_escape_string($conn, $_POST['guest_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $check_in = mysqli_real_escape_string($conn, $_POST['check_in']);
        $check_out = mysqli_real_escape_string($conn, $_POST['check_out']);
        $guests = (int)$_POST['guests'];
        $special_requests = mysqli_real_escape_string($conn, $_POST['special_requests']);

        $sql = "UPDATE bookings SET 
                room_type = ?, 
                guest_name = ?, 
                email = ?, 
                phone = ?, 
                check_in = ?, 
                check_out = ?, 
                guests = ?, 
                special_requests = ? 
                WHERE booking_id = ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssiis", $room_type, $guest_name, $email, $phone, 
                             $check_in, $check_out, $guests, $special_requests, $booking_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $message = "Booking updated successfully!";
            // Refresh booking data
            $result = mysqli_query($conn, "SELECT * FROM bookings WHERE booking_id = $booking_id");
            $booking_data = mysqli_fetch_assoc($result);
        } else {
            $message = "Error updating booking: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking - Hotel Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .form-container {
            padding: 40px;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1em;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-home {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-home:hover {
            background: linear-gradient(135deg, #218838 0%, #1ba085 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }

        .booking-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }

        .booking-info h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: 600;
            color: #666;
        }

        .info-value {
            color: #2c3e50;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .container {
                margin: 10px;
            }
            
            .header {
                padding: 20px;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit Booking</h1>
            <p>Update your reservation details</p>
        </div>

        <div class="form-container">
            <?php if ($message): ?>
                <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($booking_data): ?>
                <div class="booking-info">
                    <h3>Current Booking Information</h3>
                    <div class="info-item">
                        <span class="info-label">Booking ID:</span>
                        <span class="info-value">#<?php echo $booking_data['booking_id']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Current Guest:</span>
                        <span class="info-value"><?php echo htmlspecialchars($booking_data['guest_name']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Current Room:</span>
                        <span class="info-value"><?php echo htmlspecialchars($booking_data['room_type']); ?></span>
                    </div>
                </div>

                <form method="POST" action="">
                    <input type="hidden" name="booking_id" value="<?php echo $booking_data['booking_id']; ?>">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="guest_name">Guest Name *</label>
                            <input type="text" id="guest_name" name="guest_name" 
                                   value="<?php echo htmlspecialchars($booking_data['guest_name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($booking_data['email']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($booking_data['phone']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="room_type">Room Type *</label>
                            <select id="room_type" name="room_type" required>
                                <option value="Standard Room" <?php echo ($booking_data['room_type'] == 'Standard Room') ? 'selected' : ''; ?>>Standard Room</option>
                                <option value="Deluxe Room" <?php echo ($booking_data['room_type'] == 'Deluxe Room') ? 'selected' : ''; ?>>Deluxe Room</option>
                                <option value="Family" <?php echo ($booking_data['room_type'] == 'Family') ? 'selected' : ''; ?>>Family Room</option>
                                
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="check_in">Check-in Date & Time *</label>
                            <input type="datetime-local" id="check_in" name="check_in" 
                                   value="<?php echo date('Y-m-d\TH:i', strtotime($booking_data['check_in'])); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="check_out">Check-out Date & Time *</label>
                            <input type="datetime-local" id="check_out" name="check_out" 
                                   value="<?php echo date('Y-m-d\TH:i', strtotime($booking_data['check_out'])); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="guests">Number of Guests *</label>
                            <input type="number" id="guests" name="guests" min="1" max="10" 
                                   value="<?php echo $booking_data['guests']; ?>" required>
                        </div>

                        <div class="form-group full-width">
                            <label for="special_requests">Special Requests</label>
                            <textarea id="special_requests" name="special_requests" 
                                      placeholder="Any special requirements or requests..."><?php echo htmlspecialchars($booking_data['special_requests']); ?></textarea>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" name="update_booking" class="btn btn-primary">
                            Update Booking
                        </button>
                        <a href="ViewBooking.php" class="btn btn-secondary">
                            Back to Bookings
                        </a>
                        <a href="Main.html" class="btn btn-home">
                            Main Page
                        </a>
                    </div>
                </form>

            <?php else: ?>
                <div class="message error">
                    <?php echo $message ?: "No booking ID provided. Please select a booking to edit."; ?>
                </div>
                <div class="button-group">
                    <a href="ViewBooking.php" class="btn btn-secondary">
                        Back to Bookings
                    </a>
                    <a href="Main.html" class="btn btn-home">
                        Main Page
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const checkIn = document.getElementById('check_in');
            const checkOut = document.getElementById('check_out');

            if (form) {
                form.addEventListener('submit', function(e) {
                    const checkInDate = new Date(checkIn.value);
                    const checkOutDate = new Date(checkOut.value);
                    const now = new Date();

                    if (checkInDate >= checkOutDate) {
                        e.preventDefault();
                        alert('Check-out date must be after check-in date.');
                        return false;
                    }

                    if (confirm('Are you sure you want to update this booking?')) {
                        return true;
                    } else {
                        e.preventDefault();
                        return false;
                    }
                });
            }

            // Real-time date validation
            checkOut.addEventListener('change', function() {
                const checkInDate = new Date(checkIn.value);
                const checkOutDate = new Date(this.value);

                if (checkInDate >= checkOutDate) {
                    this.setCustomValidity('Check-out must be after check-in date');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    </script>
</body>
</html>