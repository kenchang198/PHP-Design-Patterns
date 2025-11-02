<?php

namespace App\Patterns\AbstractFactory\Task;

// 全UIコンポーネントの基本インターフェース
interface UIComponent
{
    public function render(): string;
}

// ボタンコンポーネントのインターフェース
interface Button extends UIComponent
{
    public function click(): string;
}

// フォームコンポーネントのインターフェース
interface Form extends UIComponent
{
    public function submit(array $data): string;
}

// アラートコンポーネントのインターフェース
interface Alert extends UIComponent
{
    public function show(string $message): string;
    public function close(): string;
}

// UIファクトリのインターフェース
interface UIFactory
{
    public function createButton(string $text): Button;
    public function createForm(string $name, array $fields): Form;
    public function createAlert(string $type): Alert;
}
