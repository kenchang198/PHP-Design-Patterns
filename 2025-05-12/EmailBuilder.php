<?php

/**
 * ビルダーパターンによるメール送信クラス
 */
class EmailBuilder
{
    private array $recipients = [];
    private string $subject = '';
    private string $fromEmail = '';
    private string $fromName = '';
    private string $template = '';
    private array $templateData = [];
    
    /**
     * 送信先メールアドレスを設定
     * 
     * @param string $email メールアドレス
     * @param string|null $name 送信先の名前
     * @return self
     */
    public function to(string $email, ?string $name = null): self
    {
        $this->recipients[] = $name ? [$email => $name] : $email;
        return $this;
    }
    
    /**
     * メールの件名を設定
     * 
     * @param string $subject 件名
     * @return self
     */
    public function subject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }
    
    /**
     * 送信者メールアドレスを設定
     * 
     * @param string $email 送信者メールアドレス
     * @return self
     */
    public function from(string $email): self
    {
        $this->fromEmail = $email;
        return $this;
    }
    
    /**
     * 送信者名を設定
     * 
     * @param string $name 送信者名
     * @return self
     */
    public function fromName(string $name): self
    {
        $this->fromName = $name;
        return $this;
    }
    
    /**
     * メールテンプレートを設定
     * 
     * @param string $template テンプレート名
     * @return self
     */
    public function template(string $template): self
    {
        $this->template = $template;
        return $this;
    }
    
    /**
     * テンプレートに渡すデータを設定
     * 
     * @param array $data テンプレートデータ
     * @return self
     */
    public function withData(array $data): self
    {
        $this->templateData = $data;
        return $this;
    }
    
    /**
     * メールを送信
     * 
     * @return bool 送信結果
     */
    public function send(): bool
    {
        // バリデーション
        if (empty($this->recipients)) {
            throw new InvalidArgumentException('送信先が指定されていません');
        }
        
        if (empty($this->subject)) {
            throw new InvalidArgumentException('件名が指定されていません');
        }
        
        // 実際のメール送信処理（ここではシミュレーションのみ）
        echo "=== ビルダーパターンによるメール送信 ===\n";
        echo "送信先: " . $this->formatRecipients($this->recipients) . "\n";
        echo "件名: " . $this->subject . "\n";
        
        if (!empty($this->fromEmail)) {
            $fromText = $this->fromEmail;
            if (!empty($this->fromName)) {
                $fromText = $this->fromName . " <{$this->fromEmail}>";
            }
            echo "送信者: " . $fromText . "\n";
        }
        
        if (!empty($this->template)) {
            echo "テンプレート: " . $this->template . "\n";
        }
        
        echo "メール送信完了\n\n";
        
        return true;
    }
    
    /**
     * 送信先を人間が読みやすい形式にフォーマット
     * 
     * @param array $recipients 送信先リスト
     * @return string フォーマットされた送信先
     */
    private function formatRecipients(array $recipients): string
    {
        $formatted = [];
        foreach ($recipients as $recipient) {
            if (is_array($recipient)) {
                $email = array_keys($recipient)[0];
                $name = array_values($recipient)[0];
                $formatted[] = "{$name} <{$email}>";
            } else {
                $formatted[] = $recipient;
            }
        }
        return implode(', ', $formatted);
    }
}
