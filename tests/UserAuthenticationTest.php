<?php
use PHPUnit\Framework\TestCase;

require_once '../src/UserAuthentication.php';
require_once '../config/config.php';

class UserAuthenticationTest extends TestCase {
    private $db;
    private $auth;

    protected function setUp(): void {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->db->connect_error) {
            $this->fail("Database connection failed: " . $this->db->connect_error);
        }
        
        $this->db->query("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )");

        $this->auth = new UserAuthentication($this->db);
    }

    protected function tearDown(): void {
        $this->db->query("DROP TABLE IF EXISTS users");
        $this->db->close();
    }

    public function testRegisterUserSuccess() {
        $result = $this->auth->register('testUser', 'password123');
        $this->assertEquals('User registered successfully', $result);
    }

    public function testRegisterUserEmptyUsername() {
        $result = $this->auth->register('', 'password123');
        $this->assertEquals('Username cannot be empty', $result);
    }

    public function testRegisterUserEmptyPassword() {
        $result = $this->auth->register('testUser', '');
        $this->assertEquals('Password cannot be empty', $result);
    }

    public function testRegisterUserAlreadyExists() {
        $this->auth->register('testUser', 'password123');
        $result = $this->auth->register('testUser', 'password123');
        $this->assertEquals('Username already exists', $result);
    }

    public function testLoginUserSuccess() {
        $this->auth->register('testUser', 'password123');
        $result = $this->auth->login('testUser', 'password123');
        $this->assertJson($result);
        $data = json_decode($result, true);
        $this->assertArrayHasKey('token', $data);
    }

    public function testLoginUserInvalidUsername() {
        $result = $this->auth->login('invalidUser', 'password123');
        $this->assertEquals('Invalid username or password', $result);
    }

    public function testLoginUserInvalidPassword() {
        $this->auth->register('testUser', 'password123');
        $result = $this->auth->login('testUser', 'wrongPassword');
        $this->assertEquals('Invalid username or password', $result);
    }

    public function testLoginUserEmptyFields() {
        $result = $this->auth->login('', '');
        $this->assertEquals('Username and password cannot be empty', $result);
    }
}
?>
