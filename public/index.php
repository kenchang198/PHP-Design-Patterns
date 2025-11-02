<?php
/**
 * PHPデザインパターン学習用フロントコントローラー
 */

// オートローダーの読み込み
$autoloadPath = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    echo '<div style="padding: 20px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;">';
    echo '<h1>Composerの依存関係がインストールされていません</h1>';
    echo '<p>vendor/autoload.php ファイルが見つかりません。次のコマンドを実行してください：</p>';
    echo '<pre>cd ' . dirname(__DIR__) . ' && composer install</pre>';
    echo '<p>または、Dockerコンテナを再起動してください：</p>';
    echo '<pre>docker-compose down && docker-compose up -d</pre>';
    echo '</div>';
    exit(1);
}

// オートローダーの読み込み
require_once $autoloadPath;

// 環境変数の設定
$env = getenv('APP_ENV') ?: 'development';

// リクエストパスの取得
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// URLルーティング
switch ($path) {
    case '/':
        require __DIR__ . '/../src/templates/home.php';
        break;
    case '/patterns':
        require __DIR__ . '/../src/templates/patterns_list.php';
        break;
    case '/calendar':
        // 日付ディレクトリの一覧を表示
        require __DIR__ . '/../src/templates/calendar_view.php';
        break;
    default:
        // 日付ディレクトリをチェック 例: /20250418/singleton
        if (preg_match('|^/(\d{4}-\d{2}-\d{2})/([a-zA-Z_-]+)$|', $path, $matches)) {
            $dateDir = $matches[1];
            $patternName = $matches[2];
            
            // 日付ディレクトリのパターンが存在するか確認
            $datePatternDir = __DIR__ . "/../$dateDir/code/$patternName";
            
            if (file_exists($datePatternDir)) {
                // 日付ディレクトリのデモページを表示
                $isDateDirPattern = true;
                $patternDir = $datePatternDir;
                require __DIR__ . "/../src/templates/date_pattern_demo.php";
            } else {
                // 404エラー
                http_response_code(404);
                require __DIR__ . '/../src/templates/404.php';
            }
        }
        // パターン名を抽出 例: /singleton → singleton
        else if (preg_match('|^/([a-zA-Z_-]+)$|', $path, $matches)) {
            $patternName = $matches[1];
            
            // パターンのディレクトリが存在するか確認
            $patternDir = __DIR__ . "/../src/Patterns/$patternName";
            
            if (file_exists($patternDir)) {
                // パターンのデモページを表示
                $isDateDirPattern = false;
                require __DIR__ . "/../src/templates/pattern_demo.php";
            } else {
                // 404エラー
                http_response_code(404);
                require __DIR__ . '/../src/templates/404.php';
            }
        } else {
            // 404エラー
            http_response_code(404);
            require __DIR__ . '/../src/templates/404.php';
        }
        break;
}
