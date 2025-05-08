<?php

require_once 'Shape.php';

echo "=== Prototypeパターンを使用したシェイプライブラリデモ ===\n\n";

echo "1. 基本シェイプをプロトタイプとして登録\n";

$circlePrototype = new Circle(10);
$circlePrototype->setColor('red')->setPosition(5, 5);
ShapeRegistry::addShape('circle', $circlePrototype);

$rectanglePrototype = new Rectangle(30, 20);
$rectanglePrototype->setColor('blue')->setPosition(10, 10);
ShapeRegistry::addShape('rectangle', $rectanglePrototype);

$trianglePrototype = new Triangle(25, 25);
$trianglePrototype->setColor('green')->setPosition(15, 15);
ShapeRegistry::addShape('triangle', $trianglePrototype);

$compositePrototype = new CompositeShape();
$compositePrototype->addShape(new Circle(5))
                  ->addShape(new Rectangle(10, 5))
                  ->addShape(new Triangle(8, 8));
$compositePrototype->setPosition(20, 20);
ShapeRegistry::addShape('composite', $compositePrototype);

echo "登録されたプロトタイプ: " . implode(', ', ShapeRegistry::getAvailableShapes()) . "\n\n";

echo "2. 基本シェイプのクローンと変更\n";

$circle1 = ShapeRegistry::getShape('circle');
echo "オリジナル円: ";
$circle1->draw();

$circle2 = $circle1->clone();
$circle2->setRadius(15)->setColor('yellow')->setPosition(100, 100);
echo "変更後の円: ";
$circle2->draw();

$rectangle1 = ShapeRegistry::getShape('rectangle');
echo "オリジナル長方形: ";
$rectangle1->draw();

$rectangle2 = $rectangle1->clone();
$rectangle2->setWidth(50)->setHeight(25)->setColor('purple')->setPosition(200, 200);
echo "変更後の長方形: ";
$rectangle2->draw();

echo "\n";

echo "3. 複合シェイプのクローン\n";

$composite1 = ShapeRegistry::getShape('composite');
echo "オリジナル複合シェイプ:\n";
$composite1->draw();

$composite2 = $composite1->clone();
$composite2->setPosition(300, 300);
$shapes = $composite2->getShapes();
if (isset($shapes[0]) && $shapes[0] instanceof Circle) {
    $shapes[0]->setColor('orange');
}

echo "\n変更後の複合シェイプ:\n";
$composite2->draw();

echo "\n";

echo "4. 実行時にシェイプを動的に生成する例\n";

function createRandomShape() {
    $shapes = ['circle', 'rectangle', 'triangle', 'composite'];
    $randomType = $shapes[array_rand($shapes)];
    
    $shape = ShapeRegistry::getShape($randomType);
    
    $colors = ['red', 'blue', 'green', 'yellow', 'purple', 'orange'];
    $randomColor = $colors[array_rand($colors)];
    
    $x = rand(0, 500);
    $y = rand(0, 500);
    
    $shape->setColor($randomColor)->setPosition($x, $y);
    
    return $shape;
}

echo "ランダムに生成された5つのシェイプ:\n";
for ($i = 0; $i < 5; $i++) {
    $randomShape = createRandomShape();
    echo ($i + 1) . ". ";
    $randomShape->draw();
}

echo "\n";

echo "5. 既存のプロトタイプをベースにしたカスタムシェイプの作成\n";

$redCircleCollection = new CompositeShape();
for ($i = 0; $i < 3; $i++) {
    $circle = ShapeRegistry::getShape('circle');
    $circle->setRadius(rand(20, 50))
           ->setColor('red')
           ->setPosition(rand(0, 100), rand(0, 100));
    $redCircleCollection->addShape($circle);
}

$blueRectCollection = new CompositeShape();
for ($i = 0; $i < 2; $i++) {
    $rect = ShapeRegistry::getShape('rectangle');
    $rect->setWidth(rand(30, 60))
         ->setHeight(rand(20, 40))
         ->setColor('blue')
         ->setPosition(rand(100, 200), rand(100, 200));
    $blueRectCollection->addShape($rect);
}

$finalComposite = new CompositeShape();
$finalComposite->addShape($redCircleCollection)
               ->addShape($blueRectCollection);

echo "最終的な複合シェイプ:\n";
$finalComposite->draw();

ShapeRegistry::addShape('complex-scene', $finalComposite);

$clonedScene = ShapeRegistry::getShape('complex-scene');
$clonedScene->setPosition(500, 500);

echo "\nクローンされた複雑なシーン:\n";
$clonedScene->draw();

echo "\n=== Prototypeパターンのデモ終了 ===\n";
