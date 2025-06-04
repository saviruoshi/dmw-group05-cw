<?php
    include 'db.php';

    // Prepare and execute the query
    $sql = "SELECT * FROM bookings ORDER BY booking_id DESC";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adams Peak Bookings Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .booking-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .booking-table th,
        .booking-table td {
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .booking-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
        }
        
        .booking-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .booking-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .action-links {
            white-space: nowrap;
        }
        
        .action-links a {
            color: #007bff;
            text-decoration: none;
            padding: 4px 8px;
            margin: 0 2px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .edit-link {
            background-color: #007bff;
            color: white !important;
        }
        
        .delete-link {
            background-color: #dc3545;
            color: white !important;
        }
        
        .action-links a:hover {
            opacity: 0.8;
        }
        
        .no-bookings {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        
        .booking-count {
            color: #666;
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .booking-table {
                font-size: 12px;
            }
            
            .booking-table th,
            .booking-table td {
                padding: 8px 4px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hotel Bookings Management</h1>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="booking-count">
                Total Bookings: <?php echo mysqli_num_rows($result); ?>
            </div>
            
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Room Type</th>
                        <th>Guest Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Guests</th>
                        <th>Special Requests</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['check_in']); ?></td>
                            <td><?php echo htmlspecialchars($row['check_out']); ?></td>
                            <td><?php echo htmlspecialchars($row['guests']); ?></td>
                            <td><?php echo htmlspecialchars($row['special_requests'] ?: 'None'); ?></td>
                            <td class="action-links">
                                <a href="edit_booking.php?booking_id=<?php echo urlencode($row['booking_id']); ?>" 
                                   class="edit-link"
                                   onclick="return confirm('Are you sure you want to edit this booking?');">
                                   Edit
                                </a>
                                <a href="CancelBooking.php?booking_id=<?php echo urlencode($row['booking_id']); ?>" 
                                   class="delete-link"
                                   onclick="return confirm('Are you sure you want to delete this booking? This action cannot be undone.');">
                                   Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
        <?php else: ?>
            <div class="no-bookings">
                <h3>No bookings found</h3>
                <p>There are currently no bookings in the system.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
    // Close the database connection
    mysqli_close($conn);
?>