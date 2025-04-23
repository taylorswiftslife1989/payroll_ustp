<?php
$servername = "localhost";
$username = "root";
$password = "chrisP1989.";
$dbname = "payroll_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tbl_employees";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Employee List</title>
  <link rel="stylesheet" href="employee_registration.css" />
  <style>
    .table-container {
      margin: 2rem auto;
      padding: 1rem;
      width: 95%;
      max-width: 1200px;
      background-color: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.9rem;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #0078d4;
      color: white;
    }

    img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 50%;
    }
  </style>
</head>
<body>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Photo</th>
          <th>ID</th>
          <th>First Name</th>
          <th>Middle Name</th>
          <th>Last Name</th>
          <th>Gender</th>
          <th>Civil Status</th>
          <th>Age</th>
          <th>Email</th>
          <th>Contact Number</th>
          <th>Date Hired</th>
          <th>Job Position</th>
          <th>Department</th>
          <th>TIN #</th>
          <th>SSS #</th>
          <th>PhilHealth</th>
          <th>Pag-IBIG</th>
          <th>Rate per Hour</th>
          <th>Payment Mode</th>
          <th>Backup Payment</th>
          <th>Emergency #</th>
          <th>Emergency Contact</th>
          <th>Relation</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" alt="Profile Photo"></td>
              <td><?php echo htmlspecialchars($row['empID']); ?></td>
              <td><?php echo htmlspecialchars($row['firstName']); ?></td>
              <td><?php echo htmlspecialchars($row['middleName']); ?></td>
              <td><?php echo htmlspecialchars($row['lastName']); ?></td>
              <td><?php echo htmlspecialchars($row['gender']); ?></td>
              <td><?php echo htmlspecialchars($row['civilStatus']); ?></td>
              <td><?php echo htmlspecialchars($row['age']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo htmlspecialchars($row['contactNumber']); ?></td>
              <td><?php echo htmlspecialchars($row['dateHired']); ?></td>
              <td><?php echo htmlspecialchars($row['jobPosition']); ?></td>
              <td><?php echo htmlspecialchars($row['department']); ?></td>
              <td><?php echo htmlspecialchars($row['tinNumber']); ?></td>
              <td><?php echo htmlspecialchars($row['sssNumber']); ?></td>
              <td><?php echo htmlspecialchars($row['philHealth']); ?></td>
              <td><?php echo htmlspecialchars($row['pagibig']); ?></td>
              <td><?php echo htmlspecialchars($row['ratePerHour']); ?></td>
              <td><?php echo htmlspecialchars($row['paymentMode']); ?></td>
              <td><?php echo htmlspecialchars($row['backupPayment']); ?></td>
              <td><?php echo htmlspecialchars($row['emergencyNumber']); ?></td>
              <td><?php echo htmlspecialchars($row['emergencyName']); ?></td>
              <td><?php echo htmlspecialchars($row['relation']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="23">No employee records found 😭</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</body>
</html>

<?php
$conn->close();
?>
