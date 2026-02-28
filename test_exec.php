<?php
echo "<h3>Проверка exec() функции:</h3>";

// Проверяем, отключена ли exec
$disabled = explode(',', ini_get('disable_functions'));
if (in_array('exec', $disabled)) {
    echo "<p style='color:red'>❌ Функция exec() отключена в PHP</p>";
} else {
    echo "<p style='color:green'>✅ Функция exec() доступна</p>";
}

// Пробуем выполнить простую команду
exec('echo test', $output, $code);
echo "<p>Результат тестовой команды: " . ($code === 0 ? '✅ Успешно' : '❌ Ошибка') . "</p>";

// Проверяем путь к PHP
$phpPaths = array('php', 'C:\xampp\php\php.exe', 'C:\php\php.exe');
foreach ($phpPaths as $phpPath) {
    exec($phpPath . ' -v 2>&1', $out, $code);
    if ($code === 0) {
        echo "<p style='color:green'>✅ PHP найден по пути: $phpPath</p>";
        break;
    }
}
?>