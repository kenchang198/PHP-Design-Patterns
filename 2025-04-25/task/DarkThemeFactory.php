<?php

namespace App\Patterns\AbstractFactory\Task;

require_once 'UIFactory.php';

// ダークテーマのボタン実装
class DarkButton implements Button
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function render(): string
    {
        return '<button class="dark-theme-button">' . $this->text . '</button>';
    }

    public function click(): string
    {
        return 'ダークテーマボタン "' . $this->text . '" がクリックされました。';
    }
}

// ダークテーマのフォーム実装
class DarkForm implements Form
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
        $html = '<form class="dark-theme-form" name="' . $this->name . '">';
        
        foreach ($this->fields as $field => $type) {
            $html .= '<div class="form-group dark">';
            $html .= '<label for="' . $field . '">' . ucfirst($field) . ':</label>';
            $html .= '<input type="' . $type . '" id="' . $field . '" name="' . $field . '" class="dark-input">';
            $html .= '</div>';
        }
        
        $html .= '<button type="submit" class="dark-theme-button">送信</button>';
        $html .= '</form>';
        
        return $html;
    }

    public function submit(array $data): string
    {
        return 'ダークテーマフォーム "' . $this->name . '" が次のデータで送信されました: ' . json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

// ダークテーマのアラート実装
class DarkAlert implements Alert
{
    private string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function render(): string
    {
        return '<div class="dark-theme-alert dark-alert-' . $this->type . '"><span class="close-btn">&times;</span><p class="alert-content"></p></div>';
    }

    public function show(string $message): string
    {
        return 'ダークテーマの ' . $this->type . ' アラートに「' . $message . '」というメッセージが表示されました。';
    }

    public function close(): string
    {
        return 'ダークテーマの ' . $this->type . ' アラートが閉じられました。';
    }
}

// ダークテーマのUIファクトリ実装
class DarkThemeFactory implements UIFactory
{
    public function createButton(string $text): Button
    {
        return new DarkButton($text);
    }

    public function createForm(string $name, array $fields): Form
    {
        return new DarkForm($name, $fields);
    }

    public function createAlert(string $type): Alert
    {
        return new DarkAlert($type);
    }
}
