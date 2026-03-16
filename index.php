<?php 
include 'header.php';
?>

<h1>>_ SYSTEM_ROOT<span class="cursor"></span></h1>

<?php if (isset($_SESSION['email'])) { 
    echo "<h2>Welcome user_".$_SESSION['name'].".</h2>";
    echo "<p>Access granted for node: ".$_SESSION['email']."</p>"; 
?>
    <br>
    <h3><a href="logout.php">[ Execute Logout ]</a></h3>

<?php } else { ?>
    
    <h2>Authentication Required</h2>
    <br>
    <p>
        <a href="login.php" class="btn btn-primary">Login</a> 
        &nbsp;&nbsp;&nbsp;
        <a href="register.php" class="btn btn-success">Register</a>
    </p>

<?php } ?>

</body>
</html>