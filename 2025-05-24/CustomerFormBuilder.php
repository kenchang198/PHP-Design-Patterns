<?php

require_once 'FormBuilder.php';

/**
 * 顧客登録フォーム
 */
class CustomerFormBuilder extends FormBuilder
{
    public function __construct()
    {
        parent::__construct("顧客登録フォーム");
        
        // デフォルトのフィールドを設定
        $this->addField('name', 'text', '顧客名', true);
        $this->addField('email', 'email', 'メールアドレス', true);
        $this->addField('phone', 'tel', '電話番号');
        $this->addField('company', 'text', '会社名');
        
        $this->setSubmitButton('顧客を登録');
    }
    
    /**
     * 顧客フォームを複製
     */
    public function clone(): FormBuilder
    {
        $cloned = new CustomerFormBuilder();
        $cloned->formName = $this->formName;
        $cloned->fields = $this->fields; // 配列をコピー
        $cloned->submitButtonText = $this->submitButtonText;
        
        return $cloned;
    }
    
    /**
     * 営業部向けの追加フィールド
     */
    public function addSalesFields(): void
    {
        $this->addField('budget', 'number', '予算');
        $this->addField('next_contact', 'date', '次回連絡予定日');
    }
    
    /**
     * サポート部向けの追加フィールド
     */
    public function addSupportFields(): void
    {
        $this->addField('issue_type', 'text', '問い合わせ種別');
        $this->addField('priority', 'text', '優先度');
    }
}
