<?php
//14.01.2026 Панель управления сайтами
// index.php - разместите в /xampp/htdocs/index.php

session_start();

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

// Функция для создания файла sitelist.php
function createSiteListFile() {
    $content = '<?php
// Массив сайтов с описаниями
$siteUrls = array(
    \'cch\' => array(
        \'url\' => \'http://127.0.0.1:8080/cch\',
        \'desc\' => \'Панель контроля Perco\',
    ),
    \'crm2\' => array(
        \'url\' => \'http://127.0.0.1:8080/crm2\',
        \'desc\' => \'Управление жильцами\',
    ),
    \'bas\' => array(
        \'url\' => \'http://127.0.0.1:8080/bas\',
        \'desc\' => \'Панель для bas-ip\',
    ),
    \'cvs\' => array(
        \'url\' => \'http://127.0.0.1:8080/cvs\',
        \'desc\' => \'Система видеонаблюдения\',
    ),
    \'parkresident\' => array(
        \'url\' => \'http://127.0.0.1:8080/parkresident\',
        \'desc\' => \'Парковочный комплекс\',
    ),
);
?>';
    
    $filePath = __DIR__ . '/sitelist.php';
    
    try {
        if (file_put_contents($filePath, $content) !== false) {
            return true;
        }
    } catch (Exception $e) {
        return false;
    }
    return false;
}

// Обработка сохранения файла
if (isset($_POST['save_sitelist']) && isset($_POST['content'])) {
    $content = $_POST['content'];
    $filePath = __DIR__ . '/sitelist.php';
    
    if (file_exists($filePath) && is_writable($filePath)) {
        try {
            if (file_put_contents($filePath, $content) !== false) {
                $_SESSION['message'] = 'Файл успешно сохранен';
                $_SESSION['message_type'] = 'success';
                
                // Проверяем синтаксис PHP
                $tempFile = tempnam(sys_get_temp_dir(), 'phpcheck');
                file_put_contents($tempFile, $content);
                exec('php -l ' . escapeshellarg($tempFile) . ' 2>&1', $output, $returnCode);
                unlink($tempFile);
                
                if ($returnCode !== 0) {
                    $_SESSION['message'] = 'Файл сохранен, но содержит ошибки PHP: ' . implode("\n", $output);
                    $_SESSION['message_type'] = 'warning';
                }
            } else {
                $_SESSION['message'] = 'Ошибка при сохранении файла';
                $_SESSION['message_type'] = 'danger';
            }
        } catch (Exception $e) {
            $_SESSION['message'] = 'Ошибка: ' . $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = 'Файл sitelist.php не найден или защищен от записи';
        $_SESSION['message_type'] = 'danger';
    }
    
    header('Location: ' . $_SERVER['PHP_SELF'] . '?tab=edit');
    exit;
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

// Загружаем файл с сайтами
$siteListFile = __DIR__ . '/sitelist.php';
$siteUrls = array();
$validationErrors = array();
$fileExists = file_exists($siteListFile);

if ($fileExists) {
    try {
        include $siteListFile;
        
        if (!isset($siteUrls)) {
            $validationErrors[] = "Ошибка: В файле sitelist.php не определена переменная \$siteUrls";
        } else {
            $validationErrors = validateSiteList($siteUrls);
        }
    } catch (Exception $e) {
        $validationErrors[] = "Ошибка при загрузке файла: " . $e->getMessage();
    }
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                                
                                <h5 class="mt-4">Или создайте файл вручную:</h5>
                                <pre>&lt;?php
// Массив сайтов с описаниями
$siteUrls = array(
    'cch' => array(
        'url' => 'http://127.0.0.1:8080/cch',
        'desc' => 'Панель контроля Perco',
    ),
    'crm2' => array(
        'url' => 'http://127.0.0.1:8080/crm2',
        'desc' => 'Управление жильцами',
    ),
    'bas' => array(
        'url' => 'http://127.0.0.1:8080/bas',
        'desc' => 'Панель для bas-ip',
    ),
    'cvs' => array(
        'url' => 'http://127.0.0.1:8080/cvs',
        'desc' => 'Система видеонаблюдения',
    ),
    'parkresident' => array(
        'url' => 'http://127.0.0.1:8080/parkresident',
        'desc' => 'Парковочный комплекс',
    ),
);
?&gt;</pre>
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
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Сообщения -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show alert-fixed" role="alert">
                <i class="fas <?php echo $_SESSION['message_type'] == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> me-2"></i>
                <?php echo nl2br(htmlspecialchars($_SESSION['message'])); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>
        
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'dashboard') ? 'active' : ''; ?>" 
                        id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab">
                    <i class="fas fa-tachometer-alt me-2"></i>Дашборд
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'edit') ? 'active' : ''; ?>" 
                        id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button" role="tab">
                    <i class="fas fa-edit me-2"></i>Редактор 
                </button>
            </li>
        </ul>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Dashboard Tab -->
            <div class="tab-pane fade <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'dashboard') ? 'show active' : ''; ?>" 
                 id="dashboard" role="tabpanel">
                <!-- Header -->
                <div class="header-card d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-globe text-white fs-1"></i>
                        <div>
                            <h1 class="text-white h3 mb-0">Панель управления сайтами</h1>
                            <small class="text-white-50">Все проекты в одном месте</small>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="stat-badge" style="background: #3498db;">
                            <i class="fas fa-globe me-1"></i> Всего: <?php echo count($sites); ?>
                        </span>
                        <span class="stat-badge" style="background: #27ae60;">
                            <i class="fas fa-check-circle me-1"></i> Активных: <?php echo $activeCount; ?>
                        </span>
                        <span class="stat-badge" style="background: #e67e22;">
                            <i class="fas fa-exclamation-circle me-1"></i> Неактивных: <?php echo $inactiveCount; ?>
                        </span>
                        <button class="btn btn-light" onclick="refreshStatus()">
                            <i class="fas fa-sync-alt me-1"></i> Обновить
                        </button>
                    </div>
                </div>
                
                <!-- Sites Grid -->
                <div class="row g-3">
                    <?php foreach ($sites as $site): ?>
                    <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card card-site h-100 <?php echo $site['active'] ? 'active' : 'inactive'; ?>">
                            <div class="card-body p-3">
                                <!-- Header -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="site-icon">
                                        <?php echo strtoupper(substr($site['name'], 0, 2)); ?>
                                    </div>
                                    <h6 class="card-title mb-0 fw-bold"><?php echo htmlspecialchars($site['name']); ?></h6>
                                </div>
                                
                                <!-- Description -->
                                <div class="desc-box" title="<?php echo htmlspecialchars($site['desc']); ?>">
                                    <i class="fas fa-info-circle me-1 small"></i>
                                    <?php echo htmlspecialchars($site['desc']); ?>
                                </div>
                                
                                <!-- URL -->
                                <div class="url-box mb-3" title="<?php echo htmlspecialchars($site['url']); ?>">
                                    <i class="fas fa-link me-1 small"></i>
                                    <?php echo htmlspecialchars($site['url']); ?>
                                </div>
                                
                                <!-- Status and button -->
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="badge <?php echo $site['active'] ? 'bg-success' : 'bg-danger'; ?> py-2 px-3">
                                        <?php echo $site['active'] ? 'Активен' : 'Неактивен'; ?>
                                    </span>
                                    <a href="<?php echo $site['url']; ?>" target="_blank" class="btn btn-sm btn-success px-3">
                                        <i class="fas fa-play me-1"></i> Запустить
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Footer -->
                <div class="text-center mt-4 text-white-50 small">
                    <i class="fas fa-clock me-1"></i> Последнее обновление: <?php echo date('Y-m-d H:i:s'); ?>
                </div>
            </div>
            
            <!-- Edit Tab -->
            <div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'edit') ? 'show active' : ''; ?>" 
                 id="edit" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Редактор файла sitelist.php</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="" id="editForm">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Содержимое файла sitelist.php:</label>
                                <textarea name="content" class="form-control" rows="20" style="font-family: monospace; font-size: 14px;"><?php 
                                    echo htmlspecialchars(file_get_contents($siteListFile)); 
                                ?></textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" name="save_sitelist" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Сохранить изменения
                                </button>
                                <a href="?tab=dashboard" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Отмена
                                </a>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Справка по формату:</h5>
                        <pre style="background: #f8f9fa; padding: 15px; border-radius: 10px; font-size: 13px;">
