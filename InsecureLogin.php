<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once './Models/Student.php'; 
include_once 'Functions.php';

error_reporting(E_ALL);
extract($_POST);

if (isset($submit)) {
    try {
        if (isset($studentId) && isset($password)) { 
            $student = getInsecureStudent($studentId, $password);
            
            if ($student != null) {
                $_SESSION['student'] = $student;
                header("Location: Profile.php");
                exit();
            } else {
                $errors['login'] = 'Incorrect User ID and Password combination!';
            }
        }
    } catch (Exception $ex) {
        die('Error: ' . $ex->getMessage());
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

    /* Style the login box */
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

        <h1 class="text-center">Log In</h1>
        <p class="text-center">
            You need to <a href="SecureNewUser.php">sign up</a> if you are a new user
            or <a href="InsecureNewUser.php">sign up (insecure)</a> for testing.
        </p>

        <form method="post">

            <!-- Student ID field -->
            <div class="form-group">
                <label for="studentId">Student ID:</label>
                <input
                    type="text"
                    name="studentId"
                    id="studentId"
                    class="form-control"
                    value="<?php
                        if (isset($_SESSION['studentId'])) { 
                            echo htmlspecialchars($_SESSION['studentId']);
                        } else if (isset($studentId)) {
                            echo htmlspecialchars($studentId);
                        }
                    ?>"
                >
                <small class="text-danger">
                    <?php if (isset($errors['studentId'])) { echo $errors['studentId']; } ?>
                </small>
            </div>

            <!-- Password field -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control"
                    value="<?php
                        if (isset($_SESSION['password'])) { 
                            echo htmlspecialchars(trim($_SESSION['password']));
                        } else if (isset($password)) {
                            echo htmlspecialchars($password);
                        }
                    ?>"
                >
                <small class="text-danger">
                    <?php if (isset($errors['password'])) { echo $errors['password']; } ?>
                </small>
            </div>

            <!-- Login error message -->
            <?php if (isset($errors['login'])): ?>
                <div class="form-group text-center">
                    <small class="text-danger"><?php echo $errors['login']; ?></small>
                </div>
            <?php endif; ?>

            <!-- Buttons -->
            <div class="form-group text-center mt-4">
                <button type="submit" id="submit" class="btn btn-success" name="submit">Submit</button>
                <button type="reset" id="clear" class="btn btn-secondary">Clear</button>
            </div>

        </form>

    </div>
</div>

<?php include("./include/Footer.php"); ?>
