<?php

require_once 'CustomerFormBuilder.php';

echo "=== Prototypeパターンデモ：フォーム生成システム ===\n\n";

echo "【目的】\n";
echo "基本的な顧客登録フォームの設定を複製して、\n";
echo "部署別にカスタマイズしたHTMLフォームを生成する\n\n";

// ① 基本となるプロトタイプを作成
echo "【ステップ1】基本プロトタイプの作成\n";
$baseCustomerForm = new CustomerFormBuilder();
echo "プロトタイプ: " . $baseCustomerForm->showInfo() . "\n\n";

// ② プロトタイプから営業部向けフォームを作成
echo "【ステップ2】営業部向けフォームの作成\n";
$salesForm = $baseCustomerForm->clone(); // ★ここがPrototypeパターンのポイント
$salesForm->setFormName("営業部：顧客登録フォーム");
$salesForm->addSalesFields(); // 営業部固有のフィールドを追加

echo "営業部フォーム: " . $salesForm->showInfo() . "\n";

// ③ プロトタイプからサポート部向けフォームを作成
echo "【ステップ3】サポート部向けフォームの作成\n";
$supportForm = $baseCustomerForm->clone(); // ★同じプロトタイプから別バージョンを作成
$supportForm->setFormName("サポート部：顧客登録フォーム");
$supportForm->addSupportFields(); // サポート部固有のフィールドを追加

echo "サポート部フォーム: " . $supportForm->showInfo() . "\n\n";

// ④ 生成されるHTMLを確認
echo "【ステップ4】生成されるHTMLフォーム\n\n";

echo "■ 営業部向けフォーム\n";
echo $salesForm->generateHTML() . "\n";

echo "■ サポート部向けフォーム\n";
echo $supportForm->generateHTML() . "\n";

// ⑤ プロトタイプパターンの利点を実証
echo "【プロトタイプパターンの利点】\n";
echo "✓ 基本設定を一度作成すれば、部署別にカスタマイズ可能\n";
echo "✓ 同じコードを何度も書く必要がない\n";
echo "✓ 各部署のフォームは独立しているため、変更が他に影響しない\n";
echo "✓ 新しい部署向けフォームも簡単に作成できる\n\n";

echo "=== デモ完了 ===\n";
