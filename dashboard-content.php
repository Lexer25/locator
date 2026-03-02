<?php
// dashboard-content.php - содержимое главной страницы
// Доступны переменные: $sites, $activeCount, $inactiveCount, $stats
?>
<!-- Header -->
<div class="header-card d-flex flex-wrap align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
        <i class="fas fa-globe text-white fs-1"></i>
        <div>
            <div class="d-flex align-items-center gap-2">
                <h1 class="text-white h3 mb-0">Панель управления сайтами</h1>
                <span class="badge bg-warning text-dark px-3 py-2">v 3.0</span>
            </div>
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
        <span class="stat-badge" style="background: #9b59b6;">
            <i class="fas fa-chart-bar me-1"></i> Запусков: <?php echo isset($stats['total_runs']) ? $stats['total_runs'] : 0; ?>
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
                
                <!-- Status and button with counter -->
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge <?php echo $site['active'] ? 'bg-success' : 'bg-danger'; ?> py-2 px-3">
                            <?php echo $site['active'] ? 'Активен' : 'Неактивен'; ?>
                        </span>
                        <?php if (isset($stats['sites'][$site['name']])): ?>
                            <small class="d-block text-muted mt-1 run-counter">
                                <i class="fas fa-play-circle me-1"></i> Запусков: <?php echo $stats['sites'][$site['name']]; ?>
                            </small>
                        <?php endif; ?>
                    </div>
                    <a href="?run_site=1&key=<?php echo urlencode($site['name']); ?>" 
                       class="btn btn-sm btn-success px-3" 
                       target="_blank">
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
    
    | <a href="http://www.artonit.ru" target="_blank" class="text-white-50">www.artonit.ru</a>
</div>