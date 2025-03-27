<?php
class Student {
    private string $studentId = '';
    private string $name = '';
    private string $phoneNumber = '';
    private string $email = '';
    private string $role = '';

    public function __construct($studentId, $name, $phoneNumber, $email) {
        $this->studentId = $studentId;
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
    }

    public function getStudentId() {
        return $this->studentId;
    }

    public function getName() {
        return $this->name;
    }

    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRoles() {
        return $this->role;
    }
}
?>