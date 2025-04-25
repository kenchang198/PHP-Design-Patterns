<?php

require_once 'PaymentFactory.php';

/**
 * ファクトリーレジストリクラス
 * 様々な決済方法ファクトリーを登録して管理する
 */
class PaymentFactoryRegistry {
    private static $factories = [];
    
    /**
     * 決済ファクトリーを登録する
     *
     * @param string $type 決済タイプ
     * @param PaymentFactory $factory ファクトリーオブジェクト
     */
    public static function register($type, PaymentFactory $factory) {
        self::$factories[$type] = $factory;
    }
    
    /**
     * 決済タイプに対応するファクトリーを取得する
     *
     * @param string $type 決済タイプ
     * @return PaymentFactory
     * @throws Exception 未登録の決済タイプの場合
     */
    public static function getFactory($type): PaymentFactory {
        if (!isset(self::$factories[$type])) {
            throw new Exception("サポートされていない決済方法: {$type}");
        }
        return self::$factories[$type];
    }
    
    /**
     * 登録されているすべての決済タイプを取得する
     *
     * @return array 登録済み決済タイプの配列
     */
    public static function getAvailableTypes(): array {
        return array_keys(self::$factories);
    }
    
    /**
     * レジストリをクリアする（テスト用）
     */
    public static function clear() {
        self::$factories = [];
    }
}
