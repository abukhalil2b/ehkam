<?php

namespace App\Enums;

class StepStatus
{
    public const DRAFT = 'draft';
    public const REVIEW = 'review';
    public const APPROVED = 'approved';
    public const IN_PROGRESS = 'in_progress';
    public const COMPLETED = 'completed';
    public const RETURNED = 'returned';

    // 1. Add this array for the dropdown in the Edit View
    public static array $editable = [
        self::IN_PROGRESS,
        self::COMPLETED,
    ];

    public static array $meta = [
        self::DRAFT => [
            'label' => 'مسودة',
            'bg' => 'bg-gray-100',
            'text' => 'text-gray-800',
            'icon' => 'fas fa-exclamation-circle',
        ],
        self::REVIEW => [
            'label' => 'مراجعة',
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-800',
            'icon' => 'fas fa-search',
        ],
        self::APPROVED => [
            'label' => 'معتمد',
            'bg' => 'bg-indigo-100',
            'text' => 'text-indigo-800',
            'icon' => 'fas fa-check-double',
        ],
        self::IN_PROGRESS => [
            'label' => 'قيد التنفيذ',
            'bg' => 'bg-yellow-100',
            'text' => 'text-yellow-800',
            'icon' => 'fas fa-spinner',
        ],
        self::COMPLETED => [
            'label' => 'منجز',
            'bg' => 'bg-green-100',
            'text' => 'text-green-800',
            'icon' => 'fas fa-check-circle',
        ],
        self::RETURNED => [
            'label' => 'معاد',
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-800',
            'icon' => 'fas fa-undo',
        ],
    ];

    public static function badge(string $status): array
    {
        return self::$meta[$status] ?? [
            'label' => $status,
            'bg' => 'bg-gray-100',
            'text' => 'text-gray-800',
            'icon' => 'far fa-clock',
        ];
    }

    // 2. Add this helper method
    public static function label(string $status): string
    {
        return self::$meta[$status]['label'] ?? $status;
    }
}