# PHP デザインパターン学習

このリポジトリは PHP を使った GoF デザインパターンの学習と実装のためのプロジェクトです。

## 目的

- デザインパターンの概念と実装方法を理解する
- 実際のコードでパターンの使用方法を学ぶ
- PHP での実践的なコーディングスキルを向上させる

## 環境構築

このプロジェクトは Docker を使用して開発環境を構築しています。

### 必要条件

- Docker
- Docker Compose

### セットアップ手順

1. リポジトリをクローン

```bash
git clone https://github.com/yourusername/PHP-Design-Patterns.git
cd PHP-Design-Patterns
```

2. Docker コンテナをビルドして起動

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
| ---- | -------- | ------ | ---------- |
| 2025-04-18 | Singleton | [コード](./2025-04-18/) | [ブログ記事](https://github.com/kenchang198/PHP-Design-Patterns_blog/blob/main/2025-04-18/day1_singleton_pattern.md) |
| 2025-04-23 | Factory Method | [コード](./2025-04-23/) | [ブログ記事](https://github.com/kenchang198/PHP-Design-Patterns_blog/blob/main/2025-04-23/day2_factory_method_pattern.md) |
| 2025-04-23 | Factory Method (Advanced) | [コード](./2025-04-23/advanced/) | [ブログ記事](https://github.com/kenchang198/PHP-Design-Patterns_blog/blob/main/2025-04-23/advanced/factory_method_advanced.md) |
| 2025-04-25 | Abstract Factory | [コード](./2025-04-25/) | [ブログ記事](https://github.com/kenchang198/PHP-Design-Patterns_blog/blob/main/2025-04-25/abstract_factory_pattern.md) |

## 実装済みパターン

### コピペ用チェック記号

```
[ ]  未完了
[x]  完了
```

### 生成パターン (Creational Patterns)

- [x] Singleton
- [x] Factory Method
- [x] Abstract Factory
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
