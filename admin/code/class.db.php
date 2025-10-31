<?php
class Database
{
  private $pdo;
  private $stmt;

  public function __construct($host, $dbname, $user, $pass, $charset = 'utf8mb4')
  {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
      $this->pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
    }
  }

  // Helper: build WHERE clause from array
  private function buildWhere(array $conditions)
  {
    $where = '';
    $params = [];
    if (!empty($conditions)) {
      $clauses = [];
      foreach ($conditions as $key => $val) {
        $clauses[] = "$key = :$key";
        $params[$key] = $val;
      }
      $where = 'WHERE ' . implode(' AND ', $clauses);
    }
    return [$where, $params];
  }

  // RAW SQL QUERY
  public function query($query, $params = [])
  {
    $this->stmt = $this->pdo->prepare($query);
    $this->stmt->execute($params);

    $queryType = strtolower(explode(' ', trim($query))[0]);

    if ($queryType === 'select' || $queryType === 'show') {
      return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($queryType === 'insert') {
      return $this->pdo->lastInsertId(); // Return inserted ID
    } else {
      return $this->stmt->rowCount(); // For update/delete: number of affected rows
    }
  }

  // SELECT multiple rows with optional conditions
  public function select($table, $conditions = [], $columns = '*')
  {
    list($where, $params) = $this->buildWhere($conditions);
    $sql = "SELECT $columns FROM $table $where";
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute($params);
    return $this->stmt->fetchAll();
  }

  // SELECT single row
  public function selectOne($table, $conditions = [], $columns = '*')
  {
    list($where, $params) = $this->buildWhere($conditions);
    $sql = "SELECT $columns FROM $table $where LIMIT 1";
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute($params);
    return $this->stmt->fetch();
  }

  // INSERT
  public function insert($table, $data)
  {
    $columns = implode(', ', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $this->stmt = $this->pdo->prepare($sql);
    return $this->stmt->execute($data);
  }

  // UPDATE with associative array conditions
  public function update($table, $data, $conditions)
  {
    $fields = '';
    foreach ($data as $key => $val) {
      $fields .= "$key = :$key, ";
    }
    $fields = rtrim($fields, ', ');

    list($where, $whereParams) = $this->buildWhere($conditions);

    $sql = "UPDATE $table SET $fields $where";
    $this->stmt = $this->pdo->prepare($sql);
    return $this->stmt->execute(array_merge($data, $whereParams));
  }

  // DELETE with associative array conditions
  public function delete($table, $conditions)
  {
    list($where, $params) = $this->buildWhere($conditions);
    $sql = "DELETE FROM $table $where";
    $this->stmt = $this->pdo->prepare($sql);
    return $this->stmt->execute($params);
  }

  // Get last inserted ID
  public function lastInsertId()
  {
    return $this->pdo->lastInsertId();
  }
}


$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
// ----------------- USAGE EXAMPLES ----------------- //

// INSERT example
// $sql = "INSERT INTO users (name, email) VALUES (?, ?)";
// $params = ['Alice', 'alice@example.com'];
// $insertId = $db->query($sql, $params);
// $insertId is the ID of the newly inserted row

// UPDATE example
// $sql = "UPDATE users SET name = ? WHERE id = ?";
// $params = ['Bob', 1];
// $rowsAffected = $db->query($sql, $params);
// $rowsAffected is number of rows updated

// SELECT example
// $sql = "SELECT * FROM users WHERE status = ?";
// $params = ['active'];
// $users = $db->query($sql, $params);
// $users is an array of results

// Select multiple users where status = active
// $users = $db->select('users', ['status' => 'active']);

// Select single user by id
// $user = $db->selectOne('users', ['id' => 1]);

// Insert a new user
// $db->insert('users', ['name' => 'John', 'email' => 'john@example.com']);

// Update user name where id = 1
// $db->update('users', ['name' => 'Jane'], ['id' => 1]);

// Delete user where id = 2
// $db->delete('users', ['id' => 2]);
