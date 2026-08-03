<?php

namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    public function testSQLiteConnectionIsSuccessful(): void
    {
        // This test ensures we can create an SQLite connection in-memory for testing
        // or a file-based one for development.
        
        // Arrange
        $dsn = 'sqlite::memory:';
        
        // Act
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Assert
        $this->assertInstanceOf(PDO::class, $pdo);
        
        // Let's also test a simple query to ensure it's functioning
        $pdo->exec('CREATE TABLE test (id INTEGER PRIMARY KEY)');
        $this->assertSame('00000', $pdo->errorCode());
    }
}
