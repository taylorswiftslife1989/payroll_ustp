<?php
$servername = "localhost";
$username = "root";
$password = "chrisP1989.";
$dbname = "payroll_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
  $search = $conn->real_escape_string($_GET['search']);
  $sql = "SELECT * FROM tbl_employees WHERE 
            empID LIKE '%$search%' OR
            firstName LIKE '%$search%' OR
            lastName LIKE '%$search%' OR
            gender LIKE '%$search%' OR
            age LIKE '%$search%' OR
            email LIKE '%$search%' OR
            jobPosition LIKE '%$search%' OR
            department LIKE '%$search%' OR
            tinNumber LIKE '%$search%' OR
            sssNumber LIKE '%$search%' OR
            philHealth LIKE '%$search%' OR
            pagibig LIKE '%$search%'";
} else {
  $sql = "SELECT * FROM tbl_employees";
}
$result = $conn->query($sql);

// Handle employee update (AJAX POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editEmpID'])) {
  $empID = $_POST['editEmpID'];
  $firstName = $_POST['editFirstName'];
  $middleName = $_POST['editMiddleName'];
  $lastName = $_POST['editLastName'];
  $gender = $_POST['editGender'];
  $civilStatus = $_POST['editCivilStatus'];
  $age = $_POST['editAge'];
  $email = $_POST['editEmail'];
  $contactNumber = $_POST['editContactNumber'];
  $dateHired = $_POST['editDateHired'];
  $jobPosition = $_POST['editJobPosition'];
  $department = $_POST['editDepartment'];
  $tinNumber = $_POST['editTinNumber'];
  $sssNumber = $_POST['editSssNumber'];
  $philHealth = $_POST['editPhilHealth'];
  $pagibig = $_POST['editPagibig'];
  $ratePerHour = $_POST['editRatePerHour'];
  $paymentMode = $_POST['editPaymentMode'];
  $backupPayment = $_POST['editBackupPayment'];
  $emergencyNumber = $_POST['editEmergencyNumber'];
  $emergencyName = $_POST['editEmergencyName'];
  $relation = $_POST['editRelation'];

  // Handle photo upload if provided
  $photoFileName = $_POST['currentPhoto'];
  if (isset($_FILES['editPhoto']) && $_FILES['editPhoto']['error'] === UPLOAD_ERR_OK) {
      $targetDir = "uploads/";
      $ext = pathinfo($_FILES['editPhoto']['name'], PATHINFO_EXTENSION);
      $photoFileName = uniqid("emp_") . "." . $ext;
      move_uploaded_file($_FILES['editPhoto']['tmp_name'], $targetDir . $photoFileName);
  }

  $stmt = $conn->prepare("UPDATE tbl_employees SET 
      firstName=?, middleName=?, lastName=?, gender=?, civilStatus=?, age=?, email=?, contactNumber=?, dateHired=?, jobPosition=?, department=?, tinNumber=?, sssNumber=?, philHealth=?, pagibig=?, ratePerHour=?, paymentMode=?, backupPayment=?, emergencyNumber=?, emergencyName=?, relation=?, photo=?
      WHERE empID=?");
  $stmt->bind_param("sssssssssssssssssssssss", $firstName, $middleName, $lastName, $gender, $civilStatus, $age, $email, $contactNumber, $dateHired, $jobPosition, $department, $tinNumber, $sssNumber, $philHealth, $pagibig, $ratePerHour, $paymentMode, $backupPayment, $emergencyNumber, $emergencyName, $relation, $photoFileName, $empID);
  $success = $stmt->execute();
  if ($success) {
      echo "<script>window.location.href='employee_list.php?updated=1';</script>";
      exit;
  } else {
      echo "<script>alert('Update failed.');</script>";
  }
}
?>

