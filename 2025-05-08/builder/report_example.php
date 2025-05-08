<?php

require_once 'ReportBuilder.php';

echo "=== Builderパターンを使用したレポート生成デモ ===\n\n";

echo "1. PDFビルダーを使用したシンプルレポートの作成\n";
$pdfBuilder = new PDFReportBuilder();
$director = new ReportDirector($pdfBuilder);

$simpleReport = $director->buildSimpleReport(
    "四半期売上レポート",
    "2025年第1四半期の売上は前年同期比15%増加しました。"
);

echo "タイトル: " . $simpleReport->getTitle() . "\n";
echo "ヘッダー: " . $simpleReport->getHeader() . "\n";
echo "コンテンツ: \n";
foreach ($simpleReport->getContent() as $content) {
    echo "  - " . $content . "\n";
}
echo "フッター: " . $simpleReport->getFooter() . "\n";
echo "フォーマット: " . $simpleReport->getMetadata()['format'] . "\n";
echo "\n";

echo "2. HTMLビルダーを使用した詳細レポートの作成\n";
$htmlBuilder = new HTMLReportBuilder();
$director->changeBuilder($htmlBuilder);

$detailedReport = $director->buildDetailedReport(
    "市場分析レポート",
    "当社製品の市場シェアは5%増加しました。",
    "山田太郎",
    "マーケティング部"
);

echo "タイトル: " . $detailedReport->getTitle() . "\n";
echo "ヘッダー: " . $detailedReport->getHeader() . "\n";
echo "コンテンツ: \n";
foreach ($detailedReport->getContent() as $content) {
    echo "  - " . $content . "\n";
}
echo "フッター: " . $detailedReport->getFooter() . "\n";
echo "フォーマット: " . $detailedReport->getMetadata()['format'] . "\n";
echo "作成者: " . $detailedReport->getMetadata()['author'] . "\n";
echo "部署: " . $detailedReport->getMetadata()['department'] . "\n";
echo "\n";

echo "3. 異なるビルダーを使用した月次レポートの比較\n";

$director->changeBuilder($pdfBuilder);
$pdfMonthlyReport = $director->buildMonthlyReport(
    "月次売上レポート",
    "4月",
    "2025",
    "売上目標達成率: 95%"
);

echo "PDF月次レポート:\n";
echo "タイトル: " . $pdfMonthlyReport->getTitle() . "\n";
echo "ヘッダー: " . $pdfMonthlyReport->getHeader() . "\n";
echo "コンテンツ: \n";
foreach ($pdfMonthlyReport->getContent() as $content) {
    echo "  - " . $content . "\n";
}
echo "フッター: " . $pdfMonthlyReport->getFooter() . "\n";
echo "フォーマット: " . $pdfMonthlyReport->getMetadata()['format'] . "\n";
echo "\n";

$director->changeBuilder($htmlBuilder);
$htmlMonthlyReport = $director->buildMonthlyReport(
    "月次売上レポート",
    "4月",
    "2025",
    "売上目標達成率: 95%"
);

echo "HTML月次レポート:\n";
echo "タイトル: " . $htmlMonthlyReport->getTitle() . "\n";
echo "ヘッダー: " . $htmlMonthlyReport->getHeader() . "\n";
echo "コンテンツ: \n";
foreach ($htmlMonthlyReport->getContent() as $content) {
    echo "  - " . $content . "\n";
}
echo "フッター: " . $htmlMonthlyReport->getFooter() . "\n";
echo "フォーマット: " . $htmlMonthlyReport->getMetadata()['format'] . "\n";
echo "\n";

echo "4. ディレクターなしでビルダーを直接使用する例\n";
$customReport = $pdfBuilder
    ->reset()
    ->setTitle("カスタムレポート")
    ->buildHeader("特別分析")
    ->buildContent("このレポートはカスタム要件に基づいて作成されました。")
    ->buildContent("追加の分析データがここに表示されます。")
    ->setStyle("orientation", "landscape")
    ->addMetadata("confidentiality", "社外秘")
    ->buildFooter("社外秘 - 無断複製禁止")
    ->getReport();

echo "カスタムPDFレポート:\n";
echo "タイトル: " . $customReport->getTitle() . "\n";
echo "ヘッダー: " . $customReport->getHeader() . "\n";
echo "コンテンツ: \n";
foreach ($customReport->getContent() as $content) {
    echo "  - " . $content . "\n";
}
echo "フッター: " . $customReport->getFooter() . "\n";
echo "フォーマット: " . $customReport->getMetadata()['format'] . "\n";
echo "機密性: " . $customReport->getMetadata()['confidentiality'] . "\n";
echo "スタイル: orientation = " . $customReport->getStyles()['orientation'] . "\n";
echo "\n";

echo "=== Builderパターンのデモ終了 ===\n";
