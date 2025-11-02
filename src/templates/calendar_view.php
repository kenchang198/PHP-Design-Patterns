<?php
// 日付ディレクトリの一覧を取得
$dateDirs = glob(__DIR__ . '/../../[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]', GLOB_ONLYDIR);
$lessons = [];

foreach ($dateDirs as $dir) {
    $dateStr = basename($dir);
    $patterns = [];
    
    // パターンディレクトリを取得
    $patternDirs = glob("$dir/code/*", GLOB_ONLYDIR);
    foreach ($patternDirs as $patternDir) {
        $patternName = basename($patternDir);
        
        // ブログ記事を探す
        $blogFiles = glob("$dir/blog/*.md");
        $hasBlog = !empty($blogFiles);
        $blogFile = $hasBlog ? basename($blogFiles[0]) : null;
        
        $patterns[] = [
            'name' => $patternName,
            'path' => $patternDir,
            'blog' => $blogFile,
            'hasBlog' => $hasBlog
        ];
    }
    
    $lessons[] = [
        'date' => $dateStr,
        'patterns' => $patterns
    ];
}

// 日付で降順ソート（最新の学習日が先頭に）
usort($lessons, function($a, $b) {
    return strcmp($b['date'], $a['date']);
});
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学習カレンダー - PHPデザインパターン学習</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .calendar-card {
            transition: transform 0.3s ease;
        }
        .calendar-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .date-header {
            border-bottom: 2px solid #f8f9fa;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
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
                        <a class="nav-link active" href="/calendar">カレンダー表示</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>学習カレンダー</h1>
            <div>
                <a href="/" class="btn btn-outline-secondary">
                    <i class="bi bi-house"></i> ホームに戻る
                </a>
            </div>
        </div>

        <div class="alert alert-info mb-4">
            <h4 class="alert-heading"><i class="bi bi-info-circle"></i> CLI実行ガイド</h4>
            <p>日付ディレクトリのコードサンプルはDockerコンテナ内でCLIから実行することを想定しています。</p>
            <hr>
            <p class="mb-0">
                <strong>コンテナにログインする：</strong><br>
                <code>docker-compose exec php bash</code>
            </p>
            <p class="mb-0 mt-2">
                <strong>PHPコードを実行する：</strong><br>
                <code>cd /var/www/html/2025-04-18/code/singleton && php example.php</code>
            </p>
        </div>

        <?php if (empty($lessons)): ?>
            <div class="alert alert-warning">
                まだ学習記録がありません。「今日のカリキュラムを開始します」とAIに指示して最初の学習を始めましょう。
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($lessons as $lesson): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card calendar-card h-100">
                            <div class="card-header bg-light">
                                <h3 class="card-title mb-0">
                                    <i class="bi bi-calendar-event"></i> 
                                    <?= htmlspecialchars($lesson['date']) ?>
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php if (empty($lesson['patterns'])): ?>
                                    <p class="text-muted">この日の学習内容はまだ記録されていません。</p>
                                <?php else: ?>
                                    <h5 class="mb-3">学習したパターン</h5>
                                    <ul class="list-group">
                                        <?php foreach ($lesson['patterns'] as $pattern): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?= htmlspecialchars($pattern['name']) ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <code><?= str_replace('/var/www/html/', '', $pattern['path']) ?></code>
                                                    </small>
                                                </div>
                                                <div>
                                                    <?php if ($pattern['hasBlog']): ?>
                                                        <a href="#" class="btn btn-sm btn-outline-primary" 
                                                           data-bs-toggle="tooltip" 
                                                           title="ブログ記事を見る">
                                                            <i class="bi bi-file-text"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <button class="btn btn-sm btn-outline-secondary ms-1"
                                                            data-bs-toggle="tooltip"
                                                            title="CLI実行コマンドをコピー"
                                                            onclick="copyToClipboard('cd /var/www/html/<?= $lesson['date'] ?>/code/<?= $pattern['name'] ?> && php example.php')">
                                                        <i class="bi bi-terminal"></i>
                                                    </button>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-white">
                                <small class="text-muted">
                                    CLI実行コマンド:
                                    <code>cd /var/www/html/<?= $lesson['date'] ?> && ls -la</code>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
        
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('コマンドがクリップボードにコピーされました！');
            }, function(err) {
                console.error('クリップボードへのコピーに失敗しました: ', err);
            });
        }
    </script>
</body>
</html>
