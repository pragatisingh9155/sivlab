<?php
if(isset($_POST['register'])) 
{
    $emailid = $_POST['emailid'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $repass = $_POST['repass'];


    include 'dbconfig.php';
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE emailid = ?");
    $stmt->bind_param("s", $emailid);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) 
    {
        echo "<script>alert('Email ID already exists. Please try a different email ID.')</script>";
        echo "<meta http-equiv='refresh' content='0;register.php'/>";
    }
    else 
    {
        if($password == $repass) 
        {
            $stmt = $conn->prepare("INSERT INTO user (emailid, name, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $emailid, $name, $password);
            if($stmt->execute()) 
            {
                echo "<script>alert('Data added successfully')</script>";
                echo "<meta http-equiv='refresh' content='0;login.html'/>";
            } 
            else 
            {
                echo "<script>alert('Error adding data')</script>";
                echo "<meta http-equiv='refresh' content='0;register.html'/>";
            }
        } 
        else 
        {
            echo "<script>alert('Passwords do not match')</script>";
            echo "<meta http-equiv='refresh' content='0;register.html'/>";
        }
    }
    $stmt->close();
    $conn->close();
}
else 
{
    header("location:register.php");
}
?>
