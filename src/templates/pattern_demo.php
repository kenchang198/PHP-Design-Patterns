<?php
// パターン名をURLから取得
$patternName = isset($patternName) ? $patternName : '';

// パターンディレクトリのパス
$patternDir = __DIR__ . "/../Patterns/$patternName";

// READMEファイルの存在を確認
$readmePath = "$patternDir/README.md";
$hasReadme = file_exists($readmePath);
$readmeContent = $hasReadme ? file_get_contents($readmePath) : '';

// 実行スクリプトの存在を確認
$examplePath = "$patternDir/example.php";
$hasExample = file_exists($examplePath);

// パターンクラスファイルを取得
$patternFiles = glob("$patternDir/*.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($patternName) ?> - PHPデザインパターン学習</title>
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?= htmlspecialchars($patternName) ?> パターン</h1>
            <a href="/patterns" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> パターン一覧に戻る
            </a>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <?php if ($hasReadme): ?>
                    <!-- Markdownパーサーが必要 - 簡易表示用 -->
                    <div class="markdown-content">
                        <pre><?= htmlspecialchars($readmeContent) ?></pre>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        このパターンの説明ファイル（README.md）はまだ作成されていません。
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h2 class="mb-3">実装サンプル</h2>
        
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <?php $first = true; ?>
            <?php foreach ($patternFiles as $index => $file): ?>
                <?php 
                $fileName = basename($file);
                $tabId = 'file-' . $index;
                $isActive = $first ? 'active' : '';
                $first = false;
                ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $isActive ?>" 
                            id="<?= $tabId ?>-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#<?= $tabId ?>" 
                            type="button" 
                            role="tab"><?= htmlspecialchars($fileName) ?></button>
                </li>
            <?php endforeach; ?>
            
            <?php if ($hasExample): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= empty($patternFiles) ? 'active' : '' ?>" 
                            id="example-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#example" 
                            type="button" 
                            role="tab">実行結果</button>
                </li>
            <?php endif; ?>
        </ul>
        
        <div class="tab-content" id="myTabContent">
            <?php $first = true; ?>
            <?php foreach ($patternFiles as $index => $file): ?>
                <?php 
                $fileName = basename($file);
                $tabId = 'file-' . $index;
                $isActive = $first ? 'show active' : '';
                $first = false;
                $content = file_get_contents($file);
                ?>
                <div class="tab-pane fade <?= $isActive ?>" 
                     id="<?= $tabId ?>" 
                     role="tabpanel">
                    <pre><code class="language-php"><?= htmlspecialchars($content) ?></code></pre>
                </div>
            <?php endforeach; ?>
            
            <?php if ($hasExample): ?>
                <div class="tab-pane fade <?= empty($patternFiles) ? 'show active' : '' ?>" 
                     id="example" 
                     role="tabpanel">
                    <div class="alert alert-info">
                        以下は「example.php」を実行した結果です：
                    </div>
                    <div class="border p-3 bg-light">
                        <?php
                        // 出力バッファリングを開始
                        ob_start();
                        
                        // 例外をキャッチして表示
                        try {
                            require $examplePath;
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger">エラー: ' . htmlspecialchars($e->getMessage()) . '</div>';
                        }
                        
                        // バッファの内容を取得して表示
                        $output = ob_get_clean();
                        echo $output;
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="mt-5">
            <h3>学びのポイント</h3>
            <ul class="list-group mb-4">
                <li class="list-group-item">
                    <strong>使用シーン:</strong> このパターンが特に有効な状況や問題を理解する
                </li>
                <li class="list-group-item">
                    <strong>メリット:</strong> パターン使用によるコードの改善点を確認する
                </li>
                <li class="list-group-item">
                    <strong>注意点:</strong> 過剰適用や誤用を避けるためのポイントを抑える
                </li>
                <li class="list-group-item">
                    <strong>実務活用:</strong> 実際のプロジェクトでの応用方法を考える
                </li>
            </ul>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/highlight.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelectorAll('pre code').forEach((el) => {
                hljs.highlightElement(el);
            });
        });
    </script>
</body>
</html>
