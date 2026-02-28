<?php
// stats-content.php - содержимое страницы статистики
// Доступны переменные: $sites, $activeCount, $inactiveCount, $stats, $siteUrls
?>
<div class="row">
    <div class="col-md-6">
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-chart-line me-2"></i>Общая статистика</h4>
            <table class="table">
                <tr>
                    <td>Всего запусков:</td>
                    <td><strong><?php echo isset($stats['total_runs']) ? $stats['total_runs'] : 0; ?></strong></td>
                </tr>
                <?php if (isset($stats['last_run'])): ?>
                <tr>
                    <td>Последний запуск:</td>
                    <td>
                        <strong><?php echo htmlspecialchars($stats['last_run']['site']); ?></strong><br>
                        <small><?php echo $stats['last_run']['time']; ?></small>
                    </td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td>Всего сайтов:</td>
                    <td><strong><?php echo count($sites); ?></strong></td>
                </tr>
                <tr>
                    <td>Активных сайтов:</td>
                    <td><strong><?php echo $activeCount; ?></strong></td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-card">
            <h4 class="mb-3"><i class="fas fa-trophy me-2"></i>Топ сайтов</h4>
            <?php if (!empty($stats['sites'])): 
                // Сортируем по убыванию
                arsort($stats['sites']);
                $topSites = array_slice($stats['sites'], 0, 5, true);
            ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Сайт</th>
                            <th>Запусков</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($topSites as $siteKey => $count): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($siteKey); ?></td>
                            <td><span class="badge bg-primary"><?php echo $count; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">Пока нет данных о запусках</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="form-card">
    <h4 class="mb-3"><i class="fas fa-history me-2"></i>Детальная статистика по сайтам</h4>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Сайт</th>
                    <th>Запусков</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($siteUrls as $key => $site): 
                    $runCount = isset($stats['sites'][$key]) ? $stats['sites'][$key] : 0;
                    $active = isSiteActive($site['url']);
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($key); ?></strong></td>
                    <td>
                        <span class="badge <?php echo $runCount > 0 ? 'bg-info' : 'bg-secondary'; ?>">
                            <?php echo $runCount; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($active): ?>
                            <span class="badge bg-success">Активен</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Неактивен</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?run_site=1&key=<?php echo urlencode($key); ?>" 
                           class="btn btn-sm btn-success"
                           target="_blank">
                            <i class="fas fa-play"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>