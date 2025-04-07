<?php include("./include/Header.php"); ?>
<style>
/* Hero section with full-screen background image */
.hero-section {
    background: url('./include/img/img-home.webp') no-repeat center center;
    background-size: cover;
    min-height: 90vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #fff;
    padding: 2rem;
    position: relative;
}

/* Optional overlay for better text readability */
.hero-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1;
}

/* Content inside hero section stays above overlay */
.hero-content {
    position: relative;
    z-index: 2;
}

/* Spacing between the buttons */
.hero-buttons a {
    margin: 0 10px;
}

/* Custom style for secondary button (Log In) */
.btn-secondary-custom {
    background-color: #ffffff;
    color: #00703C;
    border: none;
}

/* Hover effect for secondary button */
.btn-secondary-custom:hover {
    background-color: #f0f0f0;
    color: #00703C;
}

</style>
<!-- Hero section with text and buttons -->
<div class="hero-section">
    <div class="hero-content">
        <h1 class="mb-3" style="color: #FFFFFF;">Welcome to Algonquin College</h1>
        <p class="mb-4">Get started by signing up or log in if you already have an account.</p>
        <div class="mb-5 hero-buttons">
            <a href="SecureNewUser.php" class="btn btn-success">Sign Up</a>
            <a href="SecureLogin.php" class="btn btn-secondary-custom">Log In</a>
        </div>
    </div>
</div>

<?php include("./include/Footer.php"); ?>
