<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPデザインパターン学習</title>
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
                        <a class="nav-link active" href="/">ホーム</a>
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
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-4 mb-4">PHPデザインパターン学習</h1>
                <p class="lead mb-5">
                    このプロジェクトは、PHPを使用してGoFデザインパターンを学ぶためのプラットフォームです。
                    実際の例とコードサンプルを通じて、各デザインパターンの概念と実装方法を理解しましょう。
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="/patterns" class="btn btn-primary btn-lg">パターン一覧を見る</a>
                    <a href="/calendar" class="btn btn-outline-primary btn-lg">学習カレンダー</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Webブラウザでパターンを学ぶ</h3>
                    </div>
                    <div class="card-body">
                        <p>ブラウザからアクセスして、実装済みのデザインパターンを確認できます。</p>
                        <p><strong>アクセス方法：</strong></p>
                        <code>http://localhost:8083/パターン名</code><br>
                        <p class="mt-2"><small>例: <code>http://localhost:8083/singleton</code></small></p>
                        <a href="/patterns" class="btn btn-primary">実装済みパターンを確認</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title mb-0">CLIでコードを実行する</h3>
                    </div>
                    <div class="card-body">
                        <p>日付ディレクトリのコードサンプルは、Dockerコンテナ内でCLIから実行します。</p>
                        <p><strong>コンテナにログイン：</strong></p>
                        <code>docker-compose exec php bash</code>
                        <p><strong>コード実行：</strong></p>
                        <code>cd /var/www/html/2025-04-18/code/singleton && php example.php</code>
                        <a href="/calendar" class="btn btn-success mt-3">学習カレンダーを見る</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <h2 class="text-center mb-5">デザインパターンカテゴリ</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card pattern-card h-100">
                    <div class="card-body text-center">
                        <h3 class="card-title">生成パターン</h3>
                        <p class="card-text">オブジェクトの作成メカニズムに関するパターン</p>
                        <ul class="list-group list-group-flush text-start">
                            <li class="list-group-item">Factory Method</li>
                            <li class="list-group-item">Abstract Factory</li>
                            <li class="list-group-item">Builder</li>
                            <li class="list-group-item">Prototype</li>
                            <li class="list-group-item">Singleton</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card pattern-card h-100">
                    <div class="card-body text-center">
                        <h3 class="card-title">構造パターン</h3>
                        <p class="card-text">クラスとオブジェクトの構成に関するパターン</p>
                        <ul class="list-group list-group-flush text-start">
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
            </div>
            <div class="col-md-4 mb-4">
                <div class="card pattern-card h-100">
                    <div class="card-body text-center">
                        <h3 class="card-title">振る舞いパターン</h3>
                        <p class="card-text">オブジェクト間の通信に関するパターン</p>
                        <ul class="list-group list-group-flush text-start">
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
