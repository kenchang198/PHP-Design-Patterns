j<?php

require_once 'QueryBuilder.php';

// ビルダーパターンの使用例

echo "=== ビルダーパターン：SQLクエリビルダー ===\n\n";

// 1. 基本的な使用例
echo "1. 基本的なSELECTクエリ:\n";
$query = (new QueryBuilder())
    ->from('users')
    ->select(['id', 'name', 'email'])
    ->where('active', '=', 1)
    ->where('age', '>', 20)
    ->orderBy('created_at', 'DESC')
    ->limit(10)
    ->build();

echo $query . "\n\n";

// 2. メソッドチェーンの柔軟性を示す例
echo "2. 条件付きでクエリを構築（メソッドチェーンの柔軟性）:\n";
$queryBuilder = new QueryBuilder();
$queryBuilder->from('products');

// 条件によって異なるクエリを構築
$searchKeyword = 'laptop';
if (!empty($searchKeyword)) {
    $queryBuilder->where('name', 'LIKE', "%{$searchKeyword}%");
}

$minPrice = 1000;
if ($minPrice > 0) {
    $queryBuilder->where('price', '>=', $minPrice);
}

// ソートオプション
$sortBy = 'price';
$sortDirection = 'ASC';
$queryBuilder->orderBy($sortBy, $sortDirection);

echo $queryBuilder->build() . "\n\n";

// 3. クエリビルダーの再利用
echo "3. クエリビルダーの再利用:\n";
// リセットして別のクエリを構築
$queryBuilder->reset()
    ->from('orders')
    ->select(['order_id', 'total', 'created_at'])
    ->where('status', '=', 'completed')
    ->orderBy('created_at', 'DESC');

echo $queryBuilder->build() . "\n\n";

// 4. すべてのカラムを取得（SELECT *）
echo "4. すべてのカラムを取得:\n";
$allColumnsQuery = (new QueryBuilder())
    ->from('categories')
    ->build();

echo $allColumnsQuery . "\n\n";

// 5. 複雑な条件での使用例
echo "5. 複雑な条件の例:\n";
$complexQuery = (new QueryBuilder())
    ->from('articles')
    ->select(['title', 'author', 'published_at'])
    ->where('published', '=', 1)
    ->where('category_id', '=', 3)
    ->where('published_at', '>', '2024-01-01')
    ->orderBy('view_count', 'DESC')
    ->orderBy('published_at', 'DESC')
    ->limit(5)
    ->build();

echo $complexQuery . "\n\n";
