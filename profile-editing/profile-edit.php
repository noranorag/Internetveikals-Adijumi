<?php
session_start();
require '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) { 
    die("User ID not found in session.");
}
$userId = $_SESSION['user_id']; 

$sql = "SELECT name, surname, phone, email FROM user WHERE user_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die('Lietotājs nav atrasts!');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internetveikals</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.css">
    <link rel="stylesheet" href="../styles.css">
    <script src="../scripts.js" defer></script>
</head>
<body>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-0 w-100 text-center" role="alert" style="z-index: 1050;">
            <strong><?php echo htmlspecialchars($_GET['success']); ?></strong>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-0 w-100 text-center" role="alert" style="z-index: 1050;">
            <strong><?php echo htmlspecialchars($_GET['error']); ?></strong>
        </div>
    <?php endif; ?>

    <div class="announcement" id="announcement"></div>

    <?php include '../files/navbar.php'; ?>

    <div class="profile-container">
        <div class="profile-sidebar">
            <h3 class="sidebar-heading">Profila iestatījumi</h3>
            <ul class="sidebar-links">
                <li><a href="profile-edit.php">Profila rediģēšana</a></li>
                <li><a href="address-edit.php">Adreses rediģēšana</a></li>
                <li><a href="password-change.php">Paroles maiņa</a></li>
            </ul>
        </div>
        <div class="profile-content">
            <div class="user-info">
                <i class="fas fa-user-circle user-icon"></i>
                <div class="user-details">
                    <h2><?php echo htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></h2>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>

            <form class="profile-form" action="update-profile.php" method="POST">
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="name">Vārds</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Vārds" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="surname">Uzvārds</label>
                        <input type="text" id="surname" name="surname" class="form-control" placeholder="Uzvārds" value="<?php echo htmlspecialchars($user['surname']); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Telefons</label>
                    <input type="text" id="phone" name="phone" class="form-control" placeholder="Telefons" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">E-pasts</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="E-pasts" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <button type="submit" class="btn btn-main btn-small">Saglabāt</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 150); 
            });
        }, 3000);
    </script>
</body>
</html>