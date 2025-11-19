# Strategy パターン(ストラテジパターン)

## 概要

Strategy パターンは、**アルゴリズムのファミリーを定義し、それぞれをカプセル化して交換可能にする**デザインパターンです。このパターンを使用すると、アルゴリズムをそれを使用するクライアントから独立して変更できます。

### 目的

- アルゴリズムのバリエーションをカプセル化する
- 実行時にアルゴリズムを切り替え可能にする
- 条件分岐(if-else, switch)を削減し、コードの保守性を向上させる
- 開放閉鎖原則(OCP)を実現する

## Factory Method パターンとの違い

Strategy パターンと Factory Method パターンは混同されやすいですが、目的と構造が異なります。

### 比較表

| 観点                   | Strategy パターン      | Factory Method パターン    |
| ---------------------- | ---------------------- | -------------------------- |
| **主目的**             | アルゴリズムの切り替え | オブジェクトの生成         |
| **焦点**               | 振る舞い(Behavior)     | 構造(Creation)             |
| **実装方法**           | 委譲(Delegation)       | 継承(Inheritance)          |
| **切り替えタイミング** | 実行時に動的に変更可能 | 生成時に決定(基本的に固定) |
| **クラス構成**         | Context + Strategy     | Creator + Product          |
| **主な責務**           | どう処理するか         | 何を生成するか             |

### 構造の違い

**Factory Method パターン:**

```
PaymentFactory (抽象クラス)
    ├── createProcessor(): PaymentProcessor  // ファクトリーメソッド
    └── processPayment()                     // テンプレートメソッド
         ↑
         ├── CreditCardFactory
         ├── PayPalFactory
         └── BankTransferFactory

問題: 決済方法ごとにFactoryクラスが必要
      実行時に決済方法を切り替えるには新しいFactoryインスタンスが必要
```

**Strategy パターン:**

```
PaymentContext (コンテキスト)
    └── strategy: PaymentStrategy  // 戦略を保持
         ↑
         ├── CreditCardStrategy
         ├── PayPalStrategy
         └── BankTransferStrategy

利点: 1つのContextで実行時に戦略を自由に切り替え可能
      新しい戦略の追加が既存コードに影響しない
```

### 使い分けのガイドライン

**Factory Method を使うべき場合:**

- オブジェクトの**生成プロセス**が重要な場合
- 生成するオブジェクトの型が継承階層の中で決定される場合
- サブクラスがどのクラスをインスタンス化するかを決定する場合

**Strategy を使うべき場合:**

- アルゴリズムやロジックの**実行方法**が重要な場合
- 実行時にアルゴリズムを動的に切り替える必要がある場合
- 条件分岐(if-else/switch)が多く、保守が困難な場合
- 複数のアルゴリズムが同じインターフェースを共有する場合

## 解決する問題: 決済手段の増加による条件分岐の複雑化

### Before: 条件分岐による実装

```php
class PaymentProcessor
{
    public function processPayment(string $type, float $amount): void
    {
        if ($type === 'credit_card') {
            echo "クレジットカードで{$amount}円を決済中...\n";
            // クレジットカード固有の処理
            echo "カード番号を検証中...\n";
            echo "決済完了!\n";
        } elseif ($type === 'paypal') {
            echo "PayPalで{$amount}円を決済中...\n";
            // PayPal固有の処理
            echo "PayPalアカウントに接続中...\n";
            echo "決済完了!\n";
        } elseif ($type === 'bank_transfer') {
            echo "銀行振込で{$amount}円を決済中...\n";
            // 銀行振込固有の処理
            echo "振込先口座を確認中...\n";
            echo "決済完了!\n";
        } elseif ($type === 'bitcoin') {
            echo "Bitcoinで{$amount}円を決済中...\n";
            // Bitcoin固有の処理
            echo "ウォレットアドレスを検証中...\n";
            echo "決済完了!\n";
        } else {
            throw new Exception("未対応の決済方法: {$type}");
        }
    }
}
```

**問題点:**

1. ❌ 新しい決済方法を追加するたびに既存コードを修正(開放閉鎖原則違反)
2. ❌ 条件分岐が増えると可読性が低下
3. ❌ 各決済方法のロジックがテストしづらい
4. ❌ 単一責任の原則違反(1 つのクラスが全決済方法を知っている)

