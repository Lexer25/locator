<?php
// edit-content.php - содержимое страницы управления
// Доступны переменные: $siteUrls, $editSite, $stats, $siteListFile
?>
<!-- Форма добавления нового сайта -->
<div class="form-card">
    <h4 class="mb-3"><i class="fas fa-plus-circle text-success me-2"></i>Добавить новый сайт</h4>
    <form method="POST" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Ключ (идентификатор)</label>
            <input type="text" name="new_key" class="form-control" placeholder="например: mysite" required 
                   pattern="[a-zA-Z0-9_]+" title="Только буквы, цифры и подчеркивание">
        </div>
        <div class="col-md-4">
            <label class="form-label">URL</label>
            <input type="url" name="new_url" class="form-control" placeholder="http://..." required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Описание</label>
            <input type="text" name="new_desc" class="form-control" placeholder="Описание сайта" required>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" name="add_site" class="btn btn-success w-100">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </form>
</div>

<!-- Форма редактирования -->
<?php if ($editSite): ?>
<div class="form-card" style="border-left: 4px solid #ffc107;">
    <h4 class="mb-3"><i class="fas fa-edit text-warning me-2"></i>Редактирование: <?php echo htmlspecialchars($editSite['key']); ?></h4>
    <form method="POST" class="row g-3">
        <input type="hidden" name="old_key" value="<?php echo htmlspecialchars($editSite['key']); ?>">
        <div class="col-md-3">
            <label class="form-label">Ключ</label>
            <input type="text" name="key" class="form-control" value="<?php echo htmlspecialchars($editSite['key']); ?>" required
                   pattern="[a-zA-Z0-9_]+" title="Только буквы, цифры и подчеркивание">
        </div>
        <div class="col-md-4">
            <label class="form-label">URL</label>
            <input type="url" name="url" class="form-control" value="<?php echo htmlspecialchars($editSite['url']); ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Описание</label>
            <input type="text" name="desc" class="form-control" value="<?php echo htmlspecialchars($editSite['desc']); ?>" required>
        </div>
        <div class="col-md-1 d-flex align-items-end gap-1">
            <button type="submit" name="edit_site" class="btn btn-warning" title="Сохранить">
                <i class="fas fa-save"></i>
            </button>
            <a href="?tab=edit" class="btn btn-secondary" title="Отмена">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Таблица существующих сайтов -->
<div class="form-card">
    <h4 class="mb-3"><i class="fas fa-list me-2"></i>Существующие сайты</h4>
    <div class="table-responsive">
        <table class="table table-hover site-table">
            <thead>
                <tr>
                    <th>Ключ</th>
                    <th>URL</th>
                    <th>Описание</th>
                    <th>Статус</th>
                    <th>Запуски</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($siteUrls as $key => $site): 
                    $runCount = isset($stats['sites'][$key]) ? $stats['sites'][$key] : 0;
                ?>
                <tr>
                    <td><code><?php echo htmlspecialchars($key); ?></code></td>
                    <td><small><?php echo htmlspecialchars($site['url']); ?></small></td>
                    <td><?php echo htmlspecialchars($site['desc']); ?></td>
                    <td>
                        <?php 
                        $active = isSiteActive($site['url']);
                        if ($active): ?>
                            <span class="badge bg-success">Активен</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Неактивен</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-info"><?php echo $runCount; ?></span>
                    </td>
                    <td>
                        <a href="?tab=edit&edit=<?php echo urlencode($key); ?>" class="btn btn-sm btn-warning action-btn" title="Редактировать">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger action-btn" title="Удалить"
                                onclick="confirmDelete('<?php echo htmlspecialchars($key); ?>')">
                            <i class="fas fa-trash"></i>
                        </button>
                        <a href="?run_site=1&key=<?php echo urlencode($key); ?>" 
                           class="btn btn-sm btn-success action-btn" 
                           title="Запустить"
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

<!-- Форма прямого редактирования -->
<div class="form-card">
    <h4 class="mb-3">
        <a class="text-decoration-none" data-bs-toggle="collapse" href="#directEdit" role="button">
            <i class="fas fa-code me-2"></i>Прямое редактирование файла (для экспертов)
            <i class="fas fa-chevron-down ms-2"></i>
        </a>
    </h4>
    <div class="collapse" id="directEdit">
        <form method="POST">
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="10" style="font-family: monospace;"><?php 
                    echo htmlspecialchars(file_get_contents($siteListFile)); 
                ?></textarea>
            </div>
            <button type="submit" name="save_sitelist" class="btn btn-warning">
                <i class="fas fa-save me-2"></i>Сохранить изменения
            </button>
        </form>
    </div>
</div>