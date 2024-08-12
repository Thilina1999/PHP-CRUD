<?php
require 'db_connection.php';

$id = "";
$name = "";
$email = "";
$phone = "";
$address = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    debug_to_console("Test");

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "INSERT INTO clients (name, email, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $phone, $address);

    do {
        try {
            if ($stmt->execute()) {
                echo "<script>alert('Client added successfully!');</script>";
                $name = "";
                $email = "";
                $phone = "";
                $address = "";
                $error = "";
            } else {
                $error = "Error adding client.";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $error = "Duplicate entry for email: $email";
            } else {
                $error = "Database error: " . $e->getMessage();
            }
        }

    } while (false);

}

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Clients</h2>
            <button class="btn btn-success" data-toggle="modal" data-target="#addClientModal">Add Client</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "
                    <tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['address']}</td>
                        <td>
                        <a href='/PHP-CRUD/update.php?id=$row[id]'>
                            <button class='btn btn-primary btn-sm'>Edit</button>
                        </a>
                            
                            <button class='btn btn-danger btn-sm'>Delete</button>
                        </td>
                    </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="addClientModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel">Add Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form id="addClientForm" method="POST" action="">
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
                        <button type="submit" class="btn btn-primary">Add Client</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>