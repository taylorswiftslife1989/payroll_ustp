

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PMS | Payroll Generation System</title>
    <link rel="stylesheet" href="pay_generator.css" />
    <style>
      .left-table-container {
        width: 420px;
        min-width: 350px;
        max-width: 500px;
        height: 80vh;
        max-height: 900px;
        overflow: auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 2px 0 8px rgba(0,0,0,0.04);
        margin: 20px 0 20px 0;
      }
      .employee-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
      }
      .employee-table th, .employee-table td {
        border: 1px solid #ccc;
        padding: 6px 8px;
        font-size: 14px;
        text-align: left;
      }
      .employee-table th {
        background: #f5f5f5;
        position: sticky;
        top: 0;
        z-index: 2;
      }
      .employee-table img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
      }
      @media (max-width: 900px) {
        .left-table-container {
          width: 100%;
          max-width: none;
          margin: 20px 0;
        }
      }
    </style>
  </head>
  <body>
    <div class="back-button-container">
      <img
        src="./assets/backbutton.png"
        alt="Back Button"
        class="back-button"
      />
    </div>

    <div class="dashboard-title-bar">
      <img
        src="./assets/payroll_management.png"
        alt="Dashboard Icon"
        class="dashboard-icon"
      />
      <span class="dashboard-label">Payroll Generation System</span>
    </div>
    <!-- Right Side: Clock-->
    <div class="right-widgets">
      <!-- Clock Widget -->
      <div class="clock-widget">
        <canvas id="analogClock" width="100" height="100"></canvas>
        <div id="digitalClock">12:00 AM</div>
      </div>
    </div>

    <!-- Entry Container: Employee Table goes here -->
    <div class="entry-container">
      <div class="left-table-container">
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

          if ($result && $result->num_rows > 0): ?>
            <table class="employee-table">
              <thead>
                <tr>
                  <th>Photo</th>
                  <th>EmpID</th>
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>Last Name</th>
                  <th>Gender</th>
                  <th>Civil Status</th>
                  <th>Age</th>
                  <th>Email</th>
                  <th>Contact #</th>
                  <th>Date Hired</th>
                  <th>Position</th>
                  <th>Department</th>
                  <th>TIN</th>
                  <th>SSS</th>
                  <th>PhilHealth</th>
                  <th>PAGIBIG</th>
                  <th>Rate/Hour</th>
                  <th>Payment Mode</th>
                </tr>
              </thead>
              <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td>
                      <?php if (!empty($row['photo'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" alt="Profile Photo">
                      <?php else: ?>
                        <span>No Photo</span>
                      <?php endif; ?>
                    </td>
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
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div>No employee records found 😭</div>
          <?php endif;
          $conn->close();
        ?>
      </div>
    </div>

    <!-- Sidebar Container -->
    <div class="back-button-container" onclick="goBack()">
      <img
        src="./assets/backbutton.png"
        alt="Back Button"
        class="back-button"
      />
    </div>

    <script>
      function goBack() {
        window.location.href = "payroll_man.html";
      }
    </script>
    <!-- Firebase Auth & Firestore Script -->
    <script type="module">
      import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-app.js";
      import {
        getAuth,
        signOut,
        onAuthStateChanged,
      } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-auth.js";
      import {
        getFirestore,
        doc,
        getDoc,
      } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-firestore.js";

      const firebaseConfig = {
        apiKey: "AIzaSyDRnBpfzuU_dnIwfmjogb6qTewbFcOo5DU",
        authDomain: "authentication-de919.firebaseapp.com",
        projectId: "authentication-de919",
        storageBucket: "authentication-de919.appspot.com",
        messagingSenderId: "368984103131",
        appId: "1:368984103131:web:bb27de4fd19f55e68c54fe",
      };

      const app = initializeApp(firebaseConfig);
      const auth = getAuth(app);
      const db = getFirestore(app);

      const nameDisplay = document.getElementById("userNameDisplay");
      const logoutBtn = document.getElementById("logoutBtn");

      onAuthStateChanged(auth, async (user) => {
        if (!user) {
          window.location.href = "index.html";
          return;
        }

        try {
          const userDoc = await getDoc(doc(db, "users", user.uid));
          if (userDoc.exists()) {
            const data = userDoc.data();
            const fullName = `${data.firstName} ${data.lastName}`;
            nameDisplay.textContent = fullName;
          } else {
            nameDisplay.textContent = "User Info Missing";
          }
        } catch (err) {
          nameDisplay.textContent = "Error loading user data";
        }
      });

      logoutBtn.addEventListener("click", () => {
        signOut(auth).then(() => {
          window.location.href = "index.html";
        });
      });
    </script>
    <!-- Clock & Calendar Scripts -->
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
</div>
  </body>
</html>