<?php
// Place this at the VERY TOP of employee_list.php (before any HTML or whitespace)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['empID'])) {
    // ... (your DB connection code here, if not already connected)
    $empID = $_POST['empID'];
    $stmt = $conn->prepare("DELETE FROM tbl_employees WHERE empID = ?");
    $stmt->bind_param("s", $empID);
    if ($stmt->execute()) {
        // Redirect to avoid form resubmission warning
        header("Location: employee_list.php?deleted=1");
        exit;
    } else {
        // Optionally handle error
        header("Location: employee_list.php?deleted=0");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PMS | Employee's List</title>
  <link rel="stylesheet" href="employee_registration.css" />
  <style>
    .table-container {
      margin: 1rem auto; /* reduced margin */
      padding: 0.5rem; /* less padding = more outer space */
      width: 95%;
      max-width: 1200px;
      max-height: 420px; /* NEW: limits height */
      overflow-y: auto; /* NEW: scrolls if table is tall */
      font-style: italic;
      background-color: #fff;
      border-radius: 10px;
      margin-top: 100px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      overflow-x: auto;
      z-index: 1;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.8rem;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #251f52;
      color: white;
    }

    img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 50%;
    }

    /*TABLE UI TOOLS*/
    .table-tools {
      display: flex;
      position: fixed;
      align-items: center;
      gap: 0.7rem;
      padding: 1rem 2rem;
      background-color: #251f52;
      border-radius: 12px;
      width: 95%;
      max-width: 1200px;
      margin: 7rem auto -1rem auto;
      margin-left: 180px;
      font-style: italic;
      z-index: 2;
    }

    .search-label {
      color: white;
      font-weight: bold;
      font-style: italic;
      font-size: 1.2rem;
    }

    .search-input {
      flex: 1;
      height: 2.2rem;
      padding: 0 10px;
      border-radius: 8px;
      border: none;
      box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
    }

    .search-btn {
      background-color: orange;
      border: none;
      color: white;
      padding: 0.5rem;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1rem;
      transition: transform 0.3s;
    }
    .icon-btn {
      width: 24px;
      height: 24px;
      object-fit: contain;
    }

    .btn-content {
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .search-btn:hover {
      transform: scale(1.1);
    }
    
    .grid-btn {
      background-color: #708db3;
      color: white;
      border: none;
      padding: 0.5rem 0.8rem;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: transform 0.3s;
    }

    .grid-btn:hover {
      transform: scale(1.1);
    }

    .delete-btn {
      background-color: #e56262;
      color: white;
      border: none;
      padding: 0.5rem 0.8rem;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: transform 0.3s;
    }

    .delete-btn:hover {
      transform: scale(1.1);
    }
  </style>
</head>
  <div class="back-button-container">
    <img src="./assets/backbutton.png" alt="Back Button" class="back-button" />
  </div>

  <div class="dashboard-title-bar">
    <img src="./assets/user.png" alt="Dashboard Icon" class="dashboard-icon" />
    <span class="dashboard-label">Employee's List</span>
  </div>

  <!-- Clock Widget -->
  <div class="right-widgets">
    <div class="clock-widget">
      <canvas id="analogClock" width="100" height="100"></canvas>
      <div id="digitalClock">12:00 AM</div>
    </div>
  </div>

    <!-- UI Tools (Search + Image Buttons) -->
    <div class="table-tools">
      <label for="searchInput" class="search-label">Search</label>
      <input type="text" id="searchInput" class="search-input" placeholder="Find employees..." />

      <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
        <button class="search-btn" id="clearButton" title="Clear Search">
          <img src="./assets/cancel.png" alt="Clear" class="icon-btn" />
        </button>
      <?php else: ?>
        <button class="search-btn" id="searchButton" title="Search">
          <img src="./assets/search.png" alt="Search" class="icon-btn" />
        </button>
      <?php endif; ?>


      <script>
        const searchInput = document.getElementById("searchInput");

        <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
          // Clear button behavior
          document.getElementById("clearButton").addEventListener("click", function () {
            window.location.href = "employee_list.php"; // Reloads full list
          });
        <?php else: ?>
          // Search button behavior
          document.getElementById("searchButton").addEventListener("click", function () {
            const query = searchInput.value.trim();
            if (query !== "") {
              window.location.href = `employee_list.php?search=${encodeURIComponent(query)}`;
            }
          });

          // Trigger search on Enter key
          searchInput.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
              event.preventDefault();
              document.getElementById("searchButton").click();
            }
          });
        <?php endif; ?>
      </script>

      <button class="grid-btn" title="Grid View">
        <span class="btn-content">
          <img src="./assets/grid.png" alt="Grid View" class="icon-btn" />
          <span class="btn-label">Show in Grid View</span>
        </span>
      </button>

      <button class="delete-btn" title="Delete Selected">
        <span class="btn-content">
          <img src="./assets/delete.png" alt="Delete" class="icon-btn" />
          <span class="btn-label">Delete Selected</span>
        </span>
      </button>
    </div>

  <!-- Employee Card Modal -->
  <div id="photoModal" style="display: none;" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <div class="modal-body">
        <img id="modalImg" src="" alt="Employee Photo" />
        <div class="blank-container" style="position:relative; display:flex; flex-direction:column; align-items:center; justify-content:flex-start;">
          <!-- USTP Card at the top -->
          <img src="./assets/ustp_card.png" alt="USTP Card" class="ustp-card-img" style="width: 120px; height: auto; margin-top: 12px; margin-bottom: 8px; display:block;" />
          <div id="modalEmpID" class="modal-empid"></div>
          <div id="modalEmpDetails" class="modal-empdetails"></div>
          <!-- Actions Button Container -->
          <td>
            <div class="four-actions-button">
              <button class="action-btn" id="editBtn" title="Edit">
                <img src="./assets/edit_btn.png" alt="Edit" style="width:150px; height:35px;">
              </button>
              <button class="action-btn" id="printBtn" title="Print">
                <img src="./assets/print_btn.png" alt="Print" style="width:150px; height:35px;">
              </button>
              <button class="action-btn" id="saveAsBtn" title="Save As">
                <img src="./assets/save_as_btn.png" alt="Save As" style="width:150px; height:35px;">
              </button>
              <button class="action-btn" id="deleteBtn" title="Delete">
                <img src="./assets/delete_btn.png" alt="Delete" style="width:150px; height:35px;">
              </button>
            </div>
          </td>
          <style>
          .four-actions-button {
            background: white;
            position: absolute;   /* Now relative to .blank-container */
            left: -400px;          /* 100px from the left of the blank-container */
            top: 450px;           /* adjust as needed */
            border-radius: 5px;
            display: flex;
            flex-direction: row;
            gap: 8px;
            justify-content: center;
            align-items: center;
            padding: 8px 50px;
            box-shadow: 0 2px 8px rgba(37,31,82,0.08);
          }
          .action-btn {
            background: white;
            border: none;
            border-radius: 0px;
            padding: 6px;
            box-shadow: 0 2px 8px rgba(37,31,82,0.08);
            transition: box-shadow 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s;
          }
          .action-btn:hover {
            transform: scale(1.2);
          }
        </style>
          <style>
          .modal-empid {
            margin-top: -33px;
            margin-left: 80px;
            font-family: 'Times New Roman', Times, serif;
            font-size: .8rem;
            font-weight: bold;
            color: #000;
            background:white
            border-radius: 8px;
            padding: 2px 18px;
            box-shadow: 0 2px 8px rgba(37,31,82,0.12);
            z-index: 1001;
            position: relative;
            letter-spacing: 1px;
            display: inline-block;
          }
          .modal-empdetails {
            margin-top: 10px;
            font-size: .85rem;
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #ffffff;
            border-radius: 8px;
            padding: 12px 18px;
            
            z-index: 1000;
            position: relative;
            width: 90%;
            max-width: 350px;
            text-align: left;
          }
          .modal-empdetails label {
            font-weight: bold;
            margin-right: 4px;
            color: #000;
          }
          .modal-empdetails .emp-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 6px;
            gap: 10px;
          }
          .modal-empdetails .emp-col {
            flex: 1 1 45%;
            min-width: 120px;
          }
          .modal-empdetails .emp-col-full {
            flex: 1 1 100%;
            min-width: 100px;
          }
          /* Ensure .modal-empid is always above the card and image */
          .modal-body, .modal-content, .blank-container, .modal-empid {
            position: relative;
          }
        </style>
          <!-- You can add more content below if needed -->
        </div>
      </div>
    </div>
  </div>

    <style>
      .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0; top: 0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.7);
        justify-content: center;
        align-items: center;
      }

      .modal-body {
        display: flex;
        gap: 20px;
        align-items: center;
        justify-content: center;
      }

      .modal-content {
        background: #251f52;
        padding: 30px;
        width: 830px;
        border-radius: 12px;
        text-align: center;
        position: relative;
        animation: fadeIn 0.3s ease-in-out;
      }

      .modal-body img, .blank-container {
        width: 400px;
        height: 400px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      }

      .ustp-card-img {
        width: 120px !important;
        height: auto !important;
        object-fit: contain !important;
        background-color: transparent !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        display: block;
        margin-top: 12px;
        margin-bottom: 8px;
      }

      .blank-container img[alt="USTP Card"] {
        display: flex;
        width: 350px !important;   /* or whatever size you want */
        height: 90px !important;
        object-fit: contain;
        box-shadow: none;
      }

      .modal-body img {
        object-fit: cover;
        box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.9);
      }

      .blank-container {
        background-color: white;
        box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.4);
      }

      .modal-content img {
        width: 400px; /* fixed width */
        height: 400px; /* fixed height */
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        object-fit: cover; /* fills the box, crops if needed */
        background-color: #000; /* optional: fills behind transparent images */
      }

      .close-btn {
        position: absolute;
        top: -3px; right: 8px;
        font-size: 1.75rem;
        font-weight: bold;
        cursor: pointer;
        color: white;
        transition: transform 0.3s;
      }
      .close-btn:hover {
        transform: scale(1.3);
      }

      @keyframes fadeIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
      }
    </style>

    <!-- Edit Employee Modal (Scrollable Fields) -->
    <div id="editEmployeeModal" class="modal">
      <div class="modal-content" style="width: 800px; max-width: 98vw;">
        <span class="close-btn" id="closeEditModal">&times;</span>
        <!-- Requirement 1: Add a title inside the modal -->
        <h2 style="text-align:center; margin-bottom:4px; color: #fff; font-size: 1.25rem;">Edit Employees</h2>
        <p style="text-align:center; color:#fbb217; margin-top:-8px; margin-bottom:16px; font-size:.75rem;">
          Update employee details below
        </p>
        <form id="editEmployeeForm" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 10px;">
          <!-- Scrollable fields container starts here -->
          <div class="edit-modal-scrollable-fields">
            <div class="edit-modal-photo-col" style="justify-content: center;">
              <img id="editPhotoPreview" src="assets/default_photo.png" alt="Photo" class="edit-modal-photo" style="max-width: 140px; max-height: 140px;">
              <input type="file" name="editPhoto" id="editPhoto" accept="image/*" style="margin-top:8px;">
            </div>
            <input type="hidden" name="editEmpID" id="editEmpID">
            <input type="hidden" name="currentPhoto" id="currentPhoto">
            <div class="edit-modal-flex-row">
              <!-- Left Column: Fields 1-12 -->
              <div class="edit-modal-col">
                <label>First Name: <input type="text" name="editFirstName" id="editFirstName" required></label>
                <label>Last Name: <input type="text" name="editLastName" id="editLastName" required></label>
                <!-- Requirement 2: Dropdown for Civil Status -->
                <label>
                  Civil Status:
                  <select name="editCivilStatus" id="editCivilStatus" required>
                    <option value="">Select</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Separated">Separated</option>
                    <option value="Divorced">Divorced</option>
                  </select>
                </label>
                <label>Email: <input type="email" name="editEmail" id="editEmail"></label>
                <label>Date Hired: <input type="date" name="editDateHired" id="editDateHired"></label>
                <!-- Requirement 2: Dropdown for Department -->
                <label>
                  Department:
                  <select name="editDepartment" id="editDepartment" required>
                    <option value="">Select</option>
                    <option value="College of Information, Technology, and Computing">College of Information, Technology, and Computing</option>
                    <option value="College of Engineering and Architecture">College of Engineering and Architecture</option>
                    <option value="College of Science and Technology Education">College of Science and Technology Education</option>
                    <option value="College of Technology">College of Technology</option>
                    <option value="College of Science and Mathematics">College of Science and Mathematics</option>
                    <option value="College of Medicine">College of Medicine</option>
                    <option value="Senior High School">Senior High School</option>
                  </select>
                </label>
                <label>SSS Number: <input type="text" name="editSssNumber" id="editSssNumber"></label>
                <label>Pag-IBIG: <input type="text" name="editPagibig" id="editPagibig"></label>
                <!-- Requirement 2: Dropdown for Payment Mode -->
                <label>
                  Payment Mode:
                  <select name="editPaymentMode" id="editPaymentMode" required>
                    <option value="">Select</option>
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="GCash">GCash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                  </select>
                </label>
                
                <label>Emergency Number: <input type="text" name="editEmergencyNumber" id="editEmergencyNumber"></label>
                <!-- Requirement 2: Dropdown for Relation -->
                <label>
                  Relation:
                  <select name="editRelation" id="editRelation" required>
                    <option value="">Select</option>
                    <option value="Parent">Parent</option>
                    <option value="Sibling">Sibling</option>
                    <option value="Spouse">Spouse</option>
                    <option value="Child">Child</option>
                    <option value="Relative">Relative</option>
                    <option value="Friend">Friend</option>
                  </select>
                </label>
              </div>
              <!-- Right Column: Fields 13-23 -->
              <div class="edit-modal-col">
                <label>Middle Name: <input type="text" name="editMiddleName" id="editMiddleName"></label>
                <label>
                  Gender: 
                  <select name="editGender" id="editGender" required>
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                </label>
                <label>Age: <input type="number" name="editAge" id="editAge" min="18"></label>
                <label>Contact Number: <input type="text" name="editContactNumber" id="editContactNumber"></label>
                <!-- Requirement 2: Dropdown for Job Position -->
                <label>
                  Job Position:
                  <select name="editJobPosition" id="editJobPosition" required>
                    <option value="">Select</option>
                    <option value="Department Head">Department Head</option>
                    <option value="Program Chair">Program Chair</option>
                    <option value="Instructor">Instructor</option>
                    <option value="Capstone Adviser">Capstone Adviser</option>
                    <option value="Web Developer">Web Developer</option>
                    <option value="System Analyst">System Analyst</option>
                    <option value="Network Administrator">Network Administrator</option>
                    <option value="Database Admin">Database Admin</option>
                    <option value="IT Support Staff">IT Support Staff</option>
                    <option value="Office Staff">Office Staff</option>
                  </select>
                </label>
                <label>TIN Number: <input type="text" name="editTinNumber" id="editTinNumber"></label>
                <label>PhilHealth: <input type="text" name="editPhilHealth" id="editPhilHealth"></label>
                <label>Rate per Hour: <input type="number" name="editRatePerHour" id="editRatePerHour" step="0.01"></label>
                <!-- Requirement 2: Dropdown for Backup Payment -->
                <label>
                  Backup Payment:
                  <select name="editBackupPayment" id="editBackupPayment" required>
                    <option value="">Select</option>
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="GCash">GCash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                  </select>
                </label>
                <label>Emergency Name: <input type="text" name="editEmergencyName" id="editEmergencyName"></label>
              </div>
            </div>
          </div>
          <!-- End of scrollable fields container -->
          <div class="save-changes-btn-container2">
            <button type="submit" class="save-changes-btn2"
              style="padding:18px 60px; font-size:.75rem; background:#fbb217; color:#fff; border-radius:12px; border:none; font-weight:bold; cursor:pointer;">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>


    <style>
    .save-changes-btn-container2 {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 18px;
      margin-bottom: 8px;
      width: 100%;
    }

    #editEmployeeForm .save-changes-btn2 {
      background: #fbb217 !important;
      color: #fff !important;
      border: none;
      border-radius: 12px;
      padding: 18px 60px;
      font-size: .95rem;
      font-weight: bold;
      cursor: pointer;
      transition: transform 0.2s, background 0.3s;
    }
    #editEmployeeForm .save-changes-btn2:hover {
      transform: scale(1.2);
    }


    #editEmployeeModal.modal {
      display: none;
      position: fixed;
      z-index: 2001;
      left: 0; top: 0;
      width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
    }
    #editEmployeeModal .modal-content {
      background: #251f52;
      color: #fff;
      border-radius: 12px;
      padding: 30px 30px 20px 30px;
      box-shadow: 0 8px 32px rgba(37,31,82,0.18);
      position: relative;
    }
    #editEmployeeModal .close-btn {
      position: absolute;
      top: 10px; right: 18px;
      font-size: 2rem;
      font-weight: bold;
      color: #fff;
      cursor: pointer;
      z-index: 10;
      background: #251f52;
      border-radius: 50%;
      padding: 2px 10px;
      transition: transform 0.3s;
    }
    #editEmployeeModal .close-btn:hover {
      transform: scale(1.3);
    }
    /* Add this CSS to make the fields scrollable */
    .edit-modal-scrollable-fields {
      max-height: 420px;
      overflow-y: auto;
      padding-right: 8px;
      margin-bottom: 10px;
      /* Optional: for better appearance */
      scrollbar-width: thin;
      scrollbar-color: #251f52 #eee;
    }
    .edit-modal-scrollable-fields::-webkit-scrollbar {
      width: 8px;
    }
    .edit-modal-scrollable-fields::-webkit-scrollbar-thumb {
      background: #251f52;
      border-radius: 6px;
    }
    .edit-modal-scrollable-fields::-webkit-scrollbar-track {
      background: #eee;
      border-radius: 6px;
    }
    .edit-modal-flex-row {
      display: flex;
      flex-direction: row;
      align-items: flex-start; /* aligns columns to the top */
      justify-content: center;
      gap: 32px;
    }
    .edit-modal-col {
      display: flex;
      flex-direction: column;
      gap: 10px;
      min-width: 210px;
      max-width: 230px;
    }
    .edit-modal-photo-col {
      display: flex;
      flex-direction: column;
      margin-left: 310px;
      justify-content: flex-start; /* aligns content to the top */
      min-width: 160px;
      max-width: 180px;
    }
    .edit-modal-photo {
      width: 60px;
      height: 60px;
      border-radius: 10px;

      object-fit: cover;
      border: 2px solid #fff;
      background: #eee;
      margin-bottom: 8px;
    }

    /* Hide the "No file chosen" text for file inputs */
    input[type="file"]::-webkit-file-upload-button {
      visibility: visible;
    }

    input[type="file"]::file-selector-button {
      visibility: visible;
    }

    input[type="file"] {
      color: transparent;
      /* Optional: Remove extra width so only the button is visible */
      width: 120px;
      min-width: 0;
      max-width: 100%;
      cursor: pointer;
      background: none;
      border: none;
      padding: 0;
    }

    #editEmployeeForm label {
      color: #fff;
      font-size: 0.98rem;
      font-weight: 500;
      display: flex;
      flex-direction: column;
      gap: 2px;
    }
    #editEmployeeForm input,
    #editEmployeeForm select {
      border-radius: 6px;
      border: 1px solid #ccc;
      padding: 6px 8px;
      font-size: 1rem;
      margin-top: 2px;
      background: #fff;
      color: #251f52;
    }
    #editEmployeeForm input[type="file"] {
      background: none;
      color: #fff;
      border: none;
      padding: 0;
    }
    #editEmployeeForm button[type="submit"] {
      background: #251f52;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 10px 0;
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.2s;
    }
    #editEmployeeForm button[type="submit"]:hover {
      background: #3a2c6d;
    }
    </style>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
      // Helper: Map table columns to form fields
      const fieldMap = [
        null, // 0: Photo (skip)
        "editEmpID", // 1: Employee ID
        "editFirstName", // 2: First Name
        "editMiddleName", // 3: Middle Name
        "editLastName", // 4: Last Name
        "editGender", // 5: Gender (dropdown)
        "editCivilStatus", // 6: Civil Status (dropdown)
        "editAge", // 7: Age
        "editEmail", // 8: Email
        "editContactNumber", // 9: Contact Number
        "editDateHired", // 10: Date Hired
        "editJobPosition", // 11: Job Position (dropdown)
        "editDepartment", // 12: Department (dropdown)
        "editTinNumber", // 13: TIN No.
        "editSssNumber", // 14: SSS No.
        "editPhilHealth", // 15: PhilHealth No.
        "editPagibig", // 16: Pag-IBIG No.
        "editRatePerHour", // 17: Rate per Hour
        "editPaymentMode", // 18: Payment Mode (dropdown)
        "editBackupPayment", // 19: Backup Payment (dropdown)
        "editEmergencyNumber", // 20: Emergency #
        "editEmergencyName", // 21: Emergency Contact
        "editRelation", // 22: Relation (dropdown)
      ];

      // Get all table rows
      const rows = document.querySelectorAll("tbody tr");
      const editEmployeeModal = document.getElementById('editEmployeeModal');
      const editPhotoPreview = document.getElementById('editPhotoPreview');
      const editPhotoInput = document.getElementById('editPhoto');
      const currentPhotoInput = document.getElementById('currentPhoto');

      // When a row is clicked, store the data for editing
      let selectedRowData = null;
      rows.forEach(row => {
        row.addEventListener("click", function () {
          const cells = row.querySelectorAll("td");
          selectedRowData = [];
          for (let i = 0; i < cells.length; i++) {
            // For photo, get the src filename
            if (i === 0) {
              const img = cells[0].querySelector("img");
              selectedRowData.push(img ? img.getAttribute("src").split("/").pop() : "");
            } else {
              selectedRowData.push(cells[i].textContent.trim());
            }
          }
          // Store for later use when Edit is clicked
          window._selectedEmployeeRowData = selectedRowData;
        });
      });

      // Edit button in the employee modal
      const editBtn = document.getElementById('editBtn');
      if (editBtn) {
        editBtn.addEventListener('click', function (e) {
          e.preventDefault();
          const data = window._selectedEmployeeRowData;
          if (!data) {
            alert("No employee selected.");
            return;
          }
          // Set photo preview and current photo hidden input
          if (data[0]) {
            editPhotoPreview.src = "uploads/" + data[0];
            currentPhotoInput.value = data[0];
          } else {
            editPhotoPreview.src = "assets/default_photo.png";
            currentPhotoInput.value = "";
          }
          // Fill all fields
          for (let i = 1; i < fieldMap.length; i++) {
            const fieldId = fieldMap[i];
            if (!fieldId) continue;
            const field = document.getElementById(fieldId);
            if (!field) continue;
            // For dropdowns, set value directly
            if (field.tagName === "SELECT") {
              field.value = data[i] || "";
            } else {
              field.value = data[i] || "";
            }
          }
          // Show the modal
          editEmployeeModal.style.display = "flex";
        });
      }

      // Photo preview on file select
      editPhotoInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
          const reader = new FileReader();
          reader.onload = function (e) {
            editPhotoPreview.src = e.target.result;
          };
          reader.readAsDataURL(this.files[0]);
        }
      });

      // Close modal logic (reuse your existing code)
      const closeEditModal = document.getElementById('closeEditModal');
      closeEditModal.onclick = function () {
        editEmployeeModal.style.display = "none";
      };
      window.onclick = function (event) {
        if (event.target === editEmployeeModal) {
          editEmployeeModal.style.display = "none";
        }
      };
    });

    </script>

    <!-- Print Options Modal -->
    <div id="printOptionsModal" class="sub-modal" style="display:none;">
      <div class="sub-modal-content">
        <span class="sub-close-btn" id="closePrintOptions">&times;</span>
        <div class="sub-modal-body">
          <button class="sub-option-btn" id="printCardBtn" style="background:white; border:none; border-radius:8px; padding:18px; margin:12px;">
            <img src="./assets/prnt_card.png" alt="Print Card" style="width:64px; height:64px;"><br>
            <span>Print Card</span>
          </button>
          <button class="sub-option-btn" id="printFormBtn" style="background:white; border:none; border-radius:8px; padding:18px; margin:12px;">
            <img src="./assets/prnt_form.png" alt="Print Form" style="width:64px; height:64px;"><br>
            <span>Print Form</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Save As Options Modal -->
    <div id="saveAsOptionsModal" class="sub-modal" style="display:none;">
      <div class="sub-modal-content">
        <span class="sub-close-btn" id="closeSaveAsOptions">&times;</span>
        <div class="sub-modal-body">
          <button class="sub-option-btn" id="saveAsPdfBtn" style="background:white; border:none; border-radius:8px; padding:10px; margin:12px;">
            <img src="./assets/sav_pdf.png" alt="Save as PDF" style="width:64px; height:64px;"><br>
            <span>Save as PDF</span>
          </button>
          <button class="sub-option-btn" id="saveAsImgBtn" style="background:white; border:none; border-radius:8px; padding:10px; margin:12px;">
            <img src="./assets/sav_img.png" alt="Save as Image" style="width:64px; height:64px;"><br>
            <span>Save as Image</span>
          </button>
        </div>
      </div>
    </div>
    <script src="assets/employee_save_as_pdf.js"></script>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const saveAsPdfBtn = document.getElementById("saveAsPdfBtn");
        const modalEmpID = document.getElementById("modalEmpID");

        function getEmpIDFromModal() {
          const match = modalEmpID.textContent.match(/ID:\s*(.+)/);
          return match ? match[1].trim() : "";
        }

        if (saveAsPdfBtn) {
          saveAsPdfBtn.addEventListener("click", function () {
            const empID = getEmpIDFromModal();
            if (empID) {
              // Open PDF in new tab or trigger download
              window.open("employee_pdf.php?empID=" + encodeURIComponent(empID), "_blank");
            } else {
              alert("No employee selected.");
            }
          });
        }
      });
    </script>    

    <style>
      .sub-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0; top: 0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
      }
      .sub-modal-content {
        background: white;
        border-radius: 5px;
        padding: 32px 32px 24px 32px;
        min-width: 320px;
        min-height: 180px;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        box-shadow: 0 8px 32px rgba(37,31,82,0.18);
        animation: fadeIn 0.2s;
      }
      .sub-modal-body {
        display: flex;
        flex-direction: row;
        gap: 24px;
        justify-content: center;
        align-items: center;
        width: 100%;
        margin-top: 12px;
      }
      .sub-option-btn img {
        width: 100px !important;
        height: 100px !important;
        border-radius: 5px;
        object-fit: contain;
      }
      .sub-option-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: white;
        border: none;
        border-radius: 8px;
        padding: 10px;
        margin: 12px;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(37,31,82,0.08);
        transition: box-shadow 0.2s;
        font-size: 1rem;
        color: #000;
        font-weight: bold;
        transition: transform 0.3s;
      }
      .sub-option-btn:hover {
        box-shadow: 0 4px 16px rgba(37,31,82,0.18);
        background: #f7f7f7;
        transform: scale(1.1);
      }
      .sub-close-btn {
        position: absolute;
        top: 10px; right: 18px;
        font-size: 2rem;
        font-weight: bold;
        color: #251f52;
        cursor: pointer;
        z-index: 10;
        background: white;
        border-radius: 50%;
        padding: 2px 10px;
        transition: transform 0.3s;
      }
      .sub-close-btn:hover {
        transform: scale(1.3);
      }
    </style>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const rows = document.querySelectorAll("tbody tr");
        const modal = document.getElementById("photoModal");
        const modalImg = document.getElementById("modalImg");
        const closeBtn = document.querySelector(".close-btn");
        const modalEmpID = document.getElementById("modalEmpID");
        const modalEmpDetails = document.getElementById("modalEmpDetails");

        // Actions Button Container logic
        const printBtn = document.getElementById("printBtn");
        const saveAsBtn = document.getElementById("saveAsBtn");
        const printOptionsModal = document.getElementById("printOptionsModal");
        const saveAsOptionsModal = document.getElementById("saveAsOptionsModal");
        const closePrintOptions = document.getElementById("closePrintOptions");
        const closeSaveAsOptions = document.getElementById("closeSaveAsOptions");

        // Show Print Options Modal
        printBtn && printBtn.addEventListener("click", (e) => {
          e.stopPropagation();
          printOptionsModal.style.display = "flex";
        });

        // Show Save As Options Modal
        saveAsBtn && saveAsBtn.addEventListener("click", (e) => {
          e.stopPropagation();
          saveAsOptionsModal.style.display = "flex";
        });

        // Close Print Options Modal
        closePrintOptions && closePrintOptions.addEventListener("click", () => {
          printOptionsModal.style.display = "none";
        });

        // Close Save As Options Modal
        closeSaveAsOptions && closeSaveAsOptions.addEventListener("click", () => {
          saveAsOptionsModal.style.display = "none";
        });

        // Clicking outside the sub-modal closes it
        window.addEventListener("click", function(event) {
          if (event.target === printOptionsModal) {
            printOptionsModal.style.display = "none";
          }
          if (event.target === saveAsOptionsModal) {
            saveAsOptionsModal.style.display = "none";
          }
        });

        // Prevent modal click from bubbling to window
        document.querySelectorAll('.sub-modal-content').forEach(function(modalContent) {
          modalContent.addEventListener('click', function(e) {
            e.stopPropagation();
          });
        });
        rows.forEach(row => {
          row.addEventListener("click", () => {
            const cells = row.querySelectorAll("td");
            const img = row.querySelector("img");
            if (img && cells.length > 0) {
              // Set photo and Employee ID
              modalImg.src = img.src;
              modalEmpID.textContent = `ID: ${cells[1].textContent.trim()}`;

              // Fill in the new fields
              modalEmpDetails.innerHTML = `
                <div class="emp-row">
                  <div class="emp-col"><label>First Name:</label> ${cells[2].textContent.trim()}</div>
                  <div class="emp-col"><label>M.I:</label> ${cells[3].textContent.trim()}</div>
                </div>
                <div class="emp-row">
                  <div class="emp-col"><label>Last Name:</label> ${cells[4].textContent.trim()}</div>
                  <div class="emp-col"><label>Gender:</label> ${cells[5].textContent.trim()}</div>
                  <div class="emp-col"><label>Age:</label> ${cells[7].textContent.trim()}</div>
                </div>
                <div class="emp-row">
                  <div class="emp-col-full"><label>Address:</label> ${cells[8].textContent.trim()}</div>
                </div>
                <div class="emp-row">
                  <div class="emp-col"><label>Job Position:</label> ${cells[11].textContent.trim()}</div>
                  <div class="emp-col"><label>Date Hired:</label> ${cells[10].textContent.trim()}</div>
                </div>
                <div class="emp-row">
                  <div class="emp-col"><label>TIN Number:</label> ${cells[13].textContent.trim()}</div>
                </div>
                <div class="emp-row">
                  <div class="emp-col"><label>PhilHealth Number:</label> ${cells[15].textContent.trim()}</div>
                </div>
                <div class="emp-row">
                  <div class="emp-col"><label>SSS Number:</label> ${cells[14].textContent.trim()}</div>
                </div>
                <div class="emp-row">
                  <div class="emp-col"><label>PagIbig Number:</label> ${cells[16].textContent.trim()}</div>
                </div>
                <div class="emp-row">
                  <div class="emp-col"><label>Emergency Contact Name:</label> ${cells[21].textContent.trim()}</div>
                </div>
                <div class="emp-row">
                  <div class="emp-col"><label>Emergency Contact Number:</label> ${cells[20].textContent.trim()}</div>
                </div>
              `;

              modal.style.display = "flex";
            }
          });
        });

        closeBtn.onclick = () => {
          modal.style.display = "none";
        };

        window.onclick = (event) => {
          if (event.target === modal) {
            modal.style.display = "none";
          }
        };
      });
      </script>


  <!-- 🧠 Your Dynamic Table Goes Here -->
  <div class="entry-container">
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Employee Photo</th><th>Employee ID Number</th><th>First Name</th><th>Middle Name</th><th>Last Name</th>
            <th>Gender</th><th>Civil Status</th><th>Age</th><th>Email Address</th><th>Contact Number</th>
            <th>Date Hired</th><th>Job Position</th><th>Department</th><th>TIN No.</th><th>SSS No.</th>
            <th>PhilHealth No.</th><th>Pag-IBIG No.</th><th>Rate per Hour</th><th>Payment Mode</th>
            <th>Backup Payment</th><th>Emergency #</th><th>Emergency Contact</th><th>Relation</th>
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
          <tr><td colspan="25">No employee records found 😭</td></tr> <!-- changed colspan to 25 because new 'Actions' column -->
        <?php endif; ?>
      </tbody>
      <!-- Single Delete Confirmation Modal (rendered ONCE) -->
      <div id="deleteEmployeeModal" class="modal-warn">
        <div class="modal-content2">
          <img src="./assets/warn.png" class="modal-icon2" alt="Warning Icon">
          <h2>Are you sure you want to <span style="color: #FBB217;">delete this Employee?</span></h2>
          <p>This action cannot be undone.<br>This registered employee will be removed.</p>
          <form id="deleteEmployeeForm" method="POST" style="display:inline;">
            <input type="hidden" name="empID" id="deleteEmpID" value="">
            <div class="modal-buttons2">
              <button type="submit" class="yes-btn">
                <img src="./assets/delete.png" alt="Yes Icon"> YES
              </button>
              <button type="button" class="no-btn" id="cancelDeleteBtn">
                <img src="./assets/cancel.png" alt="No Icon"> NO
              </button>
            </div>
          </form>
        </div>
      </div>
      
      <style>
        /* Modal Styles (reuse from employee_registration.css) */
        .modal-warn {
          display: none; /* Hidden by default */
          position: fixed;
          z-index: 2000;
          left: 0;
          top: 0;
          width: 100vw;
          height: 100vh;
          overflow: auto;
          background: rgba(37, 31, 82, 0.4);
          justify-content: center;
          align-items: center;
        }

        .modal-content2 {
          background-color: #ffffff;
          padding: 30px 35px;
          border-radius: 20px;
          text-align: center;
          margin-top: 10px  ;
          width: 420px;
          box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
          position: relative;
          animation: fadeIn 0.4s ease-in-out;
        }

        .modal-content2 h2 {
          margin-bottom: 10px;
          color: #251f52;
          font-size: 22px;
        }

        .modal-content2 p {
          color: #333;
          margin-bottom: 25px;
          font-size: 14px;
          line-height: 1.5;
        }

        .modal-icon2 {
          width: 75px;
          height: 75px;
          border-radius: 0px;
        }

        .modal-buttons2 {
          display: flex;
          justify-content: center;
          gap: 20px;
        }

        .modal-buttons2 button {
          border: none;
          cursor: pointer;
          font-weight: bold;
          font-size: 16px;
          padding: 10px 18px;
          border-radius: 5px;
          display: flex;
          align-items: center;
          gap: 10px;
          transition: all 0.3s ease;
        }

        .modal-buttons2 .yes-btn {
          background-color: #e74c3c;
          color: white;
        }

        .modal-buttons2 .no-btn {
          background-color: #3b5998;
          color: white;
        }

        .modal-buttons2 button:hover {
          transform: scale(1.05);
        }

        .modal-buttons2 img {
          width: 18px;
          height: 18px;
        }
        </style>
        <script>
        // Place this script after the DOM is loaded and after the modal HTML

        document.addEventListener("DOMContentLoaded", function () {
          // Modal elements
          const deleteEmployeeModal = document.getElementById('deleteEmployeeModal');
          const deleteEmpIDInput = document.getElementById('deleteEmpID');
          const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
          const deleteEmployeeForm = document.getElementById('deleteEmployeeForm');

          // The delete button inside the employee details modal
          const modalDeleteBtn = document.getElementById('deleteBtn');
          // The modal that shows employee details
          const photoModal = document.getElementById('photoModal');
          // The Employee ID shown in the modal (e.g., "ID: 12345")
          const modalEmpID = document.getElementById('modalEmpID');

          // Helper to extract the actual empID from the modal's Employee ID text
          function getEmpIDFromModal() {
            // modalEmpID.textContent is like "ID: 12345"
            const match = modalEmpID.textContent.match(/ID:\s*(.+)/);
            return match ? match[1].trim() : "";
          }

          // When the delete button in the modal is clicked
          if (modalDeleteBtn) {
            modalDeleteBtn.addEventListener('click', function (e) {
              e.preventDefault();
              // Get the current empID from the modal
              const empID = getEmpIDFromModal();
              if (empID) {
                deleteEmpIDInput.value = empID;
                // Hide the photo modal
                photoModal.style.display = "none";
                // Show the warning modal
                deleteEmployeeModal.style.display = 'flex';
              }
            });
          }

          // Cancel button in warning modal
          if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function () {
              deleteEmployeeModal.style.display = 'none';
              deleteEmpIDInput.value = '';
            });
          }

          // Hide modal when clicking outside modal content
          window.addEventListener('click', function(event) {
            if (event.target === deleteEmployeeModal) {
              deleteEmployeeModal.style.display = 'none';
              deleteEmpIDInput.value = '';
            }
          });

          // Optional: Prevent accidental form submission if empID is missing
          if (deleteEmployeeForm) {
            deleteEmployeeForm.addEventListener('submit', function (e) {
              if (!deleteEmpIDInput.value) {
                e.preventDefault();
                alert("No employee selected for deletion.");
              }
            });
          }
        });
        </script>
      </table>
    </div>
  </div>

  <div class="back-button-container" onclick="goBack()">
    <img src="./assets/backbutton.png" alt="Back Button" class="back-button" />
  </div>

  <script>
    function goBack() {
      window.location.href = "sys_task.html";
    }
  </script>
  
  <!-- Clock Script -->
  <script type="module">
    import { drawClock, updateDigitalClock } from "./clock.js";
    window.onload = () => {
      drawClock();
      updateDigitalClock();
    };
  </script>

  <div id="overlay" class="overlay hidden"></div>
</body>
</html>
<?php $conn->close(); ?>
