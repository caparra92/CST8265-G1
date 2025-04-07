<?php
// Ensure session is started securely
if (session_status() === PHP_SESSION_NONE) {
    // Set session cookie parameters for security
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => $cookieParams['lifetime'],
        'path' => $cookieParams['path'],
        'domain' => $cookieParams['domain'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

include_once './Models/Student.php'; 
include_once 'Functions.php';
include_once 'Validations.php';

// Generate CSRF token for the form
$csrf_token = generateCSRFToken();

// Initialize errors array
$errors = array();
$formData = array();

// Process form submission
if (isset($_POST['submit'])) {
    try {
        $formData = [
            'csrf_token' => $_POST['csrf_token'] ?? '',
            'studentId' => sanitizeInput($_POST['studentId'] ?? ''),
            'name' => sanitizeInput($_POST['name'] ?? ''),
            'phoneNumber' => sanitizeInput($_POST['phoneNumber'] ?? ''),
            'email' => sanitizeInput($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'passwordConfirm' => $_POST['passwordConfirm'] ?? ''
        ];

        if (validateFormInputs($formData, $errors)) {
            $result = addSecureStudent(
                $formData['studentId'], 
                $formData['name'], 
                $formData['phoneNumber'], 
                $formData['password'], 
                $formData['email']
            );

            if ($result) {
                session_regenerate_id(true);
                $_SESSION['registration_success'] = true;
                header("Location: SecureLogin.php");
                exit();
            } else {
                $errors['form'] = 'Registration failed. Please try again.';
            }
        }
    } catch (Exception $ex) {
        error_log("Error in registration: " . $ex->getMessage());
        $errors['form'] = 'System currently not available, please try again later.';
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
        border: 1px solid #e0e0e0;
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

        <h1 class="text-center">Sign Up (Secure)</h1>
        <p class="text-center">All fields are required</p>

        <form method="post" autocomplete="off">
            <!-- CSRF Protection -->
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <!-- Student ID -->
            <div class="form-group">
                <label for="studentId">Student ID:</label>
                <input type="text" name="studentId" id="studentId" class="form-control"
                    value="<?php echo isset($formData['studentId']) ? htmlspecialchars($formData['studentId']) : ''; ?>"
                    required
                    pattern="[A-Za-z0-9]{5,20}"
                    title="Student ID must be 5-20 alphanumeric characters">
                <?php if (isset($errors['studentId'])): ?>
                    <small class="text-danger"><?php echo htmlspecialchars($errors['studentId']); ?></small>
                <?php endif; ?>
            </div>

            <!-- Name -->
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="<?php echo isset($formData['name']) ? htmlspecialchars($formData['name']) : ''; ?>"
                    required
                    pattern="[A-Za-z\s\-']{2,100}"
                    title="Name must be 2-100 characters and can only contain letters, spaces, hyphens and apostrophes">
                <?php if (isset($errors['name'])): ?>
                    <small class="text-danger"><?php echo htmlspecialchars($errors['name']); ?></small>
                <?php endif; ?>
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="phoneNumber">Phone Number:</label>
                <input type="tel" name="phoneNumber" id="phoneNumber" class="form-control"
                    placeholder="XXX-XXX-XXXX"
                    value="<?php echo isset($formData['phoneNumber']) ? htmlspecialchars($formData['phoneNumber']) : ''; ?>"
                    required
                    pattern="[1-9][0-9]{2}-[1-9][0-9]{2}-[0-9]{4}"
                    title="Phone number format: XXX-XXX-XXXX">
                <?php if (isset($errors['phoneNumber'])): ?>
                    <small class="text-danger"><?php echo htmlspecialchars($errors['phoneNumber']); ?></small>
                <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control"
                    placeholder="your.email@example.com"
                    value="<?php echo isset($formData['email']) ? htmlspecialchars($formData['email']) : ''; ?>"
                    required>
                <?php if (isset($errors['email'])): ?>
                    <small class="text-danger"><?php echo htmlspecialchars($errors['email']); ?></small>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control"
                    required minlength="8" autocomplete="new-password">
                <?php if (isset($errors['password'])): ?>
                    <small class="text-danger"><?php echo htmlspecialchars($errors['password']); ?></small>
                <?php endif; ?>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="passwordConfirm">Confirm Password:</label>
                <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control"
                    required minlength="8" autocomplete="new-password">
                <?php if (isset($errors['passwordConfirm'])): ?>
                    <small class="text-danger"><?php echo htmlspecialchars($errors['passwordConfirm']); ?></small>
                <?php endif; ?>
            </div>

            <!-- Form error -->
            <?php if (isset($errors['form'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($errors['form']); ?></div>
            <?php endif; ?>

            <!-- Buttons -->
            <div class="form-group text-center mt-4">
                <button type="submit" id="submit" class="btn btn-success" name="submit">Submit</button>
                <button type="reset" id="clear" class="btn btn-secondary">Clear</button>
            </div>

            <div class="mt-3">
                <p class="text-muted"><small>Password must contain at least 8 characters including uppercase, lowercase, numbers, and special characters.</small></p>
            </div>

        </form>

    </div>
</div>

<?php include("./include/Footer.php"); ?>
