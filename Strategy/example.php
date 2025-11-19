<?php

require_once 'PaymentStrategy.php';

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║          Strategy パターン デモンストレーション             ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// 各セクションを順次読み込む
require_once __DIR__ . '/sections/01_before.php';
require_once __DIR__ . '/sections/02_strategy_pattern.php';
require_once __DIR__ . '/sections/03_ocp_demo.php';
require_once __DIR__ . '/sections/04_constructor_injection.php';
require_once __DIR__ . '/sections/05_error_handling.php';
require_once __DIR__ . '/sections/06_user_selection.php';
require_once __DIR__ . '/sections/07_summary.php';