&lt;?php
// Массив сайтов с описаниями
$siteUrls = array(
    'ключ' => array(
        'url' => 'http://адрес:порт/путь',
        'desc' => 'Описание сайта',
    ),
    // Добавляйте новые сайты по аналогии
);
?&gt;</pre>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Совет:</strong> После редактирования файла переключитесь на вкладку "Дашборд" и нажмите "Обновить", чтобы увидеть изменения.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function refreshStatus() {
            const btn = event.currentTarget;
            btn.innerHTML = '<i class="fas fa-spinner fa-pulse me-1"></i> Обновление...';
            btn.disabled = true;
            setTimeout(() => location.reload(), 500);
        }
        
        // Автообновление каждые 30 секунд только на дашборде
        <?php if (!isset($_GET['tab']) || $_GET['tab'] == 'dashboard'): ?>
        setTimeout(() => location.reload(), 30000);
        <?php endif; ?>
        
        // Сохраняем активную вкладку в URL
        document.addEventListener('DOMContentLoaded', function() {
            const triggerTabList = document.querySelectorAll('#myTab button');
            triggerTabList.forEach(triggerEl => {
                triggerEl.addEventListener('shown.bs.tab', event => {
                    const tabName = event.target.id.replace('-tab', '');
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabName);
                    window.history.pushState({}, '', url);
                });
            });
        });
    </script>
</body>
</html>