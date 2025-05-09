<?php
session_start();
require '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) { 
    die("User ID not found in session.");
}

$userId = $_SESSION['user_id']; 

$sql = "SELECT name, surname, email, ID_address FROM user WHERE user_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

$addressId = $user['ID_address'];

if ($addressId) {
    $sql = "SELECT * FROM address WHERE address_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $addressId);
    $stmt->execute();
    $result = $stmt->get_result();
    $address = $result->fetch_assoc();

    if (!$address) {
        die("Address not found.");
    }
} else {
    $address = [
        'country' => '',
        'city' => '',
        'street' => '',
        'house' => '',
        'apartment' => '',
        'postal_code' => ''
    ];
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

            <div class="profile-form">
            <div class="delete-address" style="margin: 10px 0;">
                <a href="delete-address.php" class="text-danger" style="font-weight: bold;" onclick="return confirm('Vai tiešām vēlaties dzēst adresi?');">Dzēst adresi</a>
            </div>
                <form action="update-address.php" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Valsts</label>
                                <input type="text" id="country" name="country" class="form-control" placeholder="Valsts" value="<?php echo htmlspecialchars($address['country']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">Pilsēta</label>
                                <input type="text" id="city" name="city" class="form-control" placeholder="Pilsēta" value="<?php echo htmlspecialchars($address['city']); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="street">Iela</label>
                                <input type="text" id="street" name="street" class="form-control" placeholder="Iela" value="<?php echo htmlspecialchars($address['street']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="house">Mājas numurs</label>
                                <input type="text" id="house" name="house" class="form-control" placeholder="Mājas numurs" value="<?php echo htmlspecialchars($address['house']); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="apartment">Dzīvokļa numurs</label>
                        <input type="text" id="apartment" name="apartment" class="form-control" placeholder="Dzīvokļa numurs" value="<?php echo htmlspecialchars($address['apartment']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="postal_code">Pasta indekss</label>
                        <input type="text" id="postal_code" name="postal_code" class="form-control" placeholder="Pasta indekss" value="<?php echo htmlspecialchars($address['postal_code']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-main">Saglabāt</button>
                </form>
            </div>
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