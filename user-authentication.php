<?php
require_once 'db_connection.php';

class UserAuth {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // User Registration
    public function registerUser($username, $email, $password, $full_name, $college, $branch) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $this->db->conn->prepare("INSERT INTO users (username, email, password, full_name, college, branch) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $email, $hashed_password, $full_name, $college, $branch);
        
        return $stmt->execute();
    }

    // User Login
    public function loginUser($username, $password) {
        $stmt = $this->db->conn->prepare("SELECT user_id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                return true;
            }
        }
        return false;
    }

    // Get User Profile
    public function getUserProfile($user_id) {
        $stmt = $this->db->conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>