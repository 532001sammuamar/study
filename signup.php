<?php

require_once "config.php";


function username_exists($username, $conn) {
    $query =  $conn->prepare("SELECT COUNT(*) AS cnt FROM login_form WHERE username = ?");
    $query->bind_param("s", $username);
    $query->bind_result($result);
    $query->execute();
    $query->fetch();
    
    $count = $result;

    $query->close();

    $exists = ($count !== 0);

    return $exists;
}


function extract_and_validate_user_input($conn) {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        return NULL;
    }

    else {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $confirm_password = trim($_POST["confirm_password"]);

        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match.");
        }

        if (username_exists($username, $conn)) {
            throw new Exception("Username already taken.");
        }
    }

    return array("username"=>$username, "password"=>$password);
}


function create_user($username, $password, $conn) {
    $hashed_and_salted_password =  password_hash($password, PASSWORD_DEFAULT);

    $query =  $conn->prepare("INSERT INTO login_form (username, password) VALUES (?, ?)");
    $query->bind_param("ss", $username, $hashed_and_salted_password);
    $query->execute();
    assert($query->affected_rows === 1);  // TODO: Use better error handling.
    $query->close();
}


$error_message = "";
try {
    $creds = extract_and_validate_user_input($mysqli);
    if (!is_null($creds)) {
        create_user($creds["username"], $creds["password"], $mysqli);
        header("Location: login.php");
    }
} catch (Exception $e) {
    $error_message = $e->getMessage();
}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <?php
            if ($error_message !== "") {
                echo "<p style='color:red'> " . $error_message . " </p>";
            }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="">
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="">
            </div>
            <div>
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
