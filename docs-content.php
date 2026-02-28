<?php
// docs-content.php - Техническая документация
?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <!-- Технический заголовок -->
        <div class="form-card">
            <div class="d-flex align-items-center mb-3">
                <i class="fas fa-code-branch fs-1 text-primary me-3"></i>
                <div>
                    <h3 class="mb-1">Техническая документация</h3>
                    <p class="text-muted mb-0">Панель управления сайтами v1.2.0 | &copy; 2026 ArtonIT</p>
                </div>
            </div>
            <hr>
            <div class="row text-center">
                <div class="col">
                    <small class="text-muted">Последнее обновление: 28.02.2026</small>
                </div>
            </div>
        </div>

        <!-- 1. Назначение системы -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-bullseye text-primary me-2"></i>1. Назначение системы</h4>
            <p>Панель управления сайтами предназначена для централизованного мониторинга и администрирования веб-проектов. Система обеспечивает:</p>
            <ul class="list-group list-group-flush">
                <li class="list-group-item bg-transparent"><i class="fas fa-check text-success me-2"></i>Контроль доступности HTTP-сервисов</li>
                <li class="list-group-item bg-transparent"><i class="fas fa-check text-success me-2"></i>Учет статистики запусков</li>
                <li class="list-group-item bg-transparent"><i class="fas fa-check text-success me-2"></i>Управление конфигурацией проектов</li>
                <li class="list-group-item bg-transparent"><i class="fas fa-check text-success me-2"></i>Визуализацию состояния сервисов</li>
            </ul>
        </div>

        <!-- 2. Системные требования -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-microchip text-primary me-2"></i>2. Системные требования</h4>
            
            <table class="table table-bordered">
                <tr>
                    <th style="width: 200px;">Компонент</th>
                    <th>Требование</th>
                    <th>Примечание</th>
                </tr>
                <tr>
                    <td>Веб-сервер</td>
                    <td>Apache 2.4+</td>
                    <td>XAMPP, OpenServer, Denwer</td>
                </tr>
                <tr>
                    <td>PHP</td>
                    <td>Версия 5.6 - 8.x</td>
                    <td>Требуется модуль cURL</td>
                </tr>
                <tr>
                    <td>Файловая система</td>
                    <td>Права на запись</td>
                    <td>Для папки /locator/</td>
                </tr>
                <tr>
                    <td>Браузер</td>
                    <td>Chrome 90+, Firefox 88+, Edge 90+</td>
                    <td>Требуется JavaScript</td>
                </tr>
            </table>
        </div>

        <!-- 3. Структура файлов -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-folder-tree text-primary me-2"></i>3. Структура файлов</h4>
            
            <div class="bg-light p-3 rounded mb-3">
                <pre class="mb-0"><code>C:\xampp\htdocs\
├── index.php                    # Редирект на /locator/locator.php
│
└── locator\                     # Корневая директория проекта
    ├── locator.php              # Основной исполняемый модуль
    ├── docs-content.php         # Модуль документации
    ├── sitelist.php             # Конфигурация сайтов
    ├── stats.json               # База данных статистики
    ├── favicon.ico              # Графический ресурс
    │
    └── bootstrap-5.3.8-dist\    # Фреймворк Bootstrap
        ├── css\                  # Таблицы стилей
        └── js\                    # JavaScript-модули</code></pre>
            </div>

            <h5 class="mt-4 mb-3">Спецификация файлов:</h5>
            
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Файл</th>
                        <th>Тип</th>
                        <th>Назначение</th>
                        <th>Права доступа</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>locator.php</code></td>
                        <td>Исполняемый</td>
                        <td>Основной контроллер приложения</td>
                        <td>644 (чтение)</td>
                    </tr>
                    <tr>
                        <td><code>docs-content.php</code></td>
                        <td>Документация</td>
                        <td>Содержимое раздела документации</td>
                        <td>644 (чтение)</td>
                    </tr>
                    <tr>
                        <td><code>sitelist.php</code></td>
                        <td>Конфигурация</td>
                        <td>Хранение массива сайтов</td>
                        <td>666 (чтение/запись)</td>
                    </tr>
                    <tr>
                        <td><code>stats.json</code></td>
                        <td>Данные</td>
                        <td>Хранение статистики в JSON</td>
                        <td>666 (чтение/запись)</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 4. Конфигурация сайтов -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-cogs text-primary me-2"></i>4. Конфигурация сайтов</h4>
            
            <p>Файл <code>sitelist.php</code> содержит массив следующей структуры:</p>
            
            <div class="bg-dark text-white p-3 rounded mb-3">
                <pre class="mb-0"><code>&lt;?php
