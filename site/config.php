<?php

// Настройки подключения к базе данных
// Хост и название БД берём из переменных окружения
$config['db']['host']     = getenv('MYSQL_HOST');
$config['db']['database'] = getenv('MYSQL_DATABASE');

// Логин и пароль берём из секретов Docker (файлы в /run/secrets/)
// НЕ используем переменные окружения MYSQL_USER / MYSQL_PASSWORD напрямую
$config['db']['username'] = trim(file_get_contents('/run/secrets/user'));
$config['db']['password'] = trim(file_get_contents('/run/secrets/secret'));
