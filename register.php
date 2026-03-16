<?php
session_start();
include "cfg/dbconnect.php"; 

$name = $email = $password = $err_msg = $success_msg = "";

if (isset($_POST['submit'])) {    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // 1. Check if the email already exists in the database
    $check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $err_msg = "An account with this email already exists.";
    } else {
        // 2. Hash the password securely using Bcrypt
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // 3. Insert the new user into the database
        $insert_stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($insert_stmt, "sss", $name, $email, $hashed_password);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            $success_msg = "Registration successful! You can now login.";
            // Clear form fields after successful registration
            $name = $email = ""; 
        } else {
            $err_msg = "Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($insert_stmt);
    }
    mysqli_stmt_close($check_stmt);
}
include 'header.php';
?>	

<form class="form-1" action="register.php" method="post">
    <h2>Register</h2>
    
    <?php if ($err_msg != "") { ?>
        <p class="err-msg text-danger"><?php echo $err_msg; ?></p>
    <?php } ?>
    
    <?php if ($success_msg != "") { ?>
        <p class="success-msg text-success"><?php echo $success_msg; ?></p>
    <?php } ?>
    
    <div class="col-md-12 form-group">
        <label>Full Name</label>
        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Enter your full name" required>
    </div>
    <div class="col-md-12 form-group">
        <label>Email Id</label>
        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter your Email Id" required>
    </div>
    <div class="col-md-12 form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="password" placeholder="Create a Password" required>
    </div>
    <div class="col-md-12 form-group text-right">
        <button type="submit" class="btn btn-success" name="submit">Register</button>&nbsp;&nbsp;
        <a href="login.php" class="btn btn-primary">Go to Login</a>
    </div>
</form>
</body>
</html>