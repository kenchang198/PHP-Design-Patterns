<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found - PHPデザインパターン学習</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="container py-5 text-center">
        <h1 class="display-1 mb-4">404</h1>
        <h2 class="mb-4">ページが見つかりません</h2>
        <p class="lead mb-5">
            お探しのページは存在しないか、移動した可能性があります。
        </p>
        <div>
            <a href="/" class="btn btn-primary me-3">ホームに戻る</a>
            <a href="/patterns" class="btn btn-outline-secondary">パターン一覧を見る</a>
        </div>
    </div>

    <footer class="bg-dark text-white mt-5 fixed-bottom">
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
