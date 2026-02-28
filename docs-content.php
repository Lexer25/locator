<?php
// docs-content.php - только содержимое документации (без HTML)
?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <!-- Карточка с описанием проекта -->
        <div class="form-card">
            <h3 class="mb-4"><i class="fas fa-info-circle text-primary me-2"></i>О проекте</h3>
            <p class="lead mb-4">
                Панель управления сайтами - это удобный инструмент для мониторинга и управления веб-проектами.
            </p>
            
            <div class="alert alert-info">
                <i class="fas fa-code-branch me-2"></i>
                <strong>Версия:</strong> 1.2.0 |
                <i class="fas fa-calendar ms-3 me-1"></i> 2026 |
                <i class="fas fa-globe ms-3 me-1"></i> <a href="http://www.artonit.ru" target="_blank">www.artonit.ru</a>
            </div>
        </div>
        
        <!-- Структура проекта -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-sitemap text-primary me-2"></i>Структура проекта</h4>
            
            <div class="alert alert-secondary">
                <i class="fas fa-folder-open me-2"></i>
                <strong>Корневая папка:</strong> C:\xampp\htdocs\locator\
            </div>
            
            <div class="tree-view">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-file-code text-primary me-2"></i>
                        <code>locator.php</code> - 
                        <small class="text-muted">Главный файл панели управления</small>
                    </li>
                    <li class="mb-2 ms-3">
                        <i class="fas fa-file-code text-success me-2"></i>
                        <code>docs-content.php</code> - 
                        <small class="text-muted">Содержимое документации</small>
                    </li>
                    <li class="mb-2 ms-3">
                        <i class="fas fa-file-code text-warning me-2"></i>
                        <code>sitelist.php</code> - 
                        <small class="text-muted">Список сайтов (генерируется автоматически)</small>
                    </li>
                    <li class="mb-2 ms-3">
                        <i class="fas fa-file-code text-info me-2"></i>
                        <code>stats.json</code> - 
                        <small class="text-muted">Статистика запусков (создается автоматически)</small>
                    </li>
                    <li class="mb-2 ms-3">
                        <i class="fas fa-file-code text-secondary me-2"></i>
                        <code>favicon.ico</code> - 
                        <small class="text-muted">Иконка сайта</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-folder text-danger me-2"></i>
                        <code>bootstrap-5.3.8-dist/</code> - 
                        <small class="text-muted">Локальные файлы Bootstrap</small>
                    </li>
                    <li class="mb-2 ms-4">
                        <i class="fas fa-folder-open text-danger me-2"></i>
                        <code>css/</code> - 
                        <small class="text-muted">Папка со стилями</small>
                    </li>
                    <li class="mb-2 ms-5">
                        <i class="fas fa-file text-danger me-2"></i>
                        <code>bootstrap.min.css</code> - 
                        <small class="text-muted">Основной файл стилей</small>
                    </li>
                    <li class="mb-2 ms-5">
                        <i class="fas fa-file text-danger me-2"></i>
                        <code>all.min.css</code> - 
                        <small class="text-muted">Стили для иконок Font Awesome</small>
                    </li>
                    <li class="mb-2 ms-4">
                        <i class="fas fa-folder-open text-danger me-2"></i>
                        <code>js/</code> - 
                        <small class="text-muted">Папка со скриптами</small>
                    </li>
                    <li class="mb-2 ms-5">
                        <i class="fas fa-file text-danger me-2"></i>
                        <code>bootstrap.bundle.min.js</code> - 
                        <small class="text-muted">JavaScript файл Bootstrap</small>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Детальное описание файлов -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-file-alt text-success me-2"></i>Детальное описание файлов</h4>
            
            <div class="accordion" id="fileAccordion">
                <!-- locator.php -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#file1">
                            <code class="me-2">locator.php</code> 
                            <span class="badge bg-danger ms-2">Основной файл</span>
                        </button>
                    </h2>
                    <div id="file1" class="accordion-collapse collapse show" data-bs-parent="#fileAccordion">
                        <div class="accordion-body">
                            <p><strong>Назначение:</strong> Главный исполняемый файл панели управления.</p>
                            <p><strong>Содержит:</strong></p>
                            <ul>
                                <li>Логику проверки доступности сайтов</li>
                                <li>Обработку форм добавления/редактирования</li>
                                <li>Систему статистики</li>
                                <li>Веб-интерфейс (HTML + Bootstrap)</li>
                                <li>JavaScript для автообновления</li>
                            </ul>
                            <p><strong>Важно:</strong> Не редактировать без необходимости!</p>
                        </div>
                    </div>
                </div>
                
                <!-- docs-content.php -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#file2">
                            <code class="me-2">docs-content.php</code>
                            <span class="badge bg-info ms-2">Документация</span>
                        </button>
                    </h2>
                    <div id="file2" class="accordion-collapse collapse" data-bs-parent="#fileAccordion">
                        <div class="accordion-body">
                            <p><strong>Назначение:</strong> Содержит текст документации.</p>
                            <p><strong>Содержит:</strong></p>
                            <ul>
                                <li>Описание проекта</li>
                                <li>Структуру файлов</li>
                                <li>Инструкции по использованию</li>
                                <li>Системные требования</li>
                            </ul>
                            <p><strong>Можно редактировать</strong> - это просто текст!</p>
                        </div>
                    </div>
                </div>
                
                <!-- sitelist.php -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#file3">
                            <code class="me-2">sitelist.php</code>
                            <span class="badge bg-warning text-dark ms-2">Конфигурация</span>
                        </button>
                    </h2>
                    <div id="file3" class="accordion-collapse collapse" data-bs-parent="#fileAccordion">
                        <div class="accordion-body">
                            <p><strong>Назначение:</strong> Хранит список всех сайтов.</p>
                            <p><strong>Формат:</strong></p>
                            <pre class="bg-light p-2 rounded"><code>$siteUrls = array(
    'ключ' => array(
        'url' => 'http://адрес:порт/путь',
        'desc' => 'Описание сайта',
    ),
);</code></pre>
                            <p><strong>Редактирование:</strong> Через вкладку "Управление" в интерфейсе.</p>
                        </div>
                    </div>
                </div>
                
                <!-- stats.json -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#file4">
                            <code class="me-2">stats.json</code>
                            <span class="badge bg-secondary ms-2">Статистика</span>
                        </button>
                    </h2>
                    <div id="file4" class="accordion-collapse collapse" data-bs-parent="#fileAccordion">
                        <div class="accordion-body">
                            <p><strong>Назначение:</strong> Хранит статистику запусков сайтов.</p>
                            <p><strong>Содержит:</strong></p>
                            <ul>
                                <li>Общее количество запусков</li>
                                <li>Счетчики по каждому сайту</li>
                                <li>Время последнего запуска</li>
                            </ul>
                            <p><strong>Создается автоматически</strong> при первом запуске.</p>
                        </div>
                    </div>
                </div>
                
                <!-- bootstrap папка -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#file5">
                            <code class="me-2">bootstrap-5.3.8-dist/</code>
                            <span class="badge bg-primary ms-2">Стили</span>
                        </button>
                    </h2>
                    <div id="file5" class="accordion-collapse collapse" data-bs-parent="#fileAccordion">
                        <div class="accordion-body">
                            <p><strong>Назначение:</strong> Локальные файлы Bootstrap для оформления.</p>
                            <p><strong>Структура:</strong></p>
                            <ul>
                                <li><code>css/bootstrap.min.css</code> - основные стили</li>
                                <li><code>css/all.min.css</code> - стили для иконок</li>
                                <li><code>js/bootstrap.bundle.min.js</code> - скрипты Bootstrap</li>
                            </ul>
                            <p><strong>Не требуется редактировать</strong> - используются как есть.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Инструкция по использованию -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-book-open text-warning me-2"></i>Как пользоваться</h4>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-home text-primary me-2"></i>
                                Главная
                            </h5>
                            <p class="card-text small">
                                Отображает все сайты в виде карточек. 
                                Виден статус (активен/неактивен), описание и URL.
                                Кнопка "Запустить" открывает сайт в новой вкладке.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-chart-bar text-success me-2"></i>
                                Статистика
                            </h5>
                            <p class="card-text small">
                                Показывает общую статистику запусков, 
                                топ популярных сайтов и детальную информацию 
                                по каждому проекту.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-edit text-warning me-2"></i>
                                Управление
                            </h5>
                            <p class="card-text small">
                                Позволяет добавлять, редактировать и удалять сайты.
                                Все изменения сохраняются в файл sitelist.php.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-sync-alt text-info me-2"></i>
                                Автообновление
                            </h5>
                            <p class="card-text small">
                                Статус сайтов автоматически обновляется каждые 30 секунд.
                                Также доступна ручная кнопка "Обновить".
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Системные требования -->
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-cog text-secondary me-2"></i>Системные требования</h4>
            
            <ul class="list-group">
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-3"></i>
                    <span><strong>PHP</strong> версии 5.6 или выше</span>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-3"></i>
                    <span><strong>Apache</strong> (XAMPP, OpenServer, и т.д.)</span>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-3"></i>
                    <span><strong>cURL</strong> для проверки доступности сайтов</span>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-3"></i>
                    <span><strong>Права на запись</strong> в папку locator</span>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-3"></i>
                    <span><strong>Современный браузер</strong> (Chrome, Firefox, Edge)</span>
                </li>
            </ul>
        </div>
        
        <!-- Информация о разработчике -->
        <div class="form-card text-center">
            <h4 class="mb-3"><i class="fas  text-danger me-2"></i>О разработчике</h4>
            <p>
                Разработано специально для удобного управления сайтами.
                <br>
                По вопросам и предложениям:
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="http://www.artonit.ru" target="_blank" class="btn btn-outline-primary">
                    <i class="fas fa-globe me-2"></i>www.artonit.ru
                </a>
            </div>
            <div class="mt-3 small text-muted">
                <i class="fas fa-code-branch me-1"></i> версия 1.2.0 | 
                <i class="fas fa-calendar me-1"></i> 2026
            </div>
        </div>
        
        <!-- Подсказка -->
        <div class="alert alert-warning mt-4">
            <i class="fas fa-lightbulb me-2"></i>
            <strong>Совет:</strong> Для прямого редактирования файлов используйте вкладку "Управление" -> 
            раздел "Прямое редактирование файла" (для экспертов).
        </div>
    </div>
</div>