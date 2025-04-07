<?php
// Ensure session is started securely
if (session_status() === PHP_SESSION_NONE) {
    // Set session cookie parameters for security
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => $cookieParams['lifetime'],
        'path' => $cookieParams['path'],
        'domain' => $cookieParams['domain'],
        'secure' => true,   // Only transmit over HTTPS
        'httponly' => true, // Not accessible via JavaScript
        'samesite' => 'Lax' // Protect against CSRF
    ]);
    session_start();
}

// Only enable these in development, disable in production
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(0);

include_once './Models/Student.php'; 
include_once 'Functions.php';
include_once 'Validations.php';

// Generate CSRF token for the form
$csrf_token = generateCSRFToken();

// Initialize errors array
$errors = array();
$formData = array();

// Process form submission
if(isset($_POST['submit'])) {
    try {
        // Collect and sanitize all form data
        $formData = [
            'csrf_token' => $_POST['csrf_token'] ?? '',
            'studentId' => sanitizeInput($_POST['studentId'] ?? ''),
            'name' => sanitizeInput($_POST['name'] ?? ''),
            'phoneNumber' => sanitizeInput($_POST['phoneNumber'] ?? ''),
            'email' => sanitizeInput($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '', // Don't sanitize passwords to allow special chars
            'passwordConfirm' => $_POST['passwordConfirm'] ?? ''
        ];
        
        // Validate all inputs at once using our new validation function
        if(validateFormInputs($formData, $errors)) {
            // Only proceed if validation passed
            $result = addSecureStudent(
                $formData['studentId'], 
                $formData['name'], 
                $formData['phoneNumber'], 
                $formData['password'], 
                $formData['email']
            );
            
            if($result) {
                // Clear form data after successful registration
                session_regenerate_id(true); // Regenerate session ID for security
                $_SESSION['registration_success'] = true;
                header("Location: SecureLogin.php");
                exit();
            } else {
                $errors['form'] = 'Registration failed. Please try again.';
            }
        }
    }
    catch(Exception $ex) {
        // Log the actual error for administrators
        error_log("Error in registration: " . $ex->getMessage());
        // Show generic error to users
        $errors['form'] = 'System currently not available, please try again later.';
    }
}

include("./include/Header.php"); 
?>
<div class="container">
    <div class="row">
        <h1 class="text-left">Sign Up</h1>
        <p>All fields are required</p>
        <div class="col-md-8">
            <form method="post" class="form row" autocomplete="off">
                <!-- CSRF Protection -->
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="studentId">Student ID:</label>
                    <div class="col-sm-7">
                        <input
                        class="form-control" 
                        type="text" 
                        name="studentId" 
                        id="studentId"
                        value="<?php echo isset($formData['studentId']) ? htmlspecialchars($formData['studentId']) : ''; ?>"
                        required
                        pattern="[A-Za-z0-9]{5,20}"
                        title="Student ID must be 5-20 alphanumeric characters"
                        >
                    </div>
                    <span class="text-danger col-sm-3">
                        <?php if (isset($errors['studentId'])) {echo htmlspecialchars($errors['studentId']);} ?>
                    </span>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="name">Name</label>
                    <div class="col-sm-7">
                        <input
                        class="form-control" 
                        type="text" 
                        name="name" 
                        id="name"
                        value="<?php echo isset($formData['name']) ? htmlspecialchars($formData['name']) : ''; ?>"
                        required
                        pattern="[A-Za-z\s\-']{2,100}"
                        title="Name must be 2-100 characters and can only contain letters, spaces, hyphens and apostrophes"
                        >
                    </div>
                    <span class="text-danger col-sm-3">
                        <?php if (isset($errors['name'])) {echo htmlspecialchars($errors['name']);} ?>
                    </span>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="phoneNumber">Phone Number</label>
                    <div class="col-sm-7">
                        <input
                        class="form-control" 
                        type="tel" 
                        name="phoneNumber" 
                        id="phoneNumber" 
                        placeholder="XXX-XXX-XXXX"
                        value="<?php echo isset($formData['phoneNumber']) ? htmlspecialchars($formData['phoneNumber']) : ''; ?>"
                        required
                        pattern="[1-9][0-9]{2}-[1-9][0-9]{2}-[0-9]{4}"
                        title="Phone number format: XXX-XXX-XXXX"
                        >
                    </div>
                    <span class="text-danger col-sm-3">
                        <?php if (isset($errors['phoneNumber'])) {echo htmlspecialchars($errors['phoneNumber']);} ?>
                    </span>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="email">Email</label>
                    <div class="col-sm-7">
                        <input
                        class="form-control" 
                        type="email" 
                        name="email" 
                        id="email" 
                        placeholder="your.email@example.com"
                        value="<?php echo isset($formData['email']) ? htmlspecialchars($formData['email']) : ''; ?>"
                        required
                        >
                    </div>
                    <span class="text-danger col-sm-3">
                        <?php if (isset($errors['email'])) {echo htmlspecialchars($errors['email']);} ?>
                    </span>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="password">Password</label>
                    <div class="col-sm-7">
                        <input
                        class="form-control" 
                        type="password" 
                        name="password" 
                        id="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        >
                    </div>
                    <span class="text-danger col-sm-3">
                        <?php if (isset($errors['password'])) {echo htmlspecialchars($errors['password']);} ?>
                    </span>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="passwordConfirm">Confirm Password</label>
                    <div class="col-sm-7">
                        <input
                        class="form-control" 
                        type="password" 
                        name="passwordConfirm" 
                        id="passwordConfirm"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        >
                    </div>
                    <span class="text-danger col-sm-3">
                        <?php if (isset($errors['passwordConfirm'])) {echo htmlspecialchars($errors['passwordConfirm']);} ?>
                    </span>
                </div>
                <?php if (isset($errors['form'])): ?>
                <div class="alert alert-danger col-sm-9 mb-3"><?php echo htmlspecialchars($errors['form']); ?></div>
                <?php endif; ?>
                
                <button type="submit" id="submit" class="btn btn-success mr-2" name="submit">Submit</button>
                <button type="reset" id="clear" class="btn btn-secondary" name="clear">Clear</button>
                <div class="mt-3 col-sm-9">
                    <p class="text-muted"><small>Password must contain at least 8 characters including uppercase, lowercase, numbers, and special characters.</small></p>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include("./include/Footer.php"); ?>