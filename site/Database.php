<?php

class Database
{
    private PDO $pdo;

    /**
     * @param string $dsn      Строка подключения, например:
     *                         "mysql:host=database;dbname=my_database;charset=utf8"
     * @param string $username Имя пользователя БД
     * @param string $password Пароль пользователя БД
     */
    public function __construct(string $dsn, string $username, string $password)
    {
        $this->pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    /**
     * Выполняет SELECT-запрос и возвращает все строки.
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Выполняет INSERT / UPDATE / DELETE.
     * Возвращает количество затронутых строк.
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Возвращает ID последней вставленной записи.
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
}
