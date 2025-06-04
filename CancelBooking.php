<?php
    include 'db.php';
    
    $deleteID = $_GET['booking_id'];

    $sql = "DELETE FROM bookings  WHERE booking_id = $deleteID";
    $result = mysqli_query($conn,$sql);

    if($result == true)
    {
        echo "Record Deleted";
    }
?>