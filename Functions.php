<?php
include_once './Models/Student.php';

extract($_POST);
$errors = array();

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
    
    $pdo = getPDO();
    $passSql = "SELECT Password FROM Student WHERE StudentId = '$studentId'";
    $resultSetPass = $pdo->query($passSql);
    $passRow = $resultSetPass->fetch(PDO::FETCH_ASSOC);
    if ($passRow) {
        $hash = $passRow['Password'];
        if (password_verify($password, $hash)) {
            $sql = "SELECT StudentId, Name, Phone, Email FROM Student WHERE StudentId = '$studentId' AND Password = '$hash'";
            $resultSet = $pdo->query($sql);
            if($resultSet) {
                $row = $resultSet->fetch(PDO::FETCH_ASSOC);
                if($row) {
                    return new Student($row['StudentId'], $row['Name'], $row['Phone'], $row['Email']);
                } else {
                    return null;
                }
            } else {
                throw new Exception("Query failed!, SQL statement: $sql");
            }
        } else {
            return null;
        }
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

function addSecureStudent($studentId, $name, $phone, $password, $email )
{
    try {
        $pdo = getPDO();
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO Student (StudentId, Name, Phone, Password, Email) VALUES( '$studentId', '$name', '$phone', '$password', '$email')";
        // echo "DEBUG SQL: $sql";
        // exit();
        $pdoStmt = $pdo->query($sql);
    } catch (PDOException $ex) {
        die("Database error: " . $ex->getMessage());
    }
}

function addInsecureStudent($studentId, $name, $phone, $email, $password)
{
    try {
        $pdo = getPDO();
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO Student (StudentId, Name, Phone, Password, Email) 
                VALUES ('$studentId', '$name', '$phone', '$password', '$email')";
        $pdo->exec($sql);
    } catch (PDOException $ex) {
        die("Database error: " . $ex->getMessage());
    }
}
?>