<?php
//14.01.2026 Панель управления сайтами
// locator.php - разместите в /xampp/htdocs/locator/locator.php
// Версия для PHP 5.6 с счетчиком запусков
// Version: 1.2.0
// Last update: 2026-02-28

date_default_timezone_set('Europe/Moscow');
session_start();

// Файл для хранения статистики
define('STATS_FILE', __DIR__ . '/stats.json');

// Функция для загрузки статистики
function loadStats() {
    if (!file_exists(STATS_FILE)) {
        $defaultStats = array(
            'total_runs' => 0,
            'sites' => array()
        );
        file_put_contents(STATS_FILE, json_encode($defaultStats));
        return $defaultStats;
    }
    $content = file_get_contents(STATS_FILE);
    $stats = json_decode($content, true);
    if (!is_array($stats)) {
        $stats = array('total_runs' => 0, 'sites' => array());
    }
    return $stats;
}

// Функция для сохранения статистики
function saveStats($stats) {
    return file_put_contents(STATS_FILE, json_encode($stats, JSON_PRETTY_PRINT));
}

// Функция для увеличения счетчика запуска сайта
function incrementSiteRun($siteKey) {
    $stats = loadStats();
    
    // Увеличиваем общий счетчик
    $stats['total_runs'] = isset($stats['total_runs']) ? $stats['total_runs'] + 1 : 1;
    
    // Увеличиваем счетчик конкретного сайта
    if (!isset($stats['sites'][$siteKey])) {
        $stats['sites'][$siteKey] = 0;
    }
    $stats['sites'][$siteKey]++;
    
    // Добавляем временную метку последнего запуска
    $stats['last_run'] = array(
        'site' => $siteKey,
        'time' => date('Y-m-d H:i:s')
    );
    
    saveStats($stats);
    return $stats['sites'][$siteKey];
}

