<?php

require_once 'UIFactory.php';

function createUserInterface(UIFactory $factory) {
    echo "ユーザーインターフェースを作成します...\n";
    
    $loginButton = $factory->createButton("ログイン");
    $usernameField = $factory->createTextField("ユーザー名を入力");
    $passwordField = $factory->createTextField("パスワードを入力");
    $rememberMeCheckbox = $factory->createCheckbox("ログイン状態を保持する", false);
    
    echo "\nログインフォームをレンダリングします：\n";
    $usernameField->render();
    $passwordField->render();
    $rememberMeCheckbox->render();
    $loginButton->render();
    
    $loginButton->onClick(function() {
        echo "ログインボタンがクリックされました\n";
    });
    
    echo "\n";
}

echo "=== ライトテーマ ===\n";
$lightFactory = new LightThemeFactory();
createUserInterface($lightFactory);

echo "\n=== ダークテーマ ===\n";
$darkFactory = new DarkThemeFactory();
createUserInterface($darkFactory);

function getThemeFactory($themeName) {
    if ($themeName === 'dark') {
        return new DarkThemeFactory();
    } else {
        return new LightThemeFactory();
    }
}

$userPreference = 'dark'; // これは実際にはデータベースやセッションから取得する
echo "\n=== ユーザー設定に基づくテーマ ({$userPreference}) ===\n";
$userThemeFactory = getThemeFactory($userPreference);
createUserInterface($userThemeFactory);

/*
class BlueThemeFactory implements UIFactory {
    public function createButton($label): Button {
        return new BlueButton($label);
    }
    
    public function createTextField($placeholder): TextField {
        return new BlueTextField($placeholder);
    }
    
    public function createCheckbox($label, $checked = false): Checkbox {
        return new BlueCheckbox($label, $checked);
    }
}

$blueFactory = new BlueThemeFactory();
createUserInterface($blueFactory);
*/
