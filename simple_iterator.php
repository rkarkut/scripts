<?php

/**
 * Class SimpleIterator
 *
 * Simple Db iterator.
 *
 */
class SimpleIterator implements Iterator
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var int
     */
    private $limit = 2;

    public function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=local', 'root', 'root');
        } catch (PDOException $e) {
            echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
        }
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->getPosts($this->position, $this->limit);

    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        return $this->position += $this->limit;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        $rows = $this->getPosts($this->position, $this->limit);

        return !empty($rows);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->position = 0;
    }

    private function getPosts($position, $limit)
    {
        $sql = 'SELECT id, title, created_at FROM posts LIMIT ' . $position . ', ' . $limit;

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}

$simpleIterator = new SimpleIterator();

foreach ($simpleIterator as $key => $val) {
    var_dump($val);
    print PHP_EOL;
}

/** optional implementation */

$simpleIterator->rewind();

while ($simpleIterator->valid())
{
    $key = $simpleIterator->key();
    $value = $simpleIterator->current();

    var_dump($value);
    print PHP_EOL;

    $simpleIterator->next();
}