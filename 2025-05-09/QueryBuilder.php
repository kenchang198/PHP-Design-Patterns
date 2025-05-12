<?php

/**
 * SQLクエリを構築するクラス（Builder Pattern実装）
 * 
 * ビルダーパターンの特徴：
 * - 複雑なオブジェクトの構築を段階的に行う
 * - メソッドチェーン（流れるようなインターフェース）を使用
 * - 構築過程と表現を分離
 */
class QueryBuilder
{
    private string $table = '';
    private array $columns = [];
    private array $conditions = [];
    private array $order = [];
    private ?int $limit = null;
    
    /**
     * SELECTするテーブルを指定
     * 
     * @param string $table テーブル名
     * @return self
     */
    public function from(string $table): self
    {
        $this->table = $table;
        return $this;
    }
    
    /**
     * SELECTするカラムを指定
     * 
     * @param array $columns カラム名の配列
     * @return self
     */
    public function select(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }
    
    /**
     * WHERE条件を追加
     * 
     * @param string $column カラム名
     * @param string $operator 演算子
     * @param mixed $value 値
     * @return self
     */
    public function where(string $column, string $operator, $value): self
    {
        $this->conditions[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];
        return $this;
    }
    
    /**
     * ORDER BY句を追加
     * 
     * @param string $column カラム名
     * @param string $direction 並び順（'ASC' または 'DESC'）
     * @return self
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->order[] = [
            'column' => $column,
            'direction' => $direction
        ];
        return $this;
    }
    
    /**
     * LIMIT句を設定
     * 
     * @param int $limit 取得件数の上限
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }
    
    /**
     * 構築したクエリを文字列として取得
     * 
     * @return string 構築されたSQLクエリ
     */
    public function build(): string
    {
        // SELECT句の構築
        $query = 'SELECT ';
        if (empty($this->columns)) {
            $query .= '*';
        } else {
            $query .= implode(', ', $this->columns);
        }
        
        // FROM句の構築
        if (empty($this->table)) {
            throw new InvalidArgumentException('Table name is required');
        }
        $query .= " FROM {$this->table}";
        
        // WHERE句の構築
        if (!empty($this->conditions)) {
            $whereConditions = [];
            foreach ($this->conditions as $condition) {
                $whereConditions[] = "{$condition['column']} {$condition['operator']} '{$condition['value']}'";
            }
            $query .= ' WHERE ' . implode(' AND ', $whereConditions);
        }
        
        // ORDER BY句の構築
        if (!empty($this->order)) {
            $orderConditions = [];
            foreach ($this->order as $order) {
                $orderConditions[] = "{$order['column']} {$order['direction']}";
            }
            $query .= ' ORDER BY ' . implode(', ', $orderConditions);
        }
        
        // LIMIT句の構築
        if ($this->limit !== null) {
            $query .= " LIMIT {$this->limit}";
        }
        
        return $query;
    }
    
    /**
     * クエリビルダーをリセット（再利用可能にする）
     * 
     * @return self
     */
    public function reset(): self
    {
        $this->table = '';
        $this->columns = [];
        $this->conditions = [];
        $this->order = [];
        $this->limit = null;
        return $this;
    }
}
