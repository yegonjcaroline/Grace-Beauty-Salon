<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        set_flash('Please log in first.');
        redirect('login.php');
    }
}

function require_role(array $roles): void
{
    require_login();

    if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
        http_response_code(403);
        exit('You do not have permission to view this page.');
    }
}