// Функция для проверки доступности сайта
function isSiteActive($url) {
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    curl_exec($ch);
    
    if (curl_error($ch)) {
        curl_close($ch);
        return false;
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode >= 200 && $httpCode < 400;
}

// Функция для сохранения массива сайтов в файл
function saveSiteList($sites) {
    $content = "<?php\n// Массив сайтов с описаниями\n\$siteUrls = array(\n";
    
    foreach ($sites as $key => $site) {
        $key = addslashes($key);
        $url = addslashes($site['url']);
        $desc = addslashes($site['desc']);
        
        $content .= "    '" . $key . "' => array(\n";
        $content .= "        'url' => '" . $url . "',\n";
        $content .= "        'desc' => '" . $desc . "',\n";
        $content .= "    ),\n";
    }
    
    $content .= ");\n?>";
    
    $filePath = __DIR__ . '/sitelist.php';
    return file_put_contents($filePath, $content) !== false;
}

// Загружаем файл с сайтами до обработки форм
$siteListFile = __DIR__ . '/sitelist.php';
$siteUrls = array();
$validationErrors = array();
$fileExists = file_exists($siteListFile);

if ($fileExists) {
    try {
        include $siteListFile;
        
        if (!isset($siteUrls)) {
            $validationErrors[] = "Ошибка: В файле sitelist.php не определена переменная \$siteUrls";
        }
    } catch (Exception $e) {
        $validationErrors[] = "Ошибка при загрузке файла: " . $e->getMessage();
    }
}

// Загружаем статистику
$stats = loadStats();

// Обработка запуска сайта через GET параметр
if (isset($_GET['run_site']) && isset($_GET['key'])) {
    $siteKey = $_GET['key'];
    if (isset($siteUrls[$siteKey])) {
        incrementSiteRun($siteKey);
        header('Location: ' . $siteUrls[$siteKey]['url']);
        exit;
    }
}

// Обработка добавления нового сайта
if (isset($_POST['add_site'])) {
    $newKey = isset($_POST['new_key']) ? trim($_POST['new_key']) : '';
    $newUrl = isset($_POST['new_url']) ? trim($_POST['new_url']) : '';
    $newDesc = isset($_POST['new_desc']) ? trim($_POST['new_desc']) : '';
    
    $errors = array();
    
    if (empty($newKey)) {
        $errors[] = "Ключ сайта не может быть пустым";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $newKey)) {
        $errors[] = "Ключ может содержать только буквы, цифры и подчеркивание";
    } elseif (isset($siteUrls[$newKey])) {
        $errors[] = "Сайт с таким ключом уже существует";
    }
    
    if (empty($newUrl)) {
        $errors[] = "URL не может быть пустым";
    } elseif (!filter_var($newUrl, FILTER_VALIDATE_URL)) {
        $errors[] = "Неверный формат URL";
    }
    
    if (empty($newDesc)) {
        $errors[] = "Описание не может быть пустым";
    }
    
    if (empty($errors)) {
        $siteUrls[$newKey] = array(
            'url' => $newUrl,
            'desc' => $newDesc
        );
        
        if (saveSiteList($siteUrls)) {
            $_SESSION['message'] = '✅ Сайт успешно добавлен';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '❌ Ошибка при сохранении';
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = implode('<br>', $errors);
        $_SESSION['message_type'] = 'danger';
    }
    
    header('Location: ' . $_SERVER['PHP_SELF'] . '?tab=edit');
    exit;
}

// Обработка редактирования сайта
if (isset($_POST['edit_site'])) {
    $oldKey = isset($_POST['old_key']) ? $_POST['old_key'] : '';
    $newKey = isset($_POST['key']) ? trim($_POST['key']) : '';
    $newUrl = isset($_POST['url']) ? trim($_POST['url']) : '';
    $newDesc = isset($_POST['desc']) ? trim($_POST['desc']) : '';
    
    $errors = array();
    
    if (empty($newKey)) {
        $errors[] = "Ключ сайта не может быть пустым";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $newKey)) {
        $errors[] = "Ключ может содержать только буквы, цифры и подчеркивание";
    } elseif ($newKey != $oldKey && isset($siteUrls[$newKey])) {
        $errors[] = "Сайт с таким ключом уже существует";
    }
    
    if (empty($newUrl)) {
        $errors[] = "URL не может быть пустым";
    } elseif (!filter_var($newUrl, FILTER_VALIDATE_URL)) {
        $errors[] = "Неверный формат URL";
    }
    
    if (empty($newDesc)) {
        $errors[] = "Описание не может быть пустым";
    }
    
    if (empty($errors)) {
        // Удаляем старую запись если ключ изменился
        if ($oldKey != $newKey) {
            unset($siteUrls[$oldKey]);
        }
        
        $siteUrls[$newKey] = array(
            'url' => $newUrl,
            'desc' => $newDesc
        );
        
        if (saveSiteList($siteUrls)) {
            $_SESSION['message'] = '✅ Сайт успешно обновлен';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '❌ Ошибка при сохранении';
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = implode('<br>', $errors);
        $_SESSION['message_type'] = 'danger';
    }
    
    header('Location: ' . $_SERVER['PHP_SELF'] . '?tab=edit');
    exit;
}

// Обработка удаления сайта
if (isset($_POST['delete_site']) && isset($_POST['key'])) {
    $key = $_POST['key'];
    
    if (isset($siteUrls[$key])) {
        unset($siteUrls[$key]);
        
        if (saveSiteList($siteUrls)) {
            $_SESSION['message'] = '✅ Сайт успешно удален';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '❌ Ошибка при сохранении';
            $_SESSION['message_type'] = 'danger';
        }
    }
    
    header('Location: ' . $_SERVER['PHP_SELF'] . '?tab=edit');
    exit;
}

// Обработка сохранения файла (прямое редактирование)
if (isset($_POST['save_sitelist']) && isset($_POST['content'])) {
    $content = $_POST['content'];
    $filePath = __DIR__ . '/sitelist.php';
    
    if (file_exists($filePath) && is_writable($filePath)) {
        try {
            if (file_put_contents($filePath, $content) !== false) {
                $_SESSION['message'] = '✅ Файл успешно сохранен';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = '❌ Ошибка при сохранении файла';
                $_SESSION['message_type'] = 'danger';
            }
        } catch (Exception $e) {
            $_SESSION['message'] = '❌ Ошибка: ' . $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = '❌ Файл sitelist.php не найден или защищен от записи';
        $_SESSION['message_type'] = 'danger';
    }
    
    header('Location: ' . $_SERVER['PHP_SELF'] . '?tab=edit');
    exit;
}

// Функция для проверки корректности массива сайтов
function validateSiteList($sites) {
    $errors = array();
    
    if (!is_array($sites)) {
        $errors[] = "Ошибка: Переменная \$siteUrls должна быть массивом";
        return $errors;
    }
    
    if (empty($sites)) {
        $errors[] = "Ошибка: Массив \$siteUrls пуст. Добавьте хотя бы один сайт";
        return $errors;
    }
    
    foreach ($sites as $key => $site) {
        if (!is_string($key) || empty($key)) {
            $errors[] = "Ошибка: Ключ сайта должен быть непустой строкой";
        }
        
        if (!is_array($site)) {
            $errors[] = "Ошибка: Элемент сайта '{$key}' должен быть массивом";
            continue;
        }
        
        if (!isset($site['url'])) {
            $errors[] = "Ошибка: У сайта '{$key}' отсутствует поле 'url'";
        } elseif (!is_string($site['url']) || empty($site['url'])) {
            $errors[] = "Ошибка: Поле 'url' сайта '{$key}' должно быть непустой строкой";
        } elseif (!filter_var($site['url'], FILTER_VALIDATE_URL)) {
            $errors[] = "Ошибка: Поле 'url' сайта '{$key}' имеет неверный формат URL";
        }
        
        if (!isset($site['desc'])) {
            $errors[] = "Ошибка: У сайта '{$key}' отсутствует поле 'desc'";
        } elseif (!is_string($site['desc'])) {
            $errors[] = "Ошибка: Поле 'desc' сайта '{$key}' должно быть строкой";
        }
    }
    
    return $errors;
}

// Функция для создания файла sitelist.php путём копирования из шаблона
function createSiteListFile() {
    $defaultFile = __DIR__ . '/sitelist_default.php';
    $targetFile = __DIR__ . '/sitelist.php';
    
    // Проверяем наличие файла-шаблона
    if (!file_exists($defaultFile)) {
        return false; // шаблон не найден – копирование невозможно
    }
    
    // Копируем файл
    try {
        return copy($defaultFile, $targetFile);
    } catch (Exception $e) {
        return false;
    }
}

// Проверяем, была ли нажата кнопка создания файла
if (isset($_POST['create_file'])) {
    $fileCreated = createSiteListFile();
    if ($fileCreated) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $creationError = "Не удалось создать файл. Проверьте права на запись в папку " . __DIR__;
    }
}

// Валидация после загрузки
if ($fileExists) {
    $validationErrors = validateSiteList($siteUrls);
}

// Если есть ошибки или файл не найден, показываем сообщение
if (!$fileExists || !empty($validationErrors)) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ошибка конфигурации</title>
        <link href="bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap-5.3.8-dist/css/all.min.css">
        <style>
            body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
            .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
            pre { background: #1e1e2f; color: #fff; padding: 15px; border-radius: 10px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-warning text-dark py-3">
                            <h3 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Ошибка конфигурации</h3>
                        </div>
                        <div class="card-body p-4">
                            <?php if (isset($creationError)): ?>
                                <div class="alert alert-danger"><?php echo $creationError; ?></div>
                            <?php endif; ?>
                            
                            <?php if (!$fileExists): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-times-circle me-2"></i>
                                    Файл <strong>sitelist.php</strong> не найден в папке <strong><?php echo __DIR__; ?></strong>
                                </div>
                                
                                <div class="text-center my-4">
                                    <form method="POST">
                                        <button type="submit" name="create_file" class="btn btn-success btn-lg px-5">
                                            <i class="fas fa-plus-circle me-2"></i>Создать файл sitelist.php
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($validationErrors)): ?>
                                <div class="alert alert-danger">
                                    <strong>Ошибки в файле sitelist.php:</strong>
                                    <ul class="mt-2 mb-0">
                                        <?php foreach ($validationErrors as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mt-4">
                                <a href="?" class="btn btn-primary">
                                    <i class="fas fa-redo me-2"></i>Проверить снова
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Формируем массив сайтов с проверкой доступности
$sites = array();
foreach ($siteUrls as $name => $data) {
    $sites[] = array(
        'name' => $name,
        'url' => $data['url'],
        'desc' => $data['desc'],
        'active' => isSiteActive($data['url'])
    );
}

// Подсчет статистики
$activeCount = 0;
foreach ($sites as $site) {
    if ($site['active']) $activeCount++;
}
$inactiveCount = count($sites) - $activeCount;

// Определяем, какой сайт редактируем
$editKey = isset($_GET['edit']) ? $_GET['edit'] : '';
$editSite = null;
if ($editKey && isset($siteUrls[$editKey])) {
    $editSite = $siteUrls[$editKey];
    $editSite['key'] = $editKey;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления сайтами</title>
    <link href="bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap-5.3.8-dist/css/all.min.css">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh; 
            padding: 20px;
        }
        .header-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 15px 25px;
            margin-bottom: 20px;
        }
        .site-icon {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: bold;
        }
        .url-box {
            background-color: #f8f9fa;
            padding: 4px 8px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .desc-box {
            font-size: 0.8rem;
            color: #6c757d;
            border-bottom: 1px dashed #dee2e6;
            padding-bottom: 8px;
            margin-bottom: 8px;
            line-height: 1.3;
            height: 2.6rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .stat-badge {
            font-size: 0.9rem;
            padding: 8px 15px;
            border-radius: 30px;
            color: white;
            font-weight: 600;
        }
        .card-site {
            border: none;
            border-radius: 12px;
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }
        .card-site:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .card-site.active { border-left-color: #28a745; }
        .card-site.inactive { border-left-color: #dc3545; }
        .nav-tabs .nav-link {
            color: white;
            border: none;
            padding: 10px 20px;
            margin-right: 5px;
        }
        .nav-tabs .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-bottom: 3px solid white;
        }
        .nav-tabs .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .alert-fixed {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .site-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        .site-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .action-btn {
            padding: 5px 10px;
            margin: 0 2px;
        }
        .modal-content {
            border-radius: 15px;
        }
        .form-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .run-counter {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Сообщения -->
        <?php if (isset($_SESSION['message'])): 
            $messageType = $_SESSION['message_type'];
            $icon = 'fa-info-circle';
            if ($messageType == 'success') $icon = 'fa-check-circle';
            elseif ($messageType == 'danger') $icon = 'fa-exclamation-circle';
            elseif ($messageType == 'warning') $icon = 'fa-exclamation-triangle';
        ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show alert-fixed" role="alert">
                <i class="fas <?php echo $icon; ?> me-2"></i>
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>
        
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'dashboard') ? 'active' : ''; ?>" 
                        id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab">
                    <i class="fas fa-home me-2"></i>Главная
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'stats') ? 'active' : ''; ?>" 
                        id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button" role="tab">
                    <i class="fas fa-chart-bar me-2"></i>Статистика
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'edit') ? 'active' : ''; ?>" 
                        id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button" role="tab">
                    <i class="fas fa-edit me-2"></i>Управление
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'docs') ? 'active' : ''; ?>" 
                        id="docs-tab" data-bs-toggle="tab" data-bs-target="#docs" type="button" role="tab">
                    <i class="fas fa-book me-2"></i>Документация
                </button>
            </li>
        </ul>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Главная Tab -->
            <div class="tab-pane fade <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'dashboard') ? 'show active' : ''; ?>" 
                 id="dashboard" role="tabpanel">
                <?php include 'dashboard-content.php'; ?>
            </div>
            
            <!-- Stats Tab -->
            <div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'stats') ? 'show active' : ''; ?>" 
                 id="stats" role="tabpanel">
                <?php include 'stats-content.php'; ?>
            </div>
            
            <!-- Edit Tab -->
            <div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'edit') ? 'show active' : ''; ?>" 
                 id="edit" role="tabpanel">
                <?php include 'edit-content.php'; ?>
            </div>
            
            <!-- Docs Tab -->
            <div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'docs') ? 'show active' : ''; ?>" 
                 id="docs" role="tabpanel">
                <?php include 'docs-content.php'; ?>
            </div>
        </div>
    </div>
    
    <!-- Форма удаления -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="delete_site" value="1">
        <input type="hidden" name="key" id="deleteKey" value="">
    </form>
    
    <script src="bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function refreshStatus() {
            var btn = event.currentTarget;
            btn.innerHTML = '<i class="fas fa-spinner fa-pulse me-1"></i> Обновление...';
            btn.disabled = true;
            setTimeout(function() { location.reload(); }, 500);
        }
        
        function confirmDelete(key) {
            if (confirm('Вы уверены, что хотите удалить сайт "' + key + '"?')) {
                document.getElementById('deleteKey').value = key;
                document.getElementById('deleteForm').submit();
            }
        }
        
        // Автообновление каждые 30 секунд только на главной
        <?php if (!isset($_GET['tab']) || $_GET['tab'] == 'dashboard'): ?>
        setTimeout(function() { location.reload(); }, 30000);
        <?php endif; ?>
        
        // Сохраняем активную вкладку в URL
        document.addEventListener('DOMContentLoaded', function() {
            var triggerTabList = document.querySelectorAll('#myTab button');
            for (var i = 0; i < triggerTabList.length; i++) {
                triggerTabList[i].addEventListener('shown.bs.tab', function(event) {
                    var tabName = event.target.id.replace('-tab', '');
                    var url = new URL(window.location);
                    url.searchParams.set('tab', tabName);
                    url.searchParams.delete('edit');
                    window.history.pushState({}, '', url);
                });
            }
        });
        
        // Автоматическое скрытие сообщений через 5 секунд
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            for (var i = 0; i < alerts.length; i++) {
                var alert = alerts[i];
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function(alert) { 
                    if (alert.parentNode) alert.remove(); 
                }, 500, alert);
            }
        }, 5000);
    </script>
</body>
</html>