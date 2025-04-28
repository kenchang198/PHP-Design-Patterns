<?php

require_once 'PaymentFactory.php';

/**
 * 設定ベースの決済ファクトリープロバイダークラス
 * 設定に基づいて動的にファクトリーを生成する
 */
class PaymentFactoryProvider {
    private static $config = [
        // default
        // 'credit_card' => [
        //     'class' => CreditCardFactory::class,
        //     'params' => ['api_key' => 'production_key_789']
        // ],
        // 'paypal' => [
        //     'class' => PayPalFactory::class,
        //     'params' => ['client_id' => 'client_456', 'secret' => 'secret_789']
        // ],
    ];
    
    /**
     * 設定ファイルを読み込む（例：JSON形式）
     *
     * @param string $configFile 設定ファイルパス
     * @return bool 読み込み成功かどうか
     */
    public static function loadConfig($configFile) {
        if (file_exists($configFile)) {
            $configData = json_decode(file_get_contents($configFile), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                self::$config = $configData;
                return true;
            }
        }
        return false;
    }
    
    /**
     * 決済タイプに対応するファクトリーを取得する
     *
     * @param string $type 決済タイプ
     * @return PaymentFactory
     * @throws Exception 未登録の決済タイプまたは無効な設定の場合
     */
    public static function getFactory($type): PaymentFactory {
        if (!isset(self::$config[$type])) {
            throw new Exception("サポートされていない決済方法: {$type}");
        }
        
        $config = self::$config[$type];
        
        if (!isset($config['class']) || !class_exists($config['class'])) {
            throw new Exception("無効なファクトリークラス設定: {$type}");
        }
        
        $className = $config['class'];
        $params = $config['params'] ?? [];
        
        // Reflection APIを使用して動的にインスタンス化
        $reflection = new ReflectionClass($className);
        
        // 配列をパラメータリストに変換
        $constructorParams = [];
        if ($reflection->getConstructor()) {
            foreach ($reflection->getConstructor()->getParameters() as $param) {
                $paramName = $param->getName();
                if (isset($params[$paramName])) {
                    // 完全一致
                    $constructorParams[] = $params[$paramName];
                } elseif (isset($params[self::toSnakeCase($paramName)])) {
                    // キャメルケース→スネークケース変換で一致
                    $constructorParams[] = $params[self::toSnakeCase($paramName)];
                } elseif (isset($params[self::toCamelCase($paramName)])) {
                    // スネークケース→キャメルケース変換で一致
                    $constructorParams[] = $params[self::toCamelCase($paramName)];
                } elseif ($param->isDefaultValueAvailable()) {
                    // 一致するパラメータがなければデフォルト値を使用
                    $constructorParams[] = $param->getDefaultValue();
                } else {
                    throw new Exception("必須パラメータが不足しています: {$paramName}");
                }
            }
        }
        
        return $reflection->newInstanceArgs($constructorParams);
    }
    
    /**
     * キャメルケースからスネークケースに変換
     *
     * @param string $input キャメルケースの文字列
     * @return string スネークケースの文字列
     */
    private static function toSnakeCase($input) {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
    
    /**
     * スネークケースからキャメルケースに変換
     *
     * @param string $input スネークケースの文字列
     * @return string キャメルケースの文字列
     */
    private static function toCamelCase($input) {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }
    
    /**
     * 新しい決済方法の設定を追加する
     *
     * @param string $type 決済タイプ
     * @param string $factoryClass ファクトリークラス名
     * @param array $params コンストラクタパラメータ
     */
    public static function addFactoryType($type, $factoryClass, array $params = []) {
        self::$config[$type] = [
            'class' => $factoryClass,
            'params' => $params
        ];
    }
    
    /**
     * 登録されているすべての決済タイプを取得する
     *
     * @return array 登録済み決済タイプの配列
     */
    public static function getAvailableTypes(): array {
        return array_keys(self::$config);
    }
}
