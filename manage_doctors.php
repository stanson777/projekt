<?php
require_once "database2.php";

function getAllDoctors($conn) {
    $sql = "SELECT * FROM doctors";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function addDoctor($conn, $doctors_id, $password) {
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO doctors (doctors_id, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $doctors_id, $passwordHash);
    return mysqli_stmt_execute($stmt);
}

function updateDoctorPassword($conn, $doctors_id, $new_password) {
    $passwordHash = password_hash($new_password, PASSWORD_BCRYPT);
    $sql = "UPDATE doctors SET password = ? WHERE doctors_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $passwordHash, $doctors_id);
    return mysqli_stmt_execute($stmt);
}

function deleteDoctor($conn, $doctors_id) {
    $sql = "DELETE FROM doctors WHERE doctors_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $doctors_id);
    return mysqli_stmt_execute($stmt);
}

function getUniqueDoctors($doctors) {
    $uniqueDoctors = [];
    $seenDoctorIds = [];
    
    foreach ($doctors as $doctor) {
        if (!in_array($doctor['doctors_id'], $seenDoctorIds)) {
            $uniqueDoctors[] = $doctor;
            $seenDoctorIds[] = $doctor['doctors_id'];
        }
    }
    
    return $uniqueDoctors;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_doctor'])) {
        $doctors_id = $_POST['doctors_id'];
        $password = $_POST['password'];
        if (addDoctor($conn, $doctors_id, $password)) {
            $success_message = "Doctor added successfully.";
        } else {
            $error_message = "Error adding doctor.";
        }
    } elseif (isset($_POST['update_password'])) {
        $doctors_id = $_POST['doctors_id'];
        $new_password = $_POST['new_password'];
        if (updateDoctorPassword($conn, $doctors_id, $new_password)) {
            $success_message = "Password updated successfully.";
        } else {
            $error_message = "Error updating password.";
        }
    } elseif (isset($_POST['delete_doctor'])) {
        $doctors_id = $_POST['doctors_id'];
        if (deleteDoctor($conn, $doctors_id)) {
            $success_message = "Doctor deleted successfully.";
        } else {
            $error_message = "Error deleting doctor.";
        }
    }
}

$doctors = getAllDoctors($conn);
$uniqueDoctors = getUniqueDoctors($doctors);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        nav {
            background-color: #007bff;
            color: #fff;
            padding: 1rem;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul.sidebar {
            display: none;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #007bff;
            padding-top: 2rem;
        }

        nav ul.sidebar li {
            margin: 1rem 0;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            display: block;
        }

        nav ul li a:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
     <nav>
        <ul class="sidebar">
            <li onclick=closeSidebar()><a href="#home"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg></a></li>
            <li><a href="accountPage.php">Konto</a></li>
            <li><a href="lekarze.php">Lekarze</a></li>
            <li><a href="#receipt">E-recepta</a></li>
            <li><a href="adminPanel.php">Dla lekarza</a></li>
            <li><a href="services.php">Usługi</a></li>
            <li><a href="#contact">Kontakt</a></li>
        </ul>
        <ul>
            <li><a href="#home">Home</a></li>
            <li class="hideOnMobile"><a href="accountPage.php">Konto</a></li>
            <li class="hideOnMobile"><a href="lekarze.php">Lekarze</a></li>
            <li class="hideOnMobile"><a href="e-recepty.php">E-recepta</a></li>
            <li class="hideOnMobile"><a href="services.php">Usługi</a></li>
            <li class="hideOnMobile"><a href="#contact">Kontakt</a></li>
            <li class="hideOnMobile"><a href="adminPanel.php">Dla lekarza</a></li>
            <li onclick=showSidebar() class="menuBtn"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li>
        </ul>
    </nav>
    <div class="container mt-5">
        <h1>Zarzadzaj doktorami</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <h2>Dodaj doktora</h2>
        <form method="POST">
            <div class="form-group">
                <label for="doctors_id">Doktor ID:</label>
                <input type="text" class="form-control" id="doctors_id" name="doctors_id" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="add_doctor" class="btn btn-primary">Dodaj lekarza</button>
        </form>

        <h2 class="mt-5">Istniejacy doktorzy</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID doktora</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($uniqueDoctors as $doctor): ?>
                <tr>
                    <td><?php echo htmlspecialchars($doctor['doctors_id']); ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="doctors_id" value="<?php echo $doctor['doctors_id']; ?>">
                            <input type="password" name="new_password" placeholder="New Password" required>
                            <button type="submit" name="update_password" class="btn btn-warning btn-sm">Edytuj haslo</button>
                        </form>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this doctor?');">
                            <input type="hidden" name="doctors_id" value="<?php echo $doctor['doctors_id']; ?>">
                            <button type="submit" name="delete_doctor" class="btn btn-danger btn-sm">Usun</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
