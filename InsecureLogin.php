<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './Models/Student.php'; 
include_once 'Functions.php';

error_reporting(E_ALL);
extract($_POST);

if(isset($submit)) {
    try {
        if(isset($studentId) && isset($password)) { 
            
            $student = getInsecureStudent($studentId, $password);
            
            if($student != null) {
                $_SESSION['student'] = $student;
                header("Location: Profile.php");
                exit();
            } else {
                $errors['login'] = 'Incorrent User ID and Password Combination!';
            }
        }
    }
    catch(Exception $ex) {
        die('Error: '.$ex->getMessage());
    }

}


include("./include/Header.php"); 
?>
<div class="container">
    <div class="row">
        <h1 class="text-left">Log In</h1>
        <p>You need to <a href="SecureNewUser.php">sign up</a> if you are a new user or <a href="InsecureNewUser.php">sign up (insecure)</a> for testing</p>
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
                <span class="text-danger row">
                    <? if (isset($errors['login'])) {echo $errors['login'];} ?>
                </span>
                <div class="form-group mt-3">
                    <button type="submit" id="submit" class="btn btn-success" name="submit">Submit</button>
                    <button type="button" id="clear" class="btn btn-success" name="clear">Clear</button>
                </div>
            </form>
        </div>
    </div>
</div>
<? include("./include/Footer.php"); ?>