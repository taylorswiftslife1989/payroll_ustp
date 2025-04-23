<?php 
$servername = "localhost";
$username = "root"; 
$password = "chrisP1989.";     
$dbname = "payroll_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// File upload
$targetDir = "uploads/";
$fileName = basename($_FILES["photo"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
    // Get form data
    $empID = $_POST['empID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $middleName = $_POST['middleName'];
    $gender = $_POST['gender'];
    $civilStatus = $_POST['civilStatus'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $contactNumber = $_POST['contactNumber'];
    $dateHired = $_POST['dateHired'];
    $jobPosition = $_POST['jobPosition'];
    $department = $_POST['department'];
    $tinNumber = $_POST['tinNumber'];
    $sssNumber = $_POST['sssNumber'];
    $philHealth = $_POST['philHealth'];
    $pagibig = $_POST['pagibig'];
    $ratePerHour = $_POST['ratePerHour'];
    $paymentMode = $_POST['paymentMode'];
    $backupPayment = $_POST['backupPayment'];
    $emergencyNumber = $_POST['emergencyNumber'];
    $emergencyName = $_POST['emergencyName'];
    $relation = $_POST['relation'];

    // Insert into DB with photo filename
    $sql = "INSERT INTO tbl_employees (
      empID, firstName, lastName, middleName, gender, civilStatus, age, email, contactNumber,
      dateHired, jobPosition, department, tinNumber, sssNumber, philHealth, pagibig,
      ratePerHour, paymentMode, backupPayment, emergencyNumber, emergencyName, relation, photo
    ) VALUES (
      '$empID', '$firstName', '$lastName', '$middleName', '$gender', '$civilStatus', '$age', '$email', '$contactNumber',
      '$dateHired', '$jobPosition', '$department', '$tinNumber', '$sssNumber', '$philHealth', '$pagibig',
      '$ratePerHour', '$paymentMode', '$backupPayment', '$emergencyNumber', '$emergencyName', '$relation', '$fileName'
    )";

    if ($conn->query($sql) === TRUE) {
      echo "<script>alert('Employee registered successfully!'); window.location.href='employee_registration.html';</script>";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

} else {
    echo "Sorry, there was an error uploading the file.";
}

$conn->close();
?>
