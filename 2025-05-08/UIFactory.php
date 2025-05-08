<?php

interface Button {
    public function render();
    public function onClick($callback);
}

interface TextField {
    public function render();
    public function onInput($callback);
}

interface Checkbox {
    public function render();
    public function onToggle($callback);
}

class LightButton implements Button {
    private $label;
    
    public function __construct($label) {
        $this->label = $label;
    }
    
    public function render() {
        echo "ライトテーマのボタンをレンダリング: {$this->label} (白背景、黒テキスト)\n";
    }
    
    public function onClick($callback) {
        echo "ライトテーマのボタンにクリックイベントを設定\n";
    }
}

class LightTextField implements TextField {
    private $placeholder;
    
    public function __construct($placeholder) {
        $this->placeholder = $placeholder;
    }
    
    public function render() {
        echo "ライトテーマのテキストフィールドをレンダリング: プレースホルダー \"{$this->placeholder}\" (白背景、グレーボーダー)\n";
    }
    
    public function onInput($callback) {
        echo "ライトテーマのテキストフィールドに入力イベントを設定\n";
    }
}

class LightCheckbox implements Checkbox {
    private $label;
    private $checked;
    
    public function __construct($label, $checked = false) {
        $this->label = $label;
        $this->checked = $checked;
    }
    
    public function render() {
        $status = $this->checked ? '✓' : '□';
        echo "ライトテーマのチェックボックスをレンダリング: {$this->label} {$status} (白背景、黒枠)\n";
    }
    
    public function onToggle($callback) {
        echo "ライトテーマのチェックボックスに切り替えイベントを設定\n";
    }
}

class DarkButton implements Button {
    private $label;
    
    public function __construct($label) {
        $this->label = $label;
    }
    
    public function render() {
        echo "ダークテーマのボタンをレンダリング: {$this->label} (黒背景、白テキスト)\n";
    }
    
    public function onClick($callback) {
        echo "ダークテーマのボタンにクリックイベントを設定\n";
    }
}

class DarkTextField implements TextField {
    private $placeholder;
    
    public function __construct($placeholder) {
        $this->placeholder = $placeholder;
    }
    
    public function render() {
        echo "ダークテーマのテキストフィールドをレンダリング: プレースホルダー \"{$this->placeholder}\" (暗灰色背景、白テキスト)\n";
    }
    
    public function onInput($callback) {
        echo "ダークテーマのテキストフィールドに入力イベントを設定\n";
    }
}

class DarkCheckbox implements Checkbox {
    private $label;
    private $checked;
    
    public function __construct($label, $checked = false) {
        $this->label = $label;
        $this->checked = $checked;
    }
    
    public function render() {
        $status = $this->checked ? '✓' : '□';
        echo "ダークテーマのチェックボックスをレンダリング: {$this->label} {$status} (暗灰色背景、白枠)\n";
    }
    
    public function onToggle($callback) {
        echo "ダークテーマのチェックボックスに切り替えイベントを設定\n";
    }
}

interface UIFactory {
    public function createButton($label): Button;
    public function createTextField($placeholder): TextField;
    public function createCheckbox($label, $checked = false): Checkbox;
}

class LightThemeFactory implements UIFactory {
    public function createButton($label): Button {
        return new LightButton($label);
    }
    
    public function createTextField($placeholder): TextField {
        return new LightTextField($placeholder);
    }
    
    public function createCheckbox($label, $checked = false): Checkbox {
        return new LightCheckbox($label, $checked);
    }
}

class DarkThemeFactory implements UIFactory {
    public function createButton($label): Button {
        return new DarkButton($label);
    }
    
    public function createTextField($placeholder): TextField {
        return new DarkTextField($placeholder);
    }
    
    public function createCheckbox($label, $checked = false): Checkbox {
        return new DarkCheckbox($label, $checked);
    }
}
