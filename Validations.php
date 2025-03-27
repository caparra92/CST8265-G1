<?
include_once 'Functions.php';

$errors = array();


function ValidateStudentId($studentId, &$errors, $isSignUp) {
    if (empty($studentId)) {
        $errors['studentId'] = 'Student Id cannot be empty!';
    }

    if ($isSignUp) {
        if(getSecureStudentId($studentId)) {
            $errors['studentId'] = 'Student Id already exists!';
        }
    }
} 

function ValidateName($name, &$errors) {
    if(empty($name)) {$errors['name'] = 'Name cannot be empty';}
}

function ValidatePassword($password, &$errors, $isSignUp) {
    if(empty($password)) {
        $errors['password'] = 'Password cannot be empty';
    }
    if(trim($password) && $isSignUp) {
        if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?!.*\s).{6,}$/", $password)) {
            $errors['password'] = 'Password must contain: At least 6 char long, 1 Upper Case, 1 Lower Case, 1 Number';
        }
    }
}

function ValidatePasswordConfirm($password, $passwordConfirm, &$errors) {
    if(strcmp($password, $passwordConfirm) !== 0) {
        $errors['passwordConfirm'] = 'Passwords did not match';
    }
}

function ValidatePhone($phone, &$errors) {
    if(empty($phone)) {
        $errors['phoneNumber'] = 'Phone cannot be empty';
    }
    if(trim($phone)) {
        if(!preg_match("/^[1-9]\d{2}-[1-9]\d{2}-\d{4}$/", $phone)) {
            $errors['phoneNumber'] = 'Not a valid phone number: Use xxxxxxxxx';
        }
    }
}

?>