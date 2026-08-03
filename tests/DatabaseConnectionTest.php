<?php

namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    public function testSQLiteConnectionIsSuccessful(): void
    {
        // Arrange
        $dsn = 'sqlite::memory:';
        
        // Act
        $db = new \App\Database\DatabaseConnection($dsn);
        $pdo = $db->getPdo();
        
        // Assert
        $this->assertInstanceOf(PDO::class, $pdo);
        
        // Let's also test a simple query to ensure it's functioning
        $pdo->exec('CREATE TABLE test (id INTEGER PRIMARY KEY)');
        $this->assertSame('00000', $pdo->errorCode());
    }
}