### After: Strategy パターンによる実装

```php
// 戦略インターフェース
interface PaymentStrategy
{
    public function processPayment(float $amount): void;
}

// 具体的な戦略
class CreditCardStrategy implements PaymentStrategy
{
    public function processPayment(float $amount): void
    {
        echo "クレジットカードで{$amount}円を決済中...\n";
        echo "決済完了!\n";
    }
}

// コンテキスト
class PaymentContext
{
    private PaymentStrategy $strategy;

    public function setStrategy(PaymentStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function executePayment(float $amount): void
    {
        $this->strategy->processPayment($amount);
    }
}

// 使用例
$context = new PaymentContext();
$context->setStrategy(new CreditCardStrategy());
$context->executePayment(1000);

$context->setStrategy(new PayPalStrategy());  // 実行時に切り替え
$context->executePayment(2000);
```

**改善点:**

1. ✅ 新しい決済方法は新しいクラスを追加するだけ(開放閉鎖原則)
2. ✅ 各戦略が独立してテスト可能
3. ✅ 実行時に戦略を動的に切り替え可能
4. ✅ 単一責任の原則を遵守
5. ✅ 条件分岐が完全に排除される

## UML クラス図

```
┌─────────────────────┐
│  PaymentContext     │
├─────────────────────┤
│ - strategy          │◇──────> PaymentStrategy
├─────────────────────┤                ↑
│ + setStrategy()     │                │ implements
│ + executePayment()  │                │
└─────────────────────┘                │
                                       │
                 ┌─────────────────────┼─────────────────────┬──────────────────┐
                 │                     │                     │                  │
    ┌────────────────────┐  ┌─────────────────┐  ┌──────────────────┐  ┌─────────────────┐
    │ CreditCardStrategy │  │  PayPalStrategy │  │BankTransferStrategy│  │ BitcoinStrategy │
    ├────────────────────┤  ├─────────────────┤  ├──────────────────┤  ├─────────────────┤
    │+ processPayment()  │  │+ processPayment()│  │+ processPayment()│  │+ processPayment()│
    └────────────────────┘  └─────────────────┘  └──────────────────┘  └─────────────────┘
```

## 開放閉鎖原則(OCP)の実現

Strategy パターンは、開放閉鎖原則を実現する典型的な例です。

**開放閉鎖原則:** ソフトウェアのエンティティは、拡張に対して開いていて、修正に対して閉じているべきである。

### 新しい決済方法の追加

```php
// 新しい戦略クラスを追加するだけ(既存コードは一切変更不要)
class ApplePayStrategy implements PaymentStrategy
{
    public function processPayment(float $amount): void
    {
        echo "Apple Payで{$amount}円を決済中...\n";
        echo "Touch IDで認証中...\n";
        echo "決済完了!\n";
    }
}

// 使用
$context->setStrategy(new ApplePayStrategy());
$context->executePayment(5000);
```

- ✅ 既存の `PaymentContext` を変更する必要なし
- ✅ 既存の戦略クラスを変更する必要なし
- ✅ 新しいクラスを追加するだけで機能拡張

## Context クラスの役割

`PaymentContext` は以下の責務を持ちます:

1. **戦略の保持**: 現在使用する戦略オブジェクトへの参照を保持
2. **戦略の切り替え**: `setStrategy()` で実行時に戦略を変更可能
3. **処理の委譲**: 実際の処理は保持している戦略に委譲
4. **インターフェースの提供**: クライアントに対して統一されたインターフェースを提供

Context は戦略の実装詳細を知る必要がなく、インターフェースを通じて通信します(依存性逆転の原則)。

## 実装のポイント

### 1. 戦略の切り替え方法

**コンストラクタ注入:**

```php
class PaymentContext
{
    public function __construct(private PaymentStrategy $strategy) {}
}
```

**セッターメソッド:**

```php
class PaymentContext
{
    public function setStrategy(PaymentStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }
}
```

**メソッド引数:**