$siteUrls = array(
    'идентификатор_1' => array(
        'url' => 'http://domain.com:port/path',
        'desc' => 'Описание проекта'
    ),
    'идентификатор_2' => array(
        'url' => 'http://domain2.com:port/path',
        'desc' => 'Описание проекта'
    )
);
?&gt;</code></pre>
            </div>
            
            <p><strong>Параметры:</strong></p>
            <ul>
                <li><code>идентификатор</code> - уникальный ключ (только латиница, цифры, _)</li>
                <li><code>url</code> - полный URL для проверки и запуска</li>
                <li><code>desc</code> - текстовое описание проекта</li>
            </ul>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Рекомендация:</strong> Используйте вкладку "Управление" для редактирования. Прямое редактирование файла может привести к синтаксическим ошибкам.
            </div>
        </div>

        <!-- 5. API и методы -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-plug text-primary me-2"></i>5. Методы взаимодействия</h4>
            
            <h6 class="mb-2">Параметры GET-запросов:</h6>
            <table class="table table-bordered">
                <tr>
                    <th>Параметр</th>
                    <th>Описание</th>
                    <th>Пример</th>
                </tr>
                <tr>
                    <td><code>tab</code></td>
                    <td>Выбор вкладки интерфейса</td>
                    <td><code>?tab=dashboard|stats|edit|docs</code></td>
                </tr>
                <tr>
                    <td><code>run_site</code></td>
                    <td>Запуск сайта с подсчетом статистики</td>
                    <td><code>?run_site=1&key=cch</code></td>
                </tr>
                <tr>
                    <td><code>edit</code></td>
                    <td>Редактирование конкретного сайта</td>
                    <td><code>?tab=edit&edit=cch</code></td>
                </tr>
            </table>
        </div>

        <!-- 6. Система статистики -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-chart-bar text-primary me-2"></i>6. Система статистики</h4>
            
            <p>Статистика сохраняется в файл <code>stats.json</code> в формате:</p>
            
            <div class="bg-dark text-white p-3 rounded mb-3">
                <pre class="mb-0"><code>{
    "total_runs": 42,
    "sites": {
        "cch": 15,
        "crm2": 12,
        "bas": 8,
        "cvs": 7
    },
    "last_run": {
        "site": "cch",
        "time": "2026-02-28 15:30:00"
    }
}</code></pre>
            </div>
            
            <p><strong>Поля:</strong></p>
            <ul>
                <li><code>total_runs</code> - общее количество запусков</li>
                <li><code>sites</code> - счетчики по каждому сайту</li>
                <li><code>last_run</code> - информация о последнем запуске</li>
            </ul>
        </div>

        <!-- 7. Диагностика -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-exclamation-triangle text-primary me-2"></i>7. Диагностика неисправностей</h4>
            
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Симптом</th>
                        <th>Причина</th>
                        <th>Решение</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Пустая страница</td>
                        <td>Ошибка PHP</td>
                        <td>Проверить error_log, включить display_errors</td>
                    </tr>
                    <tr>
                        <td>Не сохраняются сайты</td>
                        <td>Нет прав на запись</td>
                        <td>Выдать права 666 на sitelist.php</td>
                    </tr>
                    <tr>
                        <td>Все сайты неактивны</td>
                        <td>cURL не работает</td>
                        <td>Проверить включение php_curl.dll</td>
                    </tr>
                    <tr>
                        <td>Статистика не обновляется</td>
                        <td>Нет прав на stats.json</td>
                        <td>Создать файл с правами 666</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 8. Обновление и поддержка -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-sync-alt text-primary me-2"></i>8. Обновление и поддержка</h4>
            
            <div class="row">
                <div class="col-md-6">
                    <h6>Процедура обновления:</h6>
                    <ol>
                        <li>Создать резервную копию папки /locator/</li>
                        <li>Загрузить новые файлы</li>
                        <li>Сохранить существующий sitelist.php</li>
                        <li>Проверить работоспособность</li>
                    </ol>
                </div>
                <div class="col-md-6">
                    <h6>Контакты разработчика:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-globe me-2"></i> www.artonit.ru</li>
                    </ul>
                </div>
            </div>
            
            <hr>
            <div class="text-muted small">
                <i class="fas fa-code-branch me-1"></i> Версия: 1.2.0 | 
                <i class="fas fa-calendar-alt me-1"></i> Дата релиза: 28.02.2026 |
                <i class="fas fa-database me-1"></i> Стабильный релиз
            </div>
        </div>
    </div>
</div>