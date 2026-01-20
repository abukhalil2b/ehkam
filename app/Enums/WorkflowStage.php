<?php

namespace App\Enums;

class WorkflowStage
{
    // Internal stage keys
    public const PLANNING = 'planning';
    public const IMPLEMENTATION = 'implementation';
    public const REVIEW = 'review';
    public const CLOSE = 'close';

    // Map stage keys to display titles and weights (from $phases)
    public static array $meta = [
        self::PLANNING => ['title' => 'التخطيط والتطوير', 'weight' => '25%'],
        self::IMPLEMENTATION => ['title' => 'التنفيذ', 'weight' => '40%'],
        self::REVIEW => ['title' => 'المراجعة', 'weight' => '20%'],
        self::CLOSE => ['title' => 'الاعتماد والإغلاق', 'weight' => '15%'],
    ];

    public static function label(string $stage): string
    {
        return self::$meta[$stage]['title'] ?? $stage;
    }

    public static function weight(string $stage): string
    {
        return self::$meta[$stage]['weight'] ?? '';
    }

    public static function all(): array
    {
        return self::$meta;
    }

    // Allowed actions per stage
    public static array $actions = [
        self::PLANNING => ['submit'],
        self::IMPLEMENTATION => ['submit'],
        self::REVIEW => ['approve', 'reject'],
        self::CLOSE => ['approve', 'reject'],
    ];

    public static function actions(string $stage): array
    {
        return self::$actions[$stage] ?? [];
    }
}
