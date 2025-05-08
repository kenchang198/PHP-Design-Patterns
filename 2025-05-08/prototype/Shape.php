<?php

abstract class Shape {
    protected $id;
    protected $type;
    protected $color;
    protected $x;
    protected $y;
    
    public function __construct() {
        $this->id = uniqid();
    }
    
    public function clone(): Shape {
        return clone $this;
    }
    
    public function __clone() {
        $this->id = uniqid(); // 新しいIDを生成
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setColor($color) {
        $this->color = $color;
        return $this;
    }
    
    public function getColor() {
        return $this->color;
    }
    
    public function setPosition($x, $y) {
        $this->x = $x;
        $this->y = $y;
        return $this;
    }
    
    public function getX() {
        return $this->x;
    }
    
    public function getY() {
        return $this->y;
    }
    
    abstract public function draw();
}

class Circle extends Shape {
    private $radius;
    
    public function __construct($radius = 10) {
        parent::__construct();
        $this->type = 'Circle';
        $this->radius = $radius;
        $this->color = 'red';
        $this->x = 0;
        $this->y = 0;
    }
    
    public function setRadius($radius) {
        $this->radius = $radius;
        return $this;
    }
    
    public function getRadius() {
        return $this->radius;
    }
    
    public function draw() {
        echo "Drawing a {$this->color} circle at ({$this->x}, {$this->y}) with radius {$this->radius}\n";
    }
}

class Rectangle extends Shape {
    private $width;
    private $height;
    
    public function __construct($width = 20, $height = 10) {
        parent::__construct();
        $this->type = 'Rectangle';
        $this->width = $width;
        $this->height = $height;
        $this->color = 'blue';
        $this->x = 0;
        $this->y = 0;
    }
    
    public function setWidth($width) {
        $this->width = $width;
        return $this;
    }
    
    public function getWidth() {
        return $this->width;
    }
    
    public function setHeight($height) {
        $this->height = $height;
        return $this;
    }
    
    public function getHeight() {
        return $this->height;
    }
    
    public function draw() {
        echo "Drawing a {$this->color} rectangle at ({$this->x}, {$this->y}) with width {$this->width} and height {$this->height}\n";
    }
}

class Triangle extends Shape {
    private $base;
    private $height;
    
    public function __construct($base = 15, $height = 15) {
        parent::__construct();
        $this->type = 'Triangle';
        $this->base = $base;
        $this->height = $height;
        $this->color = 'green';
        $this->x = 0;
        $this->y = 0;
    }
    
    public function setBase($base) {
        $this->base = $base;
        return $this;
    }
    
    public function getBase() {
        return $this->base;
    }
    
    public function setHeight($height) {
        $this->height = $height;
        return $this;
    }
    
    public function getHeight() {
        return $this->height;
    }
    
    public function draw() {
        echo "Drawing a {$this->color} triangle at ({$this->x}, {$this->y}) with base {$this->base} and height {$this->height}\n";
    }
}

class CompositeShape extends Shape {
    private $shapes = [];
    
    public function __construct() {
        parent::__construct();
        $this->type = 'Composite';
        $this->color = 'mixed';
        $this->x = 0;
        $this->y = 0;
    }
    
    public function addShape(Shape $shape) {
        $this->shapes[] = $shape;
        return $this;
    }
    
    public function getShapes() {
        return $this->shapes;
    }
    
    public function __clone() {
        parent::__clone();
        
        $clonedShapes = [];
        foreach ($this->shapes as $shape) {
            $clonedShapes[] = clone $shape;
        }
        $this->shapes = $clonedShapes;
    }
    
    public function draw() {
        echo "Drawing a composite shape at ({$this->x}, {$this->y}) with " . count($this->shapes) . " components:\n";
        foreach ($this->shapes as $shape) {
            echo "  - ";
            $shape->draw();
        }
    }
}

class ShapeRegistry {
    private static $shapes = [];
    
    public static function addShape($key, Shape $shape) {
        self::$shapes[$key] = $shape;
    }
    
    public static function getShape($key) {
        if (isset(self::$shapes[$key])) {
            return self::$shapes[$key]->clone();
        }
        return null;
    }
    
    public static function getAvailableShapes() {
        return array_keys(self::$shapes);
    }
}
