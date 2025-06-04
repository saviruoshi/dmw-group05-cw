<?php
    include 'db.php';

    echo 'Connected';

    $room_type = $_POST["room_type"];
    $guest_name = $_POST["guest_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $check_in = $_POST["check_in"];
    $check_out = $_POST["check_out"];
    $guests = $_POST["guests"];
    $special_requests = $_POST["special_requests"];

    $sql = "INSERT INTO bookings (room_type,guest_name,email,phone,check_in,check_out,guests,special_requests) VALUES ('$room_type',' $guest_name','$email','$phone','$check_in','$check_out','$guests','$special_requests')";

    if(mysqli_query($conn,$sql))
    {
        echo "New record created successfully";
    }
    else
    {
        echo "Error: ".$sql."<br>".mysqli_error($conn);
    }
    mysqli_close($conn);
?>