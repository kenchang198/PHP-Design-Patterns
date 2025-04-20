#!/bin/sh
set -e

# プロジェクトディレクトリに移動
cd /var/www/html

# vendor ディレクトリが存在しない場合のみ composer install を実行
if [ ! -d "vendor" ]; then
    echo "vendor ディレクトリが見つかりません。Composer依存関係をインストールします..."
    composer install --no-interaction --optimize-autoloader
else
    echo "vendorディレクトリが既に存在します。composer updateは実行しません。"
fi

# コンテナ起動時に vendor/autoload.php ファイルが存在することを確認
if [ ! -f "vendor/autoload.php" ]; then
    echo "vendor/autoload.php が見つかりません。Composer依存関係を更新します..."
    composer update --no-interaction --optimize-autoloader
fi

# 実行時に渡されたコマンドを実行
exec "$@"
