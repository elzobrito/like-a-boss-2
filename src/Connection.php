<?php

namespace elzobrito;

use \PDO;
use \PDOStatement;
use \Exception;

/**
 * Class Connection
 * @package elzobrito
 */
class Connection
{
    /**
     * @var PDO
     */
    private $pdo = null;
    /**
     * @var array
     */
    private $options = [];

    /**
     * Connection constructor.
     * @param array $options
     * @throws Exception
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return PDO
     */
    protected function connect()
    {
        if (!$this->pdo) {
            $this->pdo = new PDO($this->dsn(), $this->options['user'], $this->options['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"'));
        }
        return $this->pdo;
    }

    /**
     * @return string
     */
    protected function dsn()
    {
        return "{$this->options['driver']}:host={$this->options['host']};port={$this->options['port']};dbname={$this->options['database']}";
    }

    /**
     * @param $sql
     * @return PDOStatement
     */
    private final function statement($sql)
    {
        return $this->connect()->prepare($sql);
    }

    /**
     * @param string $sql
     * @param array $values
     * @return string
     */
    protected final function executeInsert($sql, array $values)
    {
        $statement = $this->statement($sql);

        if ($statement && $statement->execute(array_values($values))) {
            return $this->connect()->lastInsertId();
        }

        return null;
    }

    /**
     * @param string $sql
     * @param array $values
     * @return array
     */
    protected final function executeSelect($sql, array $values)
    {
        $statement = $this->statement($sql);

        if ($statement && $statement->execute(array_values($values))) {
            return $statement->fetchAll(PDO::FETCH_OBJ);
        }

        return null;
    }

    /**
     * @param string $sql
     * @param array $values
     * @return int
     */
    protected final function executeUpdate($sql, array $values)
    {
        return $this->execute($sql, $values);
    }

    /**
     * @param string $sql
     * @param array $values
     * @return int
     */
    protected final function executeDelete($sql, array $values)
    {
        return $this->execute($sql, $values);
    }

    /**
     * @param $sql
     * @param array $values
     * @return int|null
     */
    protected final function execute($sql, array $values)
    {
        $statement = $this->statement($sql);

        if ($statement && $statement->execute(array_values($values))) {
            return $statement->rowCount();
        }

        return null;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
