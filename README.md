# PHP デザインパターン学習

このリポジトリはPHPを使ったGoFデザインパターンの学習と実装のためのプロジェクトです。

## 目的

- デザインパターンの概念と実装方法を理解する
- 実際のコードでパターンの使用方法を学ぶ
- PHPでの実践的なコーディングスキルを向上させる

## 環境構築

このプロジェクトはDockerを使用して開発環境を構築しています。

### 必要条件

- Docker
- Docker Compose

### セットアップ手順

1. リポジトリをクローン
```bash
git clone https://github.com/yourusername/PHP-Design-Patterns.git
cd PHP-Design-Patterns
```

2. Dockerコンテナをビルドして起動
```bash
docker-compose up -d
```

3. ブラウザからアクセス
```
http://localhost:8080
```

## プロジェクト構造

```
PHP-Design-Patterns/
├── docker/                 # Docker関連ファイル
├── public/                 # 公開ディレクトリ
│   └── index.php           # フロントコントローラー
├── src/                    # ソースコード
│   ├── Patterns/           # デザインパターン実装
│   └── templates/          # 表示用テンプレート
├── docker-compose.yml      # Docker Compose設定
└── README.md               # このファイル
```

## 学習進捗

| 日付 | パターン | コード | ブログ記事 |
|------|----------|-------|------------|
|      |          |       |            |

## 実装済みパターン

### 生成パターン (Creational Patterns)
- [ ] Singleton
- [ ] Factory Method
- [ ] Abstract Factory
- [ ] Builder
- [ ] Prototype

### 構造パターン (Structural Patterns)
- [ ] Adapter
- [ ] Bridge
- [ ] Composite
- [ ] Decorator
- [ ] Facade
- [ ] Flyweight
- [ ] Proxy

### 振る舞いパターン (Behavioral Patterns)
- [ ] Chain of Responsibility
- [ ] Command
- [ ] Interpreter
- [ ] Iterator
- [ ] Mediator
- [ ] Memento
- [ ] Observer
- [ ] State
- [ ] Strategy
- [ ] Template Method
- [ ] Visitor
# PHP-Design-Patterns
