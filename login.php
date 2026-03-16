<?php
session_start();
include "cfg/dbconnect.php"; 
$email = $err_msg = "";
$remember = "";

if (isset($_POST['submit'])) {    
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // 1. Use Prepared Statements to prevent SQL Injection
    $stmt = mysqli_prepare($conn, "SELECT name, email, password FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // 2. MD5 check (Consider upgrading to password_verify() later)
        if (password_verify($password, $row['password'])) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            
            if (isset($_POST['remember'])) {  
                $remember = $_POST['remember'];
                setcookie("remember_email", $email, time() + 3600*24*365);
                setcookie("remember", $remember, time() + 3600*24*365);
            } else {
                setcookie("remember_email", "", time() - 36000);
                setcookie("remember", "", time() - 3600);
            }
            
            header("location:index.php");
            // 3. Always exit after a redirect
            exit(); 
        } else {
            $err_msg = "Incorrect Email Id/Password";
        }
    } else {
        $err_msg = "Incorrect Email Id/Password";
    }
    mysqli_stmt_close($stmt);
}
include 'header.php';
?>	
<form class="form-1" action="login.php" method="post">
    <h2>Login Form</h2>
    
    <?php if ($err_msg != "") { ?>
        <p class="err-msg"><?php echo $err_msg; ?></p>
    <?php } ?>
    
    <div class="col-md-12 form-group">
        <label>Email Id</label>
        <input type="text" class="form-control" name="email" id="email" value="<?php if(!empty($email)) { echo htmlspecialchars($email); } elseif (isset($_COOKIE["remember_email"])) { echo htmlspecialchars($_COOKIE["remember_email"]); } ?>" placeholder="Enter your Email Id" required>
    </div>
    <div class="col-md-12 form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
    </div>
    <div class="col-md-12 form-group">
        <input type="checkbox" name="remember" class="check" <?php if(!empty($remember) || isset($_COOKIE["remember"])) { echo "checked"; } ?>> Remember Me
    </div>
    <div class="col-md-12 form-group text-right">
        <button type="submit" class="btn btn-primary" name="submit">Login</button>&nbsp;&nbsp;
        <a href="index.php" class="btn btn-danger" name="cancel">Cancel</a>
    </div>
</form>
</body>
</html>