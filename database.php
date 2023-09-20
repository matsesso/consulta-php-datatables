<?php

class Database {
  private $connection;
  private static $_instance;
  private $dbhost = 'db_host';
  private $dbuser = 'db_user';
  private $dbpass = 'db_pass';
  private $dbname = 'db_name';

  /*
      * Pega a instancia do banco de dados
      * @return Instance
  */
  public static function getInstance()
    {
        if (! self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Construtor
    private function __construct()
    {
        try {
            $this->connection = new PDO('mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname . ';charset=utf8', $this->dbuser, $this->dbpass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Error handling
        } catch (PDOException $e) {
            die("Falha ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    // Método mágico previne conexão duplicada
    private function __clone()
    {}

    // Realiza a conexão
    public function getConnection()
    {
        return $this->connection;
    }
}
