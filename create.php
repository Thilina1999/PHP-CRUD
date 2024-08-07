<?php
require 'db_connection.php';

$id = "";
$name = "";
$email = "";
$phone = "";
$address = "";
$error = "";
$succese = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if ($id) {
        $sql = "UPDATE clients SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);

        try {
            if ($stmt->execute()) {
                
            } else {
                $error = "Error Updating client.";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $error = "Duplicate entry for email: $email";
            } else {
                $error = "Database error: " . $e->getMessage();
            }
        }

        $id = '';
        $name = '';
        $email = '';
        $phone = '';
        $address = '';

        $succese =  "Update Succese";
        header("Location: index.php");
        exit;
    }


}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM clients WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    try {
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $name = $row['name'];
            $email = $row['email'];
            $phone = $row['phone'];
            $address = $row['address'];
        } else {
            $error = "Client not found.";
        }
    } catch (mysqli_sql_exception $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}

$sql = 'SELECT * FROM clients';
$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients Table</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Clients</h2>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo $succese; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form id="clientForm" method="POST" action="">
            <input type="hidden" id="clientId" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <label for="clientName">Name</label>
                <input type="text" class="form-control" id="clientName" name="name"
                    value="<?php echo $name; ?>" required>
            </div>
            <div class="form-group">
                <label for="clientEmail">Email</label>
                <input type="email" class="form-control" id="clientEmail" name="email"
                    value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="clientPhone">Phone</label>
                <input type="text" class="form-control" id="clientPhone" name="phone"
                    value="<?php echo $phone; ?>">
            </div>
            <div class="form-group">
                <label for="clientAddress">Address</label>
                <input type="text" class="form-control" id="clientAddress" name="address"
                    value="<?php echo $address; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Client</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>