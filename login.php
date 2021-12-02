<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
}
 
// Include config file
require_once "db_config/database.php";
 
// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;                            
                            
                            // Redirect user to welcome page
                            header("location: dashboard.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else{
                    // email doesn't exist, display a generic error message
                    $login_err = "Invalid email or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/logo.jpeg" type="image/x-icon">
    <link rel="stylesheet" href="/indexcss/css/index.css">
    <link rel="stylesheet" href="indexcss/css/stylely.css">
    <link rel="stylesheet" href="css/css/style.min.css">
    <title>TravisLiquor | Login</title>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-blue  justify-content-between">
    <a class="navbar-brand" href="homepage.html">TravisLiquor</a>
    <form class="form-inline my-1">
      <a href="login.html" class="btn btn-outline-primary btn-sm my-0" type="submit">Signin</a>
      <a href="register.html" class="btn btn-outline-danger btn-sm my-0" type="submit">Signup</a>
    </form>
  </nav>
  <!-- Navbar -->

    <section class="vh-100" style="margin-bottom: 20px;" >
        <div class="container py-5 h-100">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-10">
              <div class="card" style="border-radius: 1rem;">
                <div class="row g-0">
                  <div class="col-md-6 col-lg-5 d-none d-md-block">
                    <img
                      src="images/acohol1.jpg"
                      alt="login form"
                      class="img-fluid" style="border-radius: 1rem 0 0 1rem;"
                    />
                  </div>
                  <div class="col-md-6 col-lg-7 d-flex align-items-center">
                    <div class="card-body p-4 p-lg-5 text-black">
      
                      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      
                        <div class="d-flex align-items-center mb-3 pb-1">
                          <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                          <span class="h1 fw-bold mb-0">TravisLiquor</span>
                        </div>
      
                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>
                        <?php 
                          if(!empty($login_err)){
                              echo '<div class="alert alert-danger">' . $login_err . '</div>';
                          }        
                        ?>

                        <div class="form-outline mb-4">
                          <input type="email" id="form2Example17" class="form-control form-control-lg"  <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" />
                          <span class="invalid-feedback"><?php echo $email_err; ?></span>
                          <label class="form-label" for="form2Example17">Email address</label>
                        </div>
      
                        <div class="form-outline mb-4">
                          <input type="password" id="form2Example27" class="form-control form-control-lg " <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" />
                          <span class="invalid-feedback"><?php echo $password_err; ?></span>
                          <label class="form-label" for="form2Example27">Password</label>
                        </div>
      
                        <div class="pt-1 mb-4">
                          <button  class="btn btn-dark btn-lg btn-block" type="submit">Login</button>
                        </div>
      
                        <a class="small text-muted" href="#!">Forgot password?</a>
                        <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="register.php" style="color: #393f81;">Register here</a></p>
                        <a href="#!" class="small text-muted">Terms of use.</a>
                        <a href="#!" class="small text-muted">Privacy policy</a>
                      </form>
                     
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      
      <section class="">
        <footer class="bg-dark text-white text-center text-lg-start">
          <!-- Grid container -->
          <div class="container p-4">
            <!--Grid row-->
            <div class="row">
              <!--Grid column-->
              <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">Opening hours</h5>
        
                <p>
                  Wed to Fri: 10pm – 6am<br/>
                  Saturday: 8pm – 6am<br/>
                  Sunday: 9pm – 3am
                </p>
              </div>
              <!--Grid column-->
        
              <!--Grid column-->
              <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Contacts</h5>
        
                <ul class="list-unstyled mb-0">
                  <li>
                    <a href="#!" class="text-white">Phone: +44(0)7565257936</a>
                  </li>
                  <li>
                    <a href="#!" class="text-white">Office address: Waterfront Park, Edinburgh
                      EH5 1FZ</a>
                  </li>
                  <li>
                    <a href="#!" class="text-white">Email:  40500521@live.napier.co.uk</a>
                  </li>
                  
                </ul>
              </div>
              <!--Grid column-->
      
            </div>
            <!--Grid row-->
          </div>
          <!-- Grid container -->
        
          <!-- Copyright -->
          <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2021 Copyright:
            <a class="text-white" href="https://google.com/">TravisLiquor.com</a>
          </div>
          <!-- Copyright -->
        </footer>
        <!-- Footer -->
      </section>

</body>
</html>