```php
class PaymentContext
{
    public function executePayment(PaymentStrategy $strategy, float $amount): void
    {
        $strategy->processPayment($amount);
    }
}
```

### 2. 戦略の選択

実際のアプリケーションでは、戦略の選択ロジックが必要です:

```php
// Factory パターンと組み合わせて使用することも可能
class PaymentStrategyFactory
{
    public static function create(string $type): PaymentStrategy
    {
        return match($type) {
            'credit_card' => new CreditCardStrategy(),
            'paypal' => new PayPalStrategy(),
            'bank_transfer' => new BankTransferStrategy(),
            'bitcoin' => new BitcoinStrategy(),
            default => throw new InvalidArgumentException("未対応の決済方法: {$type}")
        };
    }
}

// 使用例
$strategy = PaymentStrategyFactory::create('paypal');
$context->setStrategy($strategy);
```

#### Factory パターンとの組み合わせについて

**Factory パターンは必須か？**

Factory パターンは、Strategy パターン自体の動作には**必須ではありません**が、**実用的なアプリケーションではほぼ必須**と言えます。

**理由:**

1. **条件分岐の集約**: ユーザー入力や設定に基づいて適切な戦略を選択する際、Factory に条件分岐を集約することで、クライアントコードがシンプルになります。

2. **エラーハンドリングの一元化**: 未対応の決済方法のチェックや、戦略インスタンス生成時のエラーハンドリングを Factory で一元管理できます。

3. **拡張性の向上**: 新しい戦略を追加する際、Factory クラスに 1 つのケースを追加するだけで済みます。

4. **テスト容易性**: Factory をモックすることで、テスト時に特定の戦略を注入しやすくなります。

**Factory なしでも動作する例:**

```php
// 小規模な例や学習用途では直接インスタンス化も可能
$context = new PaymentContext(new CreditCardStrategy());
$context->executePayment(1000);
```

**Factory ありの実用的な例:**

```php
// ユーザー入力に基づく動的な選択
$userChoice = $_POST['payment_method']; // 'paypal', 'credit_card' など
$strategy = PaymentStrategyFactory::create($userChoice);
$context->setStrategy($strategy);
$context->executePayment($amount);
```

**結論:**

Strategy パターンは Factory なしでも動作しますが、**実アプリケーションでは Factory パターン的な実装と組み合わせることで、Strategy パターンの効果が最大化されます**。特に、ユーザー選択や設定に基づいて動的に戦略を選択する必要がある場合、Factory パターンの組み合わせは強く推奨されます。

## 使用例の実行

```bash
php example.php
```

### 期待される出力

```
=== Before: 条件分岐による実装 ===
クレジットカードで1000円を決済中...
カード番号を検証中...
決済完了!

=== After: Strategy パターンによる実装 ===
クレジットカードで1000円を決済中...
決済完了!

PayPalで2000円を決済中...
決済完了!

銀行振込で3000円を決済中...
決済完了!

Bitcoinで5000円を決済中...
決済完了!

=== 新しい戦略の追加(Apple Pay)===
Apple Payで5000円を決済中...
Touch IDで認証中...
決済完了!
```

## まとめ

Strategy パターンは以下の場合に有効です:

1. ✅ 同じ問題を解決する複数のアルゴリズムが存在する
2. ✅ 実行時にアルゴリズムを切り替える必要がある
3. ✅ 条件分岐が多く、保守が困難
4. ✅ アルゴリズムのバリエーションが今後も増える可能性がある

### デメリット

- クラスの数が増える(各戦略ごとにクラスが必要)
- クライアントが戦略の違いを理解する必要がある
- 戦略が少数で変更が稀な場合はオーバーエンジニアリングになる可能性

### 関連パターン

- **State パターン**: 状態に応じて振る舞いを変える(Strategy の特殊ケース)
- **Template Method パターン**: アルゴリズムの骨格を定義し、一部のステップをサブクラスで実装
- **Factory Method パターン**: オブジェクトの生成を委譲(Strategy と組み合わせて使用可能)

## 参考リソース

- Gang of Four『デザインパターン』
- 『Head First デザインパターン』
- [Refactoring.Guru - Strategy Pattern](https://refactoring.guru/design-patterns/strategy)
