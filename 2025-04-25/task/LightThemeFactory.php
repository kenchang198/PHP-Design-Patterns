<?php

namespace App\Patterns\AbstractFactory\Task;

require_once 'UIFactory.php';

// ライトテーマのボタン実装
class LightButton implements Button
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function render(): string
    {
        return '<button class="light-theme-button">' . $this->text . '</button>';
    }

    public function click(): string
    {
        return 'ライトテーマボタン "' . $this->text . '" がクリックされました。';
    }
}

// ライトテーマのフォーム実装
class LightForm implements Form
{
    private string $name;
    private array $fields;

    public function __construct(string $name, array $fields)
    {
        $this->name = $name;
        $this->fields = $fields;
    }

    public function render(): string
    {
        $html = '<form class="light-theme-form" name="' . $this->name . '">';
        
        foreach ($this->fields as $field => $type) {
            $html .= '<div class="form-group">';
            $html .= '<label for="' . $field . '">' . ucfirst($field) . ':</label>';
            $html .= '<input type="' . $type . '" id="' . $field . '" name="' . $field . '" class="light-input">';
            $html .= '</div>';
        }
        
        $html .= '<button type="submit" class="light-theme-button">送信</button>';
        $html .= '</form>';
        
        return $html;
    }

    public function submit(array $data): string
    {
        return 'ライトテーマフォーム "' . $this->name . '" が次のデータで送信されました: ' . json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

// ライトテーマのアラート実装
class LightAlert implements Alert
{
    private string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function render(): string
    {
        return '<div class="light-theme-alert light-alert-' . $this->type . '"><span class="close-btn">&times;</span><p class="alert-content"></p></div>';
    }

    public function show(string $message): string
    {
        return 'ライトテーマの ' . $this->type . ' アラートに「' . $message . '」というメッセージが表示されました。';
    }

    public function close(): string
    {
        return 'ライトテーマの ' . $this->type . ' アラートが閉じられました。';
    }
}

// ライトテーマのUIファクトリ実装
class LightThemeFactory implements UIFactory
{
    public function createButton(string $text): Button
    {
        return new LightButton($text);
    }

    public function createForm(string $name, array $fields): Form
    {
        return new LightForm($name, $fields);
    }

    public function createAlert(string $type): Alert
    {
        return new LightAlert($type);
    }
}
