<?php

class Report {
    private $title;
    private $header;
    private $content = [];
    private $footer;
    private $styles = [];
    private $metadata = [];
    
    public function setTitle($title) {
        $this->title = $title;
    }
    
    public function setHeader($header) {
        $this->header = $header;
    }
    
    public function addContent($content) {
        $this->content[] = $content;
    }
    
    public function setFooter($footer) {
        $this->footer = $footer;
    }
    
    public function addStyle($name, $value) {
        $this->styles[$name] = $value;
    }
    
    public function addMetadata($key, $value) {
        $this->metadata[$key] = $value;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function getHeader() {
        return $this->header;
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function getFooter() {
        return $this->footer;
    }
    
    public function getStyles() {
        return $this->styles;
    }
    
    public function getMetadata() {
        return $this->metadata;
    }
}

interface ReportBuilder {
    public function reset();
    public function setTitle($title);
    public function buildHeader($header);
    public function buildContent($content);
    public function buildFooter($footer);
    public function setStyle($name, $value);
    public function addMetadata($key, $value);
    public function getReport();
}

class PDFReportBuilder implements ReportBuilder {
    private $report;
    
    public function __construct() {
        $this->reset();
    }
    
    public function reset() {
        $this->report = new Report();
        $this->report->addMetadata('format', 'PDF');
        $this->report->addStyle('font-family', 'Times New Roman');
    }
    
    public function setTitle($title) {
        $this->report->setTitle($title);
        return $this;
    }
    
    public function buildHeader($header) {
        $formattedHeader = "PDF Header: " . $header;
        $this->report->setHeader($formattedHeader);
        return $this;
    }
    
    public function buildContent($content) {
        $formattedContent = "PDF Content Section: " . $content;
        $this->report->addContent($formattedContent);
        return $this;
    }
    
    public function buildFooter($footer) {
        $formattedFooter = "PDF Footer: " . $footer;
        $this->report->setFooter($formattedFooter);
        return $this;
    }
    
    public function setStyle($name, $value) {
        $this->report->addStyle($name, $value);
        return $this;
    }
    
    public function addMetadata($key, $value) {
        $this->report->addMetadata($key, $value);
        return $this;
    }
    
    public function getReport() {
        $result = $this->report;
        $this->reset();
        return $result;
    }
}

class HTMLReportBuilder implements ReportBuilder {
    private $report;
    
    public function __construct() {
        $this->reset();
    }
    
    public function reset() {
        $this->report = new Report();
        $this->report->addMetadata('format', 'HTML');
        $this->report->addStyle('font-family', 'Arial, sans-serif');
    }
    
    public function setTitle($title) {
        $this->report->setTitle($title);
        return $this;
    }
    
    public function buildHeader($header) {
        $formattedHeader = "<header><h1>" . htmlspecialchars($header) . "</h1></header>";
        $this->report->setHeader($formattedHeader);
        return $this;
    }
    
    public function buildContent($content) {
        $formattedContent = "<section><p>" . htmlspecialchars($content) . "</p></section>";
        $this->report->addContent($formattedContent);
        return $this;
    }
    
    public function buildFooter($footer) {
        $formattedFooter = "<footer><p>" . htmlspecialchars($footer) . "</p></footer>";
        $this->report->setFooter($formattedFooter);
        return $this;
    }
    
    public function setStyle($name, $value) {
        $this->report->addStyle($name, $value);
        return $this;
    }
    
    public function addMetadata($key, $value) {
        $this->report->addMetadata($key, $value);
        return $this;
    }
    
    public function getReport() {
        $result = $this->report;
        $this->reset();
        return $result;
    }
}

class ReportDirector {
    private $builder;
    
    public function __construct(ReportBuilder $builder) {
        $this->builder = $builder;
    }
    
    public function changeBuilder(ReportBuilder $builder) {
        $this->builder = $builder;
    }
    
    public function buildSimpleReport($title, $content) {
        $this->builder->reset();
        $this->builder->setTitle($title);
        $this->builder->buildHeader($title);
        $this->builder->buildContent($content);
        $this->builder->buildFooter("Generated on " . date('Y-m-d'));
        
        return $this->builder->getReport();
    }
    
    public function buildDetailedReport($title, $content, $author, $department) {
        $this->builder->reset();
        $this->builder->setTitle($title);
        $this->builder->buildHeader($title);
        
        $this->builder->buildContent("Executive Summary: " . $content);
        $this->builder->buildContent("Detailed Analysis: Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $this->builder->buildContent("Conclusions: Based on our analysis, we recommend...");
        
        $this->builder->buildFooter("Generated by $author from $department on " . date('Y-m-d'));
        
        $this->builder->addMetadata('author', $author);
        $this->builder->addMetadata('department', $department);
        $this->builder->addMetadata('created', date('Y-m-d H:i:s'));
        
        $this->builder->setStyle('color-scheme', 'professional');
        $this->builder->setStyle('page-size', 'A4');
        
        return $this->builder->getReport();
    }
    
    public function buildMonthlyReport($title, $month, $year, $data) {
        $this->builder->reset();
        $this->builder->setTitle("$title - $month $year");
        $this->builder->buildHeader("Monthly Report: $month $year");
        
        $this->builder->buildContent("Summary for $month $year: $data");
        $this->builder->buildContent("Trends: Compared to previous months...");
        $this->builder->buildContent("Forecast: Based on current trends...");
        
        $this->builder->buildFooter("Monthly report generated on " . date('Y-m-d'));
        
        $this->builder->addMetadata('report-type', 'monthly');
        $this->builder->addMetadata('period', "$month $year");
        
        $this->builder->setStyle('charts', 'enabled');
        $this->builder->setStyle('color-scheme', 'financial');
        
        return $this->builder->getReport();
    }
}
