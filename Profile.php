<?php

include_once("./Models/Student.php");
include_once("Functions.php");
session_start();
error_reporting(E_ALL);


if (!isset($_SESSION['student'])) {
    header("Location: /SecureLogin.php");
    exit();
} 
include_once("./include/Header.php");

extract($_POST);
$studentId = $_SESSION['student']->getStudentId();
$errors = array();

?>
<main class="container">
    <?php 
        if(isset($_SESSION["student"])) {
            $studentId = $_SESSION['student']->getStudentId();
            echo "<h1 class='text-center'>Profile</h1>
            <p>Welcome <b>".$_SESSION["student"]->getName().
            "</b>! (Not you? Change user 
            <a href='/SecureLogin.php'>here)</a>
            </p>";
        }  
    ?>
    <div class="profile-container">
        <aside class="profile-card profile-panel">
            <div class="profile-image">
                <img src="./include/img/profile.png" alt="profile-image">
            </div>
            <ul>
                <li><? echo $_SESSION['student']->getName(); ?></li>
                <li><? echo $_SESSION['student']->getPhoneNumber(); ?></li>
                <li><? echo $_SESSION['student']->getEmail(); ?></li>
            </ul>
        </aside>
        <section class="profile-data">
            <div class="profile-card profile-details">
                <h2>Details</h2>
                <div>
                    <span>Name</span>
                    <? echo "<span>".$_SESSION['student']->getName()."</span>"; ?>
                </div>
                <div>
                    <span>Phone Number</span>
                    <? echo "<span>".$_SESSION['student']->getPhoneNumber()."</span>"; ?>
                </div>
                <div>
                    <span>Email</span>
                    <? echo "<span>".$_SESSION['student']->getEmail()."</span>"; ?>
                </div>
                
            </div>
            <div class="profile-card profile-roles">
                <h2>Roles</h2>
                <? echo "<span>".$_SESSION['student']->getRoles()."</span>"; ?>
            </div>
        </section>
    </div>
</main>
<? include("./include/Footer.php"); ?>
