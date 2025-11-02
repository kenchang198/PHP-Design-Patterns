<?php

/**
 * Prototypeパターン：フォーム生成システム
 * 
 * 目的：設定を複製して、異なる部署向けのHTMLフォームを効率的に生成する
 */

/**
 * フォーム設定の基底クラス
 */
abstract class FormBuilder
{
    protected string $formName;
    protected array $fields = [];
    protected string $submitButtonText = "送信";
    
    public function __construct(string $formName)
    {
        $this->formName = $formName;
    }
    
    /**
     * Prototypeパターンの核：オブジェクトを複製する
     */
    abstract public function clone(): FormBuilder;
    
    /**
     * フィールドを追加
     */
    public function addField(string $name, string $type, string $label, bool $required = false): void
    {
        $this->fields[] = [
            'name' => $name,
            'type' => $type,
            'label' => $label,
            'required' => $required
        ];
    }
    
    /**
     * 送信ボタンのテキストを設定
     */
    public function setSubmitButton(string $text): void
    {
        $this->submitButtonText = $text;
    }
    
    /**
     * フォーム名を変更
     */
    public function setFormName(string $name): void
    {
        $this->formName = $name;
    }
    
    /**
     * HTMLフォームを生成して出力
     */
    public function generateHTML(): string
    {
        $html = "<form name='{$this->formName}' method='POST'>\n";
        $html .= "  <h3>【{$this->formName}】</h3>\n";
        
        foreach ($this->fields as $field) {
            $required = $field['required'] ? ' <span style="color:red;">*</span>' : '';
            $requiredAttr = $field['required'] ? ' required' : '';
            
            $html .= "  <div style='margin-bottom: 10px;'>\n";
            $html .= "    <label>{$field['label']}{$required}:</label><br>\n";
            
            if ($field['type'] === 'textarea') {
                $html .= "    <textarea name='{$field['name']}'{$requiredAttr}></textarea>\n";
            } else {
                $html .= "    <input type='{$field['type']}' name='{$field['name']}'{$requiredAttr}>\n";
            }
            
            $html .= "  </div>\n";
        }
        
        $html .= "  <button type='submit'>{$this->submitButtonText}</button>\n";
        $html .= "</form>\n";
        
        return $html;
    }
    
    /**
     * 設定内容を表示
     */
    public function showInfo(): string
    {
        $fieldCount = count($this->fields);
        return "フォーム名: {$this->formName} | フィールド数: {$fieldCount}";
    }
}
