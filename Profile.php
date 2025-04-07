<?php
include_once("./Models/Student.php");
include_once("Functions.php");

session_start();
error_reporting(E_ALL);

// Redirect if student not logged in
if (!isset($_SESSION['student'])) {
    header("Location: /SecureLogin.php");
    exit();
}

include_once("./include/Header.php");

$student = $_SESSION['student'];
?>

<style>
    /* Container centralization */
    .profile-container {
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Profile box */
    .profile-box {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e0e0; /* Light grey border */
        width: 100%;
        max-width: 700px;
    }

    .profile-box h1 {
        margin-bottom: 20px;
        text-align: center;
    }

    .profile-box p {
        text-align: center;
        margin-bottom: 30px;
    }

    /* Profile image */
    .profile-image {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-image img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #00703C;
    }

    /* Profile details */
    .profile-details {
        margin-bottom: 20px;
    }

    .profile-details h2,
    .profile-roles h2 {
        margin-bottom: 15px;
        color: #00703C;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 5px;
    }

    .profile-details div {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .profile-roles span {
        display: block;
        margin-top: 10px;
    }

    /* Responsive adjustment */
    @media (max-width: 576px) {
        .profile-details div {
            flex-direction: column;
            text-align: left;
        }
    }
</style>

<div class="container profile-container">
    <div class="profile-box">

        <h1>Profile</h1>
        <p>Welcome <b><?php echo htmlspecialchars($student->getName()); ?></b>! (Not you? <a href='/SecureLogin.php'>Change user here</a>)</p>

        <div class="profile-image">
            <img src="./include/img/profile.png" alt="Profile Image">
        </div>

        <div class="profile-details">
            <h2>Details</h2>
            <div>
                <span>Name</span>
                <span><?php echo htmlspecialchars($student->getName()); ?></span>
            </div>
            <div>
                <span>Phone Number</span>
                <span><?php echo htmlspecialchars($student->getPhoneNumber()); ?></span>
            </div>
            <div>
                <span>Email</span>
                <span><?php echo htmlspecialchars($student->getEmail()); ?></span>
            </div>
        </div>

        <div class="profile-roles">
            <h2>Roles</h2>
            <span><?php echo htmlspecialchars($student->getRoles()); ?></span>
        </div>

    </div>
</div>

<?php include("./include/Footer.php"); ?>
