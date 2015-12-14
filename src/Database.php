<?php

namespace Swos\Database;

use \PDO;

class Database {
	private $dbh;
	private $statement;

	public function __construct($config) {
		try {
			$dsn = $config['type'] . ':host=' . $config['host'] . ';dbname=' . $config['dbname'];
			$this->dbh = new \PDO($dsn, $config['user'], $config['pass'], $config['options']);
		}
		catch (PDOException $e) {
			$this->error = $e->getMessage();
		}
	}

	public function query($query) {
		$this->statement = $this->dbh->prepare($query);
	}

    public function bind($name, $arg) {
    	$t = gettype($arg);
    	switch($t) {
			case 'integer':
				$type = PDO::PARAM_INT;
				break;
			case 'boolean':
				$type = PDO::PARAM_BOOL;
				break;
			case 'NULL':
				$type = PDO::PARAM_NULL;
				break;
			default:
				$type = PDO::PARAM_STR;
    	}
    	$this->statement->bindValue($name, $arg, $type);
    }

    public function execute() {
		return $this->statement->execute();
    }

    public function single() {
		$this->execute();
		return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function resultset() {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }
}