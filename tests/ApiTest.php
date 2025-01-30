<?php

use PHPUnit\Framework\TestCase;


class ApiTest extends TestCase {
    public function testUserCreation() {
        // Simulate an API request to create a user
        $ch = curl_init('http://localhost:8000/users');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['username' => 'testuser']));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        var_dump($response);
        curl_close($ch);

        
        // Assert that the response is not empty
        $this->assertNotEmpty($response);
    }
}
