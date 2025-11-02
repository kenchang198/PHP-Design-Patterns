# Abstract Factory パターン学習課題

## 課題1: UIコンポーネントファクトリの実装

異なるUIテーマ（例：「Light」と「Dark」）に対応するボタン、フォーム、アラートなどのUIコンポーネントを生成するAbstract Factoryパターンを実装してください。

### 要件

1. 以下のインターフェースを定義してください：
   - `UIComponent`: 全UIコンポーネントの基本インターフェース
   - `Button`: ボタンコンポーネントのインターフェース
   - `Form`: フォームコンポーネントのインターフェース
   - `Alert`: アラートコンポーネントのインターフェース
   - `UIFactory`: UIコンポーネントを生成するファクトリのインターフェース

2. 以下の具体的なファクトリを実装してください：
   - `LightThemeFactory`: ライトテーマのUIコンポーネントを生成するファクトリ
   - `DarkThemeFactory`: ダークテーマのUIコンポーネントを生成するファクトリ

3. 各テーマに対応する具体的なコンポーネントクラスを実装してください：
   - ライトテーマ: `LightButton`, `LightForm`, `LightAlert`
   - ダークテーマ: `DarkButton`, `DarkForm`, `DarkAlert`

4. クライアントコードでテーマを切り替えられることを示す例を作成してください。

### ファイル構成

以下のファイル構成で実装してください：

```
task/
├── UIFactory.php        # インターフェース定義
├── LightThemeFactory.php # ライトテーマ実装
├── DarkThemeFactory.php  # ダークテーマ実装
└── index.php             # クライアント実装例
```

## 課題2: ブログ記事の作成

今日学んだAbstract Factoryパターンについて、以下の内容を含むブログ記事を作成してください：

1. Abstract Factoryパターンとは何か？
2. Factory Methodパターンとの違い
3. PHPでの実装例と解説
4. 実際のプロジェクトでの活用シーン
5. メリットとデメリット

ブログ記事は`/Users/ken/dev/PHP-Design-Patterns_blog/2025-04-25/abstract_factory_pattern.md`に作成してください。
