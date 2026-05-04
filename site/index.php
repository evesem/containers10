<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';

$error = null;
$messages = [];

try {
    $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['database']};charset=utf8";
    $db  = new Database($dsn, $config['db']['username'], $config['db']['password']);

    $db->execute("
        CREATE TABLE IF NOT EXISTS messages (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            text       VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['text'])) {
        $db->execute("INSERT INTO messages (text) VALUES (?)", [
            htmlspecialchars(trim($_POST['text']), ENT_QUOTES, 'UTF-8')
        ]);
        header('Location: /');
        exit;
    }

    $messages = $db->query("SELECT * FROM messages ORDER BY created_at DESC");

} catch (PDOException $e) {
    $error = 'Ошибка подключения к БД: ' . $e->getMessage();
} catch (Exception $e) {
    $error = 'Ошибка: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>containers10 — Docker Secrets Demo</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: sans-serif;
            max-width: 680px;
            margin: 40px auto;
            padding: 0 20px;
            color: #222;
        }
        h1 { font-size: 1.3rem; margin-bottom: 24px; }
        .error {
            background: #fde8e8;
            border: 1px solid #f5a5a5;
            color: #a00;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            word-break: break-word;
        }
        form {
            display: flex;
            gap: 8px;
            margin-bottom: 28px;
        }
        input[type="text"] {
            flex: 1;
            padding: 9px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }
        button {
            padding: 9px 18px;
            background: #0066cc;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover { background: #0052a3; }
        ul { list-style: none; }
        li {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        li:last-child { border-bottom: none; }
        .meta { font-size: 0.78rem; color: #888; margin-top: 3px; }
        .empty { color: #888; font-size: 0.9rem; padding: 12px 0; }
    </style>
</head>
<body>
    <h1>Docker Secrets Demo — containers10</h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!$error): ?>
    <form method="post" action="/">
        <input type="text" name="text" placeholder="Введите сообщение..." required maxlength="255">
        <button type="submit">Добавить</button>
    </form>

    <ul>
        <?php if (empty($messages)): ?>
            <li class="empty">Записей пока нет.</li>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
            <li>
                <div><?= htmlspecialchars($msg['text'], ENT_QUOTES, 'UTF-8') ?></div>
                <div class="meta"><?= htmlspecialchars($msg['created_at']) ?></div>
            </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    <?php endif; ?>
</body>
</html>
