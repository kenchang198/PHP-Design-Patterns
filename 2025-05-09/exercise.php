<?php

/**
 * ビルダーパターン 練習問題
 * 
 * 以下の課題に取り組んで、ビルダーパターンの理解を深めましょう
 */

require_once 'QueryBuilder.php';

echo "=== ビルダーパターン 練習問題 ===\n\n";

// 課題1: 基本的なクエリ構築
echo "課題1: 以下の条件でSELECTクエリを構築してください\n";
echo "- テーブル: employees\n";
echo "- カラム: id, name, department\n";
echo "- 条件: salary > 50000\n";
echo "- 並び順: name の昇順\n\n";

// TODO: ここにコードを書いてください
// $result1 = 

// echo "結果: " . $result1 . "\n\n";

// 課題2: 複数条件のクエリ
echo "課題2: 以下の条件でクエリを構築してください\n";
echo "- テーブル: books\n";
echo "- カラム: title, author, price\n";
echo "- 条件: price >= 1000 AND category = 'technology'\n";
echo "- 並び順: price の降順\n";
echo "- 制限: 5件まで\n\n";

// TODO: ここにコードを書いてください
// $result2 = 

// echo "結果: " . $result2 . "\n\n";

// 課題3: 動的なクエリ構築
echo "課題3: 条件によって動的にクエリを構築してください\n";
echo "関数 buildDynamicQuery(\$filters) を実装してください\n";
echo "\$filters は以下のような配列です:\n";
echo "[\n";
echo "  'table' => 'users',\n";
echo "  'columns' => ['id', 'name'],\n";
echo "  'conditions' => [\n";
echo "    ['column' => 'age', 'operator' => '>', 'value' => 18],\n";
echo "    ['column' => 'status', 'operator' => '=', 'value' => 'active']\n";
echo "  ],\n";
echo "  'order' => ['column' => 'created_at', 'direction' => 'DESC'],\n";
echo "  'limit' => 10\n";
echo "]\n\n";

function buildDynamicQuery(array $filters): string
{
    // TODO: ここに実装してください
    // QueryBuilderクラスを使って動的にクエリを構築
    
    return '';
}

// テスト用のフィルター配列
$testFilters = [
    'table' => 'users',
    'columns' => ['id', 'name', 'email'],
    'conditions' => [
        ['column' => 'age', 'operator' => '>', 'value' => 18],
        ['column' => 'status', 'operator' => '=', 'value' => 'active']
    ],
    'order' => ['column' => 'created_at', 'direction' => 'DESC'],
    'limit' => 10
];

// $result3 = buildDynamicQuery($testFilters);
// echo "結果: " . $result3 . "\n\n";

// 課題4: 高度な機能の追加
echo "課題4: QueryBuilderクラスに以下の機能を追加してください\n";
echo "1. join() メソッド: テーブル結合を行う\n";
echo "2. groupBy() メソッド: GROUP BY句を追加\n";
echo "3. having() メソッド: HAVING句を追加\n\n";

/*
 * 実装例の参考:
 * 
 * public function join(string $table, string $condition): self
 * {
 *     // JOIN を追加する実装
 *     return $this;
 * }
 * 
 * public function groupBy(array $columns): self
 * {
 *     // GROUP BY を追加する実装
 *     return $this;
 * }
 * 
 * public function having(string $condition): self
 * {
 *     // HAVING を追加する実装
 *     return $this;
 * }
 */

echo "=== 解答例は次回のカリキュラムで提供されます ===\n";
echo "まずは自分で実装してみましょう！\n";
