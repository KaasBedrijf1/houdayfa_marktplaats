<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

logout_user();
flash_set('success', 'Je bent uitgelogd.');
header('Location: ' . base_url() . '/index.php');
exit;
