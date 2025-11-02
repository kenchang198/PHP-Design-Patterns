<?php
// 実装済みパターンを動的に取得
$patternDirs = glob(__DIR__ . '/../Patterns/*', GLOB_ONLYDIR);
$patterns = [];

foreach ($patternDirs as $dir) {
    $name = basename($dir);
    // パターンの種類を判定（ディレクトリ構造に基づく）
    $category = '';
    
    // 仮の判定ロジック - 実際の実装に合わせて調整してください
    if (in_array($name, ['Singleton', 'Factory', 'AbstractFactory', 'Builder', 'Prototype'])) {
        $category = 'creational';
    } elseif (in_array($name, ['Adapter', 'Bridge', 'Composite', 'Decorator', 'Facade', 'Flyweight', 'Proxy'])) {
        $category = 'structural';
    } else {
        $category = 'behavioral';
    }
    
    $patterns[] = [
        'name' => $name,
        'path' => strtolower($name),
        'category' => $category
    ];
}

// カテゴリー別に整理
$categories = [
    'creational' => [
        'title' => '生成パターン',
        'patterns' => array_filter($patterns, function($p) { return $p['category'] === 'creational'; })
    ],
    'structural' => [
        'title' => '構造パターン',
        'patterns' => array_filter($patterns, function($p) { return $p['category'] === 'structural'; })
    ],
    'behavioral' => [
        'title' => '振る舞いパターン',
        'patterns' => array_filter($patterns, function($p) { return $p['category'] === 'behavioral'; })
    ]
];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パターン一覧 - PHPデザインパターン学習</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .pattern-card {
            transition: transform 0.3s ease;
        }
        .pattern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">PHPデザインパターン学習</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">ホーム</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/patterns">パターン一覧</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/calendar">カレンダー表示</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="text-center mb-5">デザインパターン一覧</h1>

        <?php foreach ($categories as $category): ?>
            <h2 class="mt-5 mb-4"><?= $category['title'] ?></h2>
            
            <?php if (empty($category['patterns'])): ?>
                <div class="alert alert-info">
                    このカテゴリのパターンはまだ実装されていません。
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($category['patterns'] as $pattern): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card pattern-card h-100">
                                <div class="card-body">
                                    <h3 class="card-title"><?= $pattern['name'] ?></h3>
                                    <p class="card-text">
                                        <!-- パターンの説明を追加 -->
                                    </p>
                                    <a href="/<?= $pattern['path'] ?>" class="btn btn-primary">詳細を見る</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="mt-5">
            <h2 class="mb-4">今後実装予定のパターン</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            生成パターン
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Factory Method</li>
                            <li class="list-group-item">Abstract Factory</li>
                            <li class="list-group-item">Builder</li>
                            <li class="list-group-item">Prototype</li>
                            <li class="list-group-item">Singleton</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            構造パターン
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Adapter</li>
                            <li class="list-group-item">Bridge</li>
                            <li class="list-group-item">Composite</li>
                            <li class="list-group-item">Decorator</li>
                            <li class="list-group-item">Facade</li>
                            <li class="list-group-item">Flyweight</li>
                            <li class="list-group-item">Proxy</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            振る舞いパターン
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Chain of Responsibility</li>
                            <li class="list-group-item">Command</li>
                            <li class="list-group-item">Interpreter</li>
                            <li class="list-group-item">Iterator</li>
                            <li class="list-group-item">Mediator</li>
                            <li class="list-group-item">Memento</li>
                            <li class="list-group-item">Observer</li>
                            <li class="list-group-item">State</li>
                            <li class="list-group-item">Strategy</li>
                            <li class="list-group-item">Template Method</li>
                            <li class="list-group-item">Visitor</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6">
                    <h5>PHPデザインパターン学習</h5>
                    <p>このプロジェクトはPHPでのデザインパターン実装を学ぶための教材です。</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>© 2025 PHPデザインパターン学習</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
