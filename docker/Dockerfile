FROM php:8.2-apache

# システムパッケージとPHP拡張をインストール
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Apacheの設定
RUN a2enmod rewrite

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 作業ディレクトリの設定
WORKDIR /var/www/html

# Apacheのドキュメントルートを設定
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Apache設定の更新
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# カスタムApache設定をコピー
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# コンテナ起動時にComposer依存関係をインストールするスクリプトを追加
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# エントリーポイントを設定
ENTRYPOINT ["docker-entrypoint.sh"]

# ポート80を公開
EXPOSE 80

# コマンドの設定
CMD ["apache2-foreground"]
