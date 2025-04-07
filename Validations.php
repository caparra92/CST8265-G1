<?php
include_once 'Functions.php';

$errors = array();

/**
 * Sanitize user input to prevent XSS attacks
 * @param string $input User input to sanitize
 * @return string Sanitized input
 */
function sanitizeInput($input) {
    // Remove leading/trailing whitespace
    $input = trim($input);
    // Convert special characters to HTML entities
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

/**
 * Validate email format
 * @param string $email Email to validate
 * @param array &$errors Reference to errors array
 */
function ValidateEmail($email, &$errors) {
    if(empty($email)) {
        $errors['email'] = 'Email cannot be empty';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
}


function ValidateStudentId($studentId, &$errors, $isSignUp) {
    // Sanitize input
    $studentId = sanitizeInput($studentId);
    
    if (empty($studentId)) {
        $errors['studentId'] = 'Student Id cannot be empty!';
    } elseif (!preg_match('/^[A-Za-z0-9]{5,20}$/', $studentId)) {
        $errors['studentId'] = 'Student ID must be 5-20 alphanumeric characters';
    } elseif ($isSignUp) {
        if(getSecureStudentId($studentId)) {
            $errors['studentId'] = 'Student Id already exists!';
        }
    }
} 

function ValidateName($name, &$errors) {
    // Sanitize input
    $name = sanitizeInput($name);
    
    if(empty($name)) {
        $errors['name'] = 'Name cannot be empty';
    } elseif (strlen($name) < 2 || strlen($name) > 100) {
        $errors['name'] = 'Name must be between 2 and 100 characters';
    } elseif (!preg_match('/^[A-Za-z\s\-\']+$/', $name)) {
        $errors['name'] = 'Name can only contain letters, spaces, hyphens and apostrophes';
    }
}



function ValidatePassword($password, &$errors, $isSignUp) {
    if(empty($password)) {
        $errors['password'] = 'Password cannot be empty';
    }
    
    if($isSignUp) {
        // Stronger password requirements
        if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?\":{}|<>]).{8,}$/", $password)) {
            $errors['password'] = 'Password must contain: At least 8 characters, 1 uppercase letter, ' . 
                                 '1 lowercase letter, 1 number, and 1 special character';
        }
        
        // Check for common passwords (this is a simplified check - in production, use a larger list)
        $commonPasswords = ['password', 'Password', 'password123', 'admin', 'admin123', '123456', 'qwerty'];
        if (in_array(strtolower($password), $commonPasswords)) {
            $errors['password'] = 'Please use a less common password';
        }
    }
}

function ValidatePasswordConfirm($password, $passwordConfirm, &$errors) {
    if(empty($passwordConfirm)) {
        $errors['passwordConfirm'] = 'Please confirm your password';
    } elseif(strcmp($password, $passwordConfirm) !== 0) {
        $errors['passwordConfirm'] = 'Passwords did not match';
    }
}

function ValidatePhone($phone, &$errors) {
    // Sanitize input
    $phone = sanitizeInput($phone);
    
    if(empty($phone)) {
        $errors['phoneNumber'] = 'Phone cannot be empty';
    } elseif(!preg_match("/^[1-9]\d{2}-[1-9]\d{2}-\d{4}$/", $phone)) {
        $errors['phoneNumber'] = 'Not a valid phone number: Use format XXX-XXX-XXXX';
    }
}

/**
 * Validate all form inputs at once
 * 
 * @param array $formData Associative array of form inputs
 * @param array &$errors Reference to errors array
 * @param bool $isSignUp Whether this is for sign up (true) or login (false)
 * @return bool True if validation passes, false otherwise
 */
function validateFormInputs($formData, &$errors, $isSignUp = true) {
    // Validate CSRF token first
    if (!isset($formData['csrf_token']) || !verifyCSRFToken($formData['csrf_token'])) {
        $errors['form'] = 'Form validation failed. Please try again.';
        return false;
    }
    
    if(isset($formData['studentId'])) { 
        ValidateStudentId($formData['studentId'], $errors, $isSignUp);
    }
    
    if($isSignUp) {
        if(isset($formData['name'])) { 
            ValidateName($formData['name'], $errors);
        }
        
        if(isset($formData['phoneNumber'])) { 
            ValidatePhone($formData['phoneNumber'], $errors);
        }
        
        if(isset($formData['email'])) { 
            ValidateEmail($formData['email'], $errors);
        }
        
        if(isset($formData['passwordConfirm'])) { 
            ValidatePasswordConfirm($formData['password'], $formData['passwordConfirm'], $errors);
        }
    }
    
    if(isset($formData['password'])) { 
        ValidatePassword($formData['password'], $errors, $isSignUp);
    }
    
    return empty($errors);
}
?>