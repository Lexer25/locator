@echo off
chcp 1251 >nul
title Удаление локатора
color 0C

echo ========================================
echo    Удаление панели управления сайтами
echo ========================================
echo.

set XAMPP_HTDOCS=C:\xampp\htdocs
set INDEX_FILE=%XAMPP_HTDOCS%\index.php
set BACKUP_FILE=%XAMPP_HTDOCS%\index_before_locator.php

echo [1/2] Проверка наличия файлов...

:: Проверяем, есть ли что удалять
if not exist "%INDEX_FILE%" (
    echo [✗] Файл index.php не найден, возможно он уже удален
) else (
    echo [✓] Текущий index.php найден
)

if exist "%BACKUP_FILE%" (
    echo [✓] Резервная копия найдена
) else (
    echo [i] Резервная копия не найдена
)

echo.
echo [2/2] Восстановление исходного index.php...

:: Удаляем текущий index.php
if exist "%INDEX_FILE%" (
    del "%INDEX_FILE%" >nul 2>&1
    if %errorLevel% equ 0 (
        echo [✓] Текущий index.php удален
    ) else (
        echo [✗] Ошибка при удалении index.php
    )
)

:: Восстанавливаем из резервной копии
if exist "%BACKUP_FILE%" (
    ren "%BACKUP_FILE%" "index.php"
    if %errorLevel% equ 0 (
        echo [✓] Исходный index.php восстановлен из резервной копии
    ) else (
        echo [✗] Ошибка при восстановлении из резервной копии
    )
) else (
    echo [i] Резервная копия не найдена, создаем пустой index.php
    echo ^<?php // Пустой файл ^> > "%INDEX_FILE%"
    echo [✓] Создан пустой index.php
)

echo.
echo ========================================
echo    Операция завершена
echo ========================================
echo.
echo   Текущее состояние:
if exist "%INDEX_FILE%" (
    echo   - index.php: присутствует
) else (
    echo   - index.php: отсутствует
)
if exist "%BACKUP_FILE%" (
    echo   - Резервная копия: присутствует (нужно удалить вручную)
) else (
    echo   - Резервная копия: отсутствует
)
echo.
pause