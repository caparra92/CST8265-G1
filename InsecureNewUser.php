<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once './Models/Student.php'; 
include_once 'Functions.php';
include 'Validations.php';

extract($_POST);

if(isset($submit)) {
    try {
        if(isset($studentId)) { ValidateStudentId($studentId, $errors, true);}
        if(isset($name)) { ValidateName($name, $errors);}
        if(isset($phoneNumber)) { ValidatePhone($phoneNumber, $errors);}
        if(isset($password)) { ValidatePassword($password, $errors, true);}
        if(isset($passwordConfirm)) { ValidatePasswordConfirm($password, $passwordConfirm, $errors);}
        
        if(empty($errors)) {
            $student = addInsecureStudent($studentId, $name, $phoneNumber, $password, $email);
            header("Location: InsecureLogin.php");
            exit();
        }
    }
    catch(Exception $ex) {
        die('System currently not available, try again later');
    }
    
}


include("./include/Header.php"); 
?>
<div class="container">
    <div class="row">
        <h1 class="text-left">Sign Up</h1>
        <p>All fields are required</p>
        <div class="col-md-8">
            <form method="post" class="form row">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="studentId">Student ID:</label>
                    <div class="col-sm-7">
                        <input
                        class="form-control" 
                        type="text" 
                        name="studentId" 
                        id="studentId"
                        value="<? if (isset($_SESSION['studentId'])) { 
                                    echo $_SESSION['studentId'];
                                } else if (isset($studentId)) {
                                    echo $studentId;
                                }
                                ?>"
                        >
                    </div>
                    <span class="text-danger col-sm-3">
                        <? if (isset($errors['studentId'])) {echo $errors['studentId'];} ?>
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
                        value="<? if (isset($_SESSION['name'])) { 
                                    echo $_SESSION['name'];
                                } else if (isset($name)) {
                                    echo $name;
                                }
                                ?>"
                        >
                    </div>
                    <span class="text-danger col-sm-3">
                        <? if (isset($errors['name'])) {echo $errors['name'];} ?>
                    </span>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="phoneNumber">Phone Number</label>
                    <div class="col-sm-7">
                        <input
                        class="form-control" 
                        type="text" 
                        name="phoneNumber" 
                        id="phoneNumber" 
                        placeholder="xxx-xxx-xxxx"
                        value=<? 
                                if (isset($_SESSION['phoneNumber'])) { 
                                    echo $_SESSION['phoneNumber'];
                                } else if (isset($phoneNumber)) {
                                    echo $phoneNumber;
                                }
                        ?>
                        >
                    </div>
                    <span class="text-danger col-sm-3">
                        <? if (isset($errors['email'])) {echo $errors['email'];} ?>
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
                        placeholder="xxx@test.com"
                        value=<? 
                                if (isset($_SESSION['email'])) { 
                                    echo $_SESSION['email'];
                                } else if (isset($email)) {
                                    echo $email;
                                }
                        ?>
                        >
                    </div>
                    <span class="text-danger col-sm-3">
                        <? if (isset($errors['email'])) {echo $errors['email'];} ?>
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
                        value="<? 
                                if (isset($_SESSION['password'])) { 
                                    echo trim($_SESSION['password']);
                                } else if (isset($password)) {
                                    echo $password;
                                }
                        ?>"
                        >
                    </div>
                    <span class="text-danger col-sm-3">
                        <? if (isset($errors['password'])) {echo $errors['password'];} ?>
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
                        value="<? 
                                if (isset($_SESSION['passwordConfirm'])) { 
                                    echo trim($_SESSION['passwordConfirm']);
                                } else if (isset($passwordConfirm)) {
                                    echo $passwordConfirm;
                                }
                        ?>"
                        >
                    </div>
                    <span class="text-danger col-sm-3">
                        <? if (isset($errors['passwordConfirm'])) {echo $errors['passwordConfirm'];} ?>
                    </span>
                </div>
                <button type="submit" id="submit" class="btn btn-success" name="submit">Submit</button>
                <button type="button" id="clear" class="btn btn-success" name="clear">Clear</button>
            </form>
        </div>
    </div>
</div>
<? include("./include/Footer.php"); ?>