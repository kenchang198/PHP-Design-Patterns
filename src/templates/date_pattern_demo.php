<?php
// パターン名とディレクトリ情報を取得
$patternName = isset($patternName) ? $patternName : '';
$dateDir = isset($dateDir) ? $dateDir : '';
$patternDir = isset($patternDir) ? $patternDir : '';

// READMEファイルの存在を確認
$readmePath = "$patternDir/README.md";
$hasReadme = file_exists($readmePath);
$readmeContent = $hasReadme ? file_get_contents($readmePath) : '';

// 実行スクリプトの存在を確認
$examplePath = "$patternDir/example.php";
$hasExample = file_exists($examplePath);

// パターンクラスファイルを取得
$patternFiles = glob("$patternDir/*.php");
// example.phpを除外
$patternFiles = array_filter($patternFiles, function($file) {
    return basename($file) !== 'example.php';
});

// ブログ記事を取得
$blogDir = dirname(dirname($patternDir)) . '/blog';
$blogFiles = glob("$blogDir/*.md");
$hasBlog = !empty($blogFiles);
$blogFile = $hasBlog ? $blogFiles[0] : null;
$blogContent = $hasBlog ? file_get_contents($blogFile) : '';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($patternName) ?> (<?= htmlspecialchars($dateDir) ?>) - PHPデザインパターン学習</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/styles/atom-one-dark.min.css">
    <style>
        pre code {
            border-radius: 4px;
        }
        .nav-tabs .nav-link {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
        .tab-content {
            border: 1px solid #dee2e6;
            border-top: none;
            padding: 1rem;
        }
        .date-badge {
            font-size: 0.9rem;
            background-color: #f8f9fa;
            padding: 0.3rem 0.6rem;
            border-radius: 0.5rem;
            color: #6c757d;
            margin-left: 1rem;
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
                        <a class="nav-link" href="/patterns">パターン一覧</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/calendar">カレンダー表示</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <?= htmlspecialchars($patternName) ?> パターン
                <span class="date-badge">学習日: <?= htmlspecialchars($dateDir) ?></span>
            </h1>
            <div>
                <a href="/calendar" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-calendar"></i> カレンダー表示
                </a>
                <a href="/patterns" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> パターン一覧
                </a>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="code-tab" data-bs-toggle="tab" data-bs-target="#code" type="button" role="tab">コード</button>
            </li>
            <?php if ($hasExample): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="result-tab" data-bs-toggle="tab" data-bs-target="#result" type="button" role="tab">実行結果</button>
            </li>
            <?php endif; ?>
            <?php if ($hasBlog): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="blog-tab" data-bs-toggle="tab" data-bs-target="#blog" type="button" role="tab">ブログ記事</button>
            </li>
            <?php endif; ?>
            <?php if ($hasReadme): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="readme-tab" data-bs-toggle="tab" data-bs-target="#readme" type="button" role="tab">README</button>
            </li>
            <?php endif; ?>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- コードタブ -->
            <div class="tab-pane fade show active" id="code" role="tabpanel">
                <?php if (empty($patternFiles)): ?>
                    <div class="alert alert-warning">
                        このパターンにはコードファイルが見つかりません。
                    </div>
                <?php else: ?>
                    <ul class="nav nav-pills mb-3">
                        <?php $first = true; ?>
                        <?php foreach ($patternFiles as $index => $file): ?>
                            <?php 
                            $fileName = basename($file);
                            $pillId = 'file-pill-' . $index;
                            $isActive = $first ? 'active' : '';
                            $first = false;
                            ?>
                            <li class="nav-item">
                                <button class="nav-link <?= $isActive ?>" 
                                        id="<?= $pillId ?>" 
                                        data-bs-toggle="pill" 
                                        data-bs-target="#pill-<?= $pillId ?>" 
                                        type="button"><?= htmlspecialchars($fileName) ?></button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="tab-content">
                        <?php $first = true; ?>
                        <?php foreach ($patternFiles as $index => $file): ?>
                            <?php 
                            $fileName = basename($file);
                            $pillId = 'file-pill-' . $index;
                            $isActive = $first ? 'show active' : '';
                            $first =