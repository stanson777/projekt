<?php
require_once "database.php";


function getAllServices($conn) {
    $sql = "SELECT s.*, c.name as category_name FROM services s 
            JOIN categories c ON s.category_id = c.id";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function addService($conn, $name, $description, $price, $category_id) {
    $sql = "INSERT INTO services (name, description, price, category_id) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssdi", $name, $description, $price, $category_id);
    return mysqli_stmt_execute($stmt);
}

function updateService($conn, $id, $name, $description, $price, $category_id) {
    $sql = "UPDATE services SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssdii", $name, $description, $price, $category_id, $id);
    return mysqli_stmt_execute($stmt);
}

function deleteService($conn, $id) {
    $sql = "DELETE FROM services WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

function getAllCategories($conn) {
    $sql = "SELECT * FROM categories";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getUniqueServices($services) {
    $uniqueServices = [];
    $seenNames = [];
    
    foreach ($services as $service) {
        if (!in_array($service['name'], $seenNames)) {
            $uniqueServices[] = $service;
            $seenNames[] = $service['name'];
        }
    }
    
    return $uniqueServices;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_service'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];
        if (addService($conn, $name, $description, $price, $category_id)) {
            $success_message = "Usługa dodana pomyślnie.";
        } else {
            $error_message = "Błąd podczas dodawania usługi.";
        }
    } elseif (isset($_POST['update_service'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];
        if (updateService($conn, $id, $name, $description, $price, $category_id)) {
            $success_message = "Usługa zaktualizowana pomyślnie.";
        } else {
            $error_message = "Błąd podczas aktualizacji usługi.";
        }
    } elseif (isset($_POST['delete_service'])) {
        $id = $_POST['id'];
        if (deleteService($conn, $id)) {
            $success_message = "Usługa usunięta pomyślnie.";
        } else {
            $error_message = "Błąd podczas usuwania usługi.";
        }
    }
}

$services = getAllServices($conn);
$uniqueServices = getUniqueServices($services);
$categories = getAllCategories($conn);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie Usługami</title>
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
            <li onclick=closeSidebar()><a href="test.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg></a></li>
            <li><a href="accountPage.php">Konto</a></li>
            <li><a href="lekarze.php">Lekarze</a></li>
            <li><a href="#receipt">E-recepta</a></li>
            <li><a href="adminPanel.php">Dla lekarza</a></li>
            <li><a href="services.php">Usługi</a></li>
            <li><a href="#contact">Kontakt</a></li>
        </ul>
        <ul>
            <li><a href="test.php">Home</a></li>
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
        <h1>Zarządzanie Usługami</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <h2>Dodaj Nową Usługę</h2>
        <form method="POST">
            <div class="form-group">
                <label for="name">Nazwa usługi:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Opis:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Cena:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="category_id">Kategoria:</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="add_service" class="btn btn-primary">Dodaj Usługę</button>
        </form>

        <h2 class="mt-5">Istniejące Usługi</h2>
        <div class="row">
            <?php foreach ($uniqueServices as $service): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($service['name']) ?></h5>
                            <p class="card-text">Cena: <?= $service['price'] ?> zł</p>
                            <p class="card-text">Kategoria: <?= $service['category_name'] ?></p>
                            <p class="card-text"><?= $service['description'] ?></p>
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal<?php echo $service['id']; ?>">Edytuj</button>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Czy na pewno chcesz usunąć tę usługę?');">
                                <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                <button type="submit" name="delete_service" class="btn btn-danger btn-sm">Usuń</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                
                <div class="modal fade" id="editModal<?php echo $service['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $service['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel<?php echo $service['id']; ?>">Edytuj Usługę</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                    <div class="form-group">
                                        <label for="edit_name<?php echo $service['id']; ?>">Nazwa usługi:</label>
                                        <input type="text" class="form-control" id="edit_name<?php echo $service['id']; ?>" name="name" value="<?php echo htmlspecialchars($service['name']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_description<?php echo $service['id']; ?>">Opis:</label>
                                        <textarea class="form-control" id="edit_description<?php echo $service['id']; ?>" name="description" required><?php echo htmlspecialchars($service['description']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_price<?php echo $service['id']; ?>">Cena:</label>
                                        <input type="number" class="form-control" id="edit_price<?php echo $service['id']; ?>" name="price" step="0.01" value="<?php echo $service['price']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_category_id<?php echo $service['id']; ?>">Kategoria:</label>
                                        <select class="form-control" id="edit_category_id<?php echo $service['id']; ?>" name="category_id" required>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $service['category_id']) ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                                    <button type="submit" name="update_service" class="btn btn-primary">Zapisz zmiany</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
