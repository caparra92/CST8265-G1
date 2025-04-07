<?php
include_once './Models/Student.php';

// Don't extract POST data globally - it's a security risk
// extract($_POST);
$errors = array();

// Function for audit logging
function logUserAction($action, $userId) {
    try {
        $timestamp = date('Y-m-d H:i:s');
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        
        $pdo = getPDO();
        $sql = "INSERT INTO AuditLog (Timestamp, UserId, Action, IPAddress, UserAgent) 
                VALUES (:timestamp, :userId, :action, :ipAddress, :userAgent)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':action', $action, PDO::PARAM_STR);
        $stmt->bindParam(':ipAddress', $ipAddress, PDO::PARAM_STR);
        $stmt->bindParam(':userAgent', $userAgent, PDO::PARAM_STR);
        
        $stmt->execute();
    } catch (PDOException $ex) {
        // Silent failure for logs - don't disrupt the user experience
        error_log("Failed to log action: " . $ex->getMessage());
    }
}

// Generate anti-CSRF token
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify anti-CSRF token
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

function getPDO() {

    $dbConnection = parse_ini_file('Lab5.ini');

    extract($dbConnection);

    return new PDO($dsn, $user, $password);
}

function getSecureStudentId($studentId) {
    try {
        $pdo = getPDO();
        $sql = "SELECT StudentId FROM Student WHERE StudentId = :studentId";
        $resultSet = $pdo->prepare($sql);
        $resultSet->execute(['studentId'=>$studentId]);
        $row = $resultSet->fetch(PDO::FETCH_ASSOC);
        $studentExists = $row ? true : false;
        return $studentExists;
    } catch (PDOException $ex) {
        die("Database error: " . $ex->getMessage());
    }
}

function getInsecureStudentId($studentId) {
    try {
        $pdo = getPDO();
        $sql = "SELECT StudentId FROM Student WHERE StudentId = '{$_POST['studentId']}'";
        $resultSet = $pdo->query($sql);
        $row = $resultSet->fetch(PDO::FETCH_ASSOC);
        $studentExists = $row ? true : false;
        return $studentExists;
    } catch (PDOException $ex) {
        die("Database error: " . $ex->getMessage());
    }
}

function getSecureStudent($studentId, $password) {
    try {
        $pdo = getPDO();
        
        // Use prepared statement for the initial password query
        $passSql = "SELECT Password FROM Student WHERE StudentId = :studentId";
        $stmt = $pdo->prepare($passSql);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_STR);
        $stmt->execute();
        
        $passRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($passRow) {
            $hash = $passRow['Password'];
            // Verify password with password_verify
            if (password_verify($password, $hash)) {
                // Use prepared statement for the user data query
                $sql = "SELECT StudentId, Name, Phone, Email FROM Student WHERE StudentId = :studentId";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':studentId', $studentId, PDO::PARAM_STR);
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row) {
                    // Log successful login
                    logUserAction('User login', $studentId);
                    return new Student($row['StudentId'], $row['Name'], $row['Phone'], $row['Email']);
                } else {
                    return null;
                }
            } else {
                // Log failed login attempt
                logUserAction('Failed login attempt', $studentId);
                return null;
            }
        }
        return null;
    } catch (PDOException $ex) {
        error_log("Database error during login: " . $ex->getMessage());
        throw new Exception("Authentication system unavailable");
    }
}

function getInsecureStudent($studentId, $password) {
    
    $pdo = getPDO();
    $passSql = "SELECT Password FROM Student WHERE StudentId = '$studentId'";
    $resultSetPass = $pdo->query($passSql);
    $passRow = $resultSetPass->fetch(PDO::FETCH_ASSOC);
    $hash = $passRow['Password'];
    $sql = "SELECT StudentId, Name, Phone, Email FROM Student WHERE StudentId = '{$_POST['studentId']}' AND Password = '$hash'";
    // echo "DEBUG SQL: $sql";
    // exit();
    $resultSet = $pdo->query($sql);
    if ($resultSet) {
        $row = $resultSet->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Student($row['StudentId'], $row['Name'], $row['Phone'], $row['Email']);
        }
    }
    
    return null;
}

function addSecureStudent($studentId, $name, $phone, $password, $email)
{
    try {
        $pdo = getPDO();
        // Use PASSWORD_BCRYPT explicitly as requested
        $password = password_hash($password, PASSWORD_BCRYPT);
        
        // Use prepared statements with parameters to prevent SQL injection
        $sql = "INSERT INTO Student (StudentId, Name, Phone, Password, Email) VALUES(:studentId, :name, :phone, :password, :email)";
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        
        // Execute the statement
        $stmt->execute();
        
        // Audit logging
        logUserAction('New user registration', $studentId);
        
        return true;
    } catch (PDOException $ex) {
        // Generic error message to avoid exposing technical details
        error_log("Database error during registration: " . $ex->getMessage());
        return false;
    }
}

function addInsecureStudent($studentId, $name, $phone, $email, $password)
{
    try {
        $pdo = getPDO();
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO Student (StudentId, Name, Phone, Password, Email) 
                VALUES ('$studentId', '$name', '$phone', '$password', '$email')";
        // echo "DEBUG SQL: $sql";
        // exit();
        $pdo->exec($sql);
    } catch (PDOException $ex) {
        die("Database error: " . $ex->getMessage());
    }
}
?>