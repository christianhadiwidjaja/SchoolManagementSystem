<?php

session_start();

if(!isset($_SESSION["loggedin"])){
    header("location: login.php");
    return;
}
if($_SESSION["role"] != 0){
    header("location: login.php");
    return;

}

require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = $int= $unt= $name = $nip = "";
$username_err = $password_err = $confirm_password_err = $int_err= $unt_err= $name_err= $nip_err = "" ;



// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // $conn = new mysqli("localhost","root", "", "kckrctb");
    // $sql = "SELECT * from `msunitkerja`";
    // $result2 = $conn->query($sql);
                    
    // if ($result2->num_rows > 0) {
    //     while ($row123 = $result2->fetch_assoc()) {
    //                 //data perusahaan
    //         $id12 = $row123['id'];
    //         $role12 = $row123['role'];
    //         $name12= $row123['name'];

    //         if($row123['id']==$_POST['unt']){
    //             $role = $role12;
    //         }
    //     }                 
    // }
    // $conn->close();
    $name = $_POST['name'];
    $unt = $_POST['unt'];

    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter your name.";     
    }
    else{
        $name = trim($_POST["name"]);
    }

    //validate NIP
    // if(empty(trim($_POST["nip"]))){
    //     $nip_err = "Please enter NIP.";
    // }
    // else{
    //     $nip = trim($_POST["nip"]);
    // }

    //Validate Unit Kerja
    if(empty(trim($_POST["unt"]))){
        $unt_err = "Please enter Role.";
    }


    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter an username.";
    }
    else if(strlen(trim($_POST["username"])) < 6){
        $username_err = "Username must have at least 6 character";
    } 
    else{
        // Prepare a select statement
        $sql = "SELECT id FROM msuser where username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($unt_err) && empty($nip_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO msuser (username, password,role,name) VALUES (?, ?,'$unt','$name')";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                echo "<script type='text/javascript'>alert('User has been created!');window.location.href='register.php';</script>";
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="shortcut icon" href="../favicon.ico">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/css.css" rel="stylesheet">
    <script src="css/js.js"></script>
</style>
</head>
<body>
    <?php include('/nav/nav-sa.php');?>
    <br><br>
    <!--Body-->
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <div class="wrapper">
                <h1>Register</h1>
                <p>Please fill this form to create an account.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                        <label>Name</label>
                        <input   style="width:420px" type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                        <span class="help-block"><?php echo $name_err; ?></span>
                    </div>

                    <!-- <div class="form-group <?php echo (!empty($nip_err)) ? 'has-error' : ''; ?>">
                        <label>NIP</label>
                        <input   style="width:420px" type="text" name="nip" class="form-control" value="<?php echo $nip; ?>">
                        <span class="help-block"><?php echo $nip_err; ?></span>
                    </div> -->
                
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label>Username</label>
                        <input   style="width:420px" type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>    
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label>Password</label>
                        <input   style="width:420px" type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label>Confirm Password</label>
                        <input   style="width:420px" type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div> 

                    <div class="form-group">
                        <label>Role</label>
                        <select id="subdiv123" name="unt" class="form-select" style="width:420px" required>
                            <option value="">Select</option>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="parent">Parent</option>
                        </select>
                    </div> 

                    <br>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <input type="reset" class="btn btn-dark" value="Reset">
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-4"></div>  
    </div>  
</body>
</html>