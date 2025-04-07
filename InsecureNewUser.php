<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once './Models/Student.php'; 
include_once 'Functions.php';

extract($_POST);

if (isset($submit)) {
    try {
        // No validations for insecure user flow
        if (empty($errors)) {
            $student = addInsecureStudent($studentId, $name, $phoneNumber, $password, $email);
            header("Location: InsecureLogin.php");
            exit();
        }
    } catch (Exception $ex) {
        die('System currently not available, try again later');
    }
}

include("./include/Header.php"); 
?>

<style>
    /* Center the form vertically and horizontally */
    .login-container {
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Style the sign-up box */
    .login-box {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e0e0; /* Light grey border */
        width: 100%;
        max-width: 500px;
    }

    .login-box h1 {
        margin-bottom: 20px;
    }

    .login-box p {
        margin-bottom: 20px;
    }
</style>

<div class="container login-container">
    <div class="login-box">

        <h1 class="text-center">Sign Up (Insecure)</h1>
        <p class="text-center">All fields are required (No validation applied)</p>

        <form method="post">

            <!-- Student ID -->
            <div class="form-group">
                <label for="studentId">Student ID:</label>
                <input type="text" name="studentId" id="studentId" class="form-control"
                    value="<?php echo isset($studentId) ? htmlspecialchars($studentId) : ''; ?>">
            </div>

            <!-- Name -->
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="phoneNumber">Phone Number:</label>
                <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" placeholder="xxx-xxx-xxxx"
                    value="<?php echo isset($phoneNumber) ? htmlspecialchars($phoneNumber) : ''; ?>">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="xxx@test.com"
                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control"
                    value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="passwordConfirm">Confirm Password:</label>
                <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control"
                    value="<?php echo isset($passwordConfirm) ? htmlspecialchars($passwordConfirm) : ''; ?>">
            </div>

            <!-- Buttons -->
            <div class="form-group text-center mt-4">
                <button type="submit" id="submit" class="btn btn-success" name="submit">Submit</button>
                <button type="reset" id="clear" class="btn btn-secondary">Clear</button>
            </div>

        </form>

    </div>
</div>

<?php include("./include/Footer.php"); ?>
