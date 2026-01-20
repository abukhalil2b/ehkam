<?php

namespace App\Enums;

class StepStatus
{
    public const DRAFT = 'draft';
    public const IN_PROGRESS = 'in_progress';
    public const COMPLETED = 'completed';
    public const RETURNED = 'returned';
    public const REJECTED = 'rejected';
    public const DELAYED = 'delayed';

    // 1. Add this array for the dropdown in the Edit View
    public static array $editable = [
        self::IN_PROGRESS,
        self::COMPLETED,
        self::DELAYED,
    ];

    public static array $meta = [
        self::DRAFT => [
            'label' => 'مسودة',
            'bg' => 'bg-gray-100',
            'text' => 'text-gray-800',
            'icon' => 'fas fa-exclamation-circle',
        ],
        self::IN_PROGRESS => [
            'label' => 'في الإجراء',
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
        self::DELAYED => [
            'label' => 'متأخر',
            'bg' => 'bg-red-100',
            'text' => 'text-red-800',
            'icon' => 'fas fa-exclamation-circle',
        ],
        self::RETURNED => [
            'label' => 'تم إعادته',
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-800',
            'icon' => 'fas fa-exclamation-circle',
        ],
        self::REJECTED => [
            'label' => 'مرفوض',
            'bg' => 'bg-red-100',
            'text' => 'text-red-800',
            'icon' => 'fas fa-exclamation-circle',
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