<?php
// docs-content.php - Техническая документация
// Обновлено для версии 3.0.0
$version=3.0;
?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <!-- Технический заголовок -->
        <div class="form-card">
            <div class="d-flex align-items-center mb-3">
                <i class="fas fa-code-branch fs-1 text-primary me-3"></i>
                <div>
                    <h3 class="mb-1">Техническая документация</h3>
                    <p class="text-muted mb-0">Панель управления сайтами | &copy; 2026 Artonit</p>
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

        <!-- 3. Структура файлов (ОБНОВЛЕНО) -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-folder-tree text-primary me-2"></i>3. Структура файлов</h4>
            
            <div class="bg-light p-3 rounded mb-3">
                <pre class="mb-0"><code>C:\xampp\htdocs\
├── index.php                    # Редирект на /locator/locator.php
│
└── locator\                     # Корневая директория проекта
    ├── locator.php              # Основной исполняемый модуль (логика + каркас)
    │
    ├── dashboard-content.php    # Содержимое вкладки "Главная"
    ├── stats-content.php        # Содержимое вкладки "Статистика"
    ├── edit-content.php         # Содержимое вкладки "Управление"
    ├── docs-content.php         # Содержимое вкладки "Документация" (этот файл)
    │
    ├── sitelist.php             # Конфигурация сайтов (генерируется)
    ├── stats.json                # База данных статистики (создается)
    ├── favicon.ico               # Графический ресурс
    │
    └── bootstrap-5.3.8-dist\     # Фреймворк Bootstrap
        ├── css\                   # Таблицы стилей
        │	├── bootstrap.min.css  # таблицы стилей
        │	└── all.min.css  # таблицы стилей
        └── js\                     # JavaScript-модули
        │	└── bootstrap.bundle.min.js #JS скрипты
        └── webfonts\                     # JavaScript-модули
            └── fa-solid-900.woff2 # файл шрифта (веб-формат WOFF2), который содержит набор иконок
          </code></pre>
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
                        <td>Основной контроллер приложения (логика + подключение вкладок)</td>
                        <td>644 (чтение)</td>
                    </tr>
                    <tr>
                        <td><code>dashboard-content.php</code></td>
                        <td>Шаблон</td>
                        <td>Содержимое вкладки "Главная"</td>
                        <td>644 (чтение)</td>
                    </tr>
                    <tr>
                        <td><code>stats-content.php</code></td>
                        <td>Шаблон</td>
                        <td>Содержимое вкладки "Статистика"</td>
                        <td>644 (чтение)</td>
                    </tr>
                    <tr>
                        <td><code>edit-content.php</code></td>
                        <td>Шаблон</td>
                        <td>Содержимое вкладки "Управление"</td>
                        <td>644 (чтение)</td>
                    </tr>
                    <tr>
                        <td><code>docs-content.php</code></td>
                        <td>Шаблон</td>
                        <td>Содержимое вкладки "Документация"</td>
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
            
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Модульная структура:</strong> Каждая вкладка вынесена в отдельный файл. Это позволяет легко редактировать содержимое не затрагивая основную логику.
            </div>
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
                <strong>Рекомендация:</strong> Используйте вкладку "Управление" (<code>edit-content.php</code>) для редактирования. Прямое редактирование файла может привести к синтаксическим ошибкам.
            </div>
        </div>

        <!-- 5. Описание вкладок (НОВОЕ) -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-window-maximize text-primary me-2"></i>5. Описание вкладок</h4>
            
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Вкладка</th>
                        <th>Файл</th>
                        <th>Функционал</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="fas fa-home text-primary me-1"></i> Главная</td>
                        <td><code>dashboard-content.php</code></td>
                        <td>Отображение всех сайтов в виде карточек с индикацией статуса и счетчиками запусков</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-chart-bar text-success me-1"></i> Статистика</td>
                        <td><code>stats-content.php</code></td>
                        <td>Общая статистика, топ сайтов, детальная информация по каждому проекту</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-edit text-warning me-1"></i> Управление</td>
                        <td><code>edit-content.php</code></td>
                        <td>Добавление, редактирование и удаление сайтов, прямое редактирование файла</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-book text-info me-1"></i> Документация</td>
                        <td><code>docs-content.php</code></td>
                        <td>Техническая документация проекта (текущий файл)</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 6. API и методы -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-plug text-primary me-2"></i>6. Методы взаимодействия</h4>
            
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
            
            <h6 class="mb-2 mt-3">Обработка форм (POST):</h6>
            <table class="table table-bordered">
                <tr>
                    <th>Действие</th>
                    <th>Параметры</th>
                    <th>Обработчик</th>
                </tr>
                <tr>
                    <td>Добавление сайта</td>
                    <td><code>add_site, new_key, new_url, new_desc</code></td>
                    <td><code>locator.php</code></td>
                </tr>
                <tr>
                    <td>Редактирование сайта</td>
                    <td><code>edit_site, old_key, key, url, desc</code></td>
                    <td><code>locator.php</code></td>
                </tr>
                <tr>
                    <td>Удаление сайта</td>
                    <td><code>delete_site, key</code></td>
                    <td><code>locator.php</code></td>
                </tr>
            </table>
        </div>

        <!-- 7. Система статистики -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-chart-bar text-primary me-2"></i>7. Система статистики</h4>
            
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

        <!-- 8. Диагностика -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-exclamation-triangle text-primary me-2"></i>8. Диагностика неисправностей</h4>
            
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
                    <tr>
                        <td>Не открывается вкладка</td>
                        <td>Ошибка в подключении файла</td>
                        <td>Проверить наличие соответствующего <code>*-content.php</code> файла</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 9. Обновление и поддержка (ОБНОВЛЕНО) -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-sync-alt text-primary me-2"></i>9. Обновление и поддержка</h4>
            
            <div class="row">
                <div class="col-md-6">
                    <h6>Процедура обновления:</h6>
                    <ol>
                        <li>Создать резервную копию папки /locator/</li>
                        <li>Сохранить существующие <code>sitelist.php</code> и <code>stats.json</code></li>
                        <li>Загрузить новые файлы, кроме сохраненных</li>
                        <li>Проверить работоспособность всех вкладок</li>
                    </ol>
                </div>
                <div class="col-md-6">
                    <h6>Модификация вкладок:</h6>
                    <ul>
                        <li><strong>Главная</strong> - править <code>dashboard-content.php</code></li>
                        <li><strong>Статистика</strong> - править <code>stats-content.php</code></li>
                        <li><strong>Управление</strong> - править <code>edit-content.php</code></li>
                        <li><strong>Документация</strong> - править <code>docs-content.php</code></li>
                    </ul>
                </div>
            </div>
            
            <hr>
            <div class="text-muted small">
                <i class="fas fa-calendar-alt me-1"></i> Дата релиза: 28.02.2026 |
                <i class="fas fa-database me-1"></i> Модульная структура |
                <i class="fas fa-globe me-1"></i> <a href="http://www.artonit.ru" target="_blank">www.artonit.ru</a>
            </div>
        </div>
        
        <!-- Схема взаимодействия (НОВОЕ) -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-project-diagram text-primary me-2"></i>10. Схема взаимодействия</h4>
            
            <div class="bg-light p-3 rounded">
                <pre class="mb-0"><code>locator.php (главный контроллер)
       │
       ├── include dashboard-content.php  (отображение главной)
       ├── include stats-content.php      (отображение статистики)
       ├── include edit-content.php       (отображение управления)
       ├── include docs-content.php       (отображение документации)
       │
       ├── читает/пишет sitelist.php      (конфигурация сайтов)
       └── читает/пишет stats.json        (статистика)</code></pre>
            </div>
            
            <p class="mt-3 small text-muted">
                <i class="fas fa-arrow-right text-success me-1"></i> 
                Все вкладки подключаются динамически, что обеспечивает модульность и простоту поддержки.
            </p>
        </div>
    </div>
</div>