<?php

namespace App\Services;

use App\Models\OrgUnit;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

/**
 * OrgStructureService - إدارة الهيكل التنظيمي
 * 
 * هذه الخدمة مسؤولة عن:
 * - التحقق من صحة الربط الهرمي بين الوحدات
 * - إنشاء وتحديث وحذف الوحدات التنظيمية
 * - توليد رموز الوحدات تلقائياً
 * - منع الحذف الخاطئ للوحدات
 * 
 * القاعدة الذهبية:
 * الهيكل التنظيمي يُبنى بالـ parent_id،
 * الوظائف تُربط بالوحدات عبر org_unit_positions،
 * والموظفون يُربطون بالوظائف عبر employee_assignments.
 */
class OrgStructureService
{
    /**
     * قواعد الربط الهرمي - أي نوع يمكن أن يكون تحت أي نوع
     */
    private const ALLOWED_PARENTS = [
        'Minister' => [null],
        'Undersecretary' => ['Minister'],
        'Directorate' => ['Minister', 'Undersecretary'],
        'Department' => ['Directorate'],
        'Section' => ['Department'],
        'Expert' => ['Department', 'Section'],
    ];

    /**
     * البادئات لكل نوع وحدة - تستخدم في توليد الرمز
     */
    private const PREFIXES = [
        'Minister' => 'MIN',
        'Undersecretary' => 'UND',
        'Directorate' => 'DIR',
        'Department' => 'DEP',
        'Section' => 'SEC',
        'Expert' => 'EXP',
    ];

    /**
     * الأسماء العربية لأنواع الوحدات
     */
    public const TYPE_LABELS = [
        'Minister' => 'وزير',
        'Undersecretary' => 'وكيل وزارة',
        'Directorate' => 'مديرية عامة',
        'Department' => 'دائرة',
        'Section' => 'قسم',
        'Expert' => 'خبير',
    ];

    /**
     * التحقق من صحة الربط الهرمي للوحدة الأم
     *
     * @param string $type نوع الوحدة المراد إنشاؤها/تعديلها
     * @param OrgUnit|null $parent الوحدة الأم
     * @throws ValidationException إذا كان الربط غير صحيح
     */
    public function validateParent(string $type, ?OrgUnit $parent): void
    {
        $allowedParentTypes = self::ALLOWED_PARENTS[$type] ?? [];

        if (!in_array($parent?->type, $allowedParentTypes, true)) {
            $parentTypeLabel = $parent ? (self::TYPE_LABELS[$parent->type] ?? $parent->type) : 'لا شيء';
            $typeLabel = self::TYPE_LABELS[$type] ?? $type;
            
            throw ValidationException::withMessages([
                'parent_id' => "لا يمكن ربط {$typeLabel} تحت {$parentTypeLabel}. الربط الهيكلي غير صحيح."
            ]);
        }
    }

    /**
     * التحقق من إمكانية حذف الوحدة
     *
     * @param OrgUnit $unit الوحدة المراد حذفها
     * @throws \Exception إذا لم يمكن حذف الوحدة
     */
    public function validateDelete(OrgUnit $unit): void
    {
        // التحقق من وجود وحدات فرعية
        if ($unit->children()->exists()) {
            throw new \Exception('لا يمكن حذف وحدة لها وحدات فرعية. يرجى حذف الوحدات الفرعية أولاً.');
        }

        // التحقق من وجود موظفين نشطين
        $hasActiveEmployees = $unit->employeeAssignments()
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->exists();

        if ($hasActiveEmployees) {
            throw new \Exception('لا يمكن حذف وحدة لها موظفون نشطون. يرجى نقل الموظفين أولاً.');
        }
    }

    /**
     * توليد رمز فريد للوحدة بناءً على النوع
     *
     * @param string $type نوع الوحدة
     * @return string الرمز الفريد
     */
    public function generateUnitCode(string $type): string
    {
        $prefix = self::PREFIXES[$type] ?? 'ORG';

        // الحصول على آخر رمز مستخدم لهذا النوع
        $lastCode = OrgUnit::where('type', $type)
            ->where('unit_code', 'like', $prefix . '%')
            ->orderBy('unit_code', 'desc')
            ->value('unit_code');

        // حساب الرقم التالي
        $nextNumber = $lastCode
            ? ((int) substr($lastCode, strlen($prefix))) + 1
            : 1;

        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * إنشاء وحدة تنظيمية جديدة
     *
     * @param array $data بيانات الوحدة
     * @return OrgUnit الوحدة المنشأة
     * @throws ValidationException إذا فشل التحقق
     */
    public function createUnit(array $data): OrgUnit
    {
        // جلب الوحدة الأم إن وجدت
        $parent = isset($data['parent_id']) ? OrgUnit::find($data['parent_id']) : null;

        // التحقق من صحة الربط الهرمي
        $this->validateParent($data['type'], $parent);

        // توليد الرمز
        $unitCode = $this->generateUnitCode($data['type']);

        // إنشاء الوحدة
        return OrgUnit::create([
            'unit_code' => $unitCode,
            'name' => $data['name'],
            'type' => $data['type'],
            'parent_id' => $data['parent_id'] ?? null,
            'hierarchy_order' => $data['hierarchy_order'] ?? 0,
        ]);
    }

    /**
     * تحديث وحدة تنظيمية
     *
     * @param OrgUnit $unit الوحدة المراد تحديثها
     * @param array $data البيانات الجديدة
     * @return OrgUnit الوحدة المحدثة
     * @throws ValidationException إذا فشل التحقق
     */
    public function updateUnit(OrgUnit $unit, array $data): OrgUnit
    {
        // جلب الوحدة الأم الجديدة إن تغيرت
        $newParentId = $data['parent_id'] ?? $unit->parent_id;
        $parent = $newParentId ? OrgUnit::find($newParentId) : null;

        // التحقق من عدم جعل الوحدة أماً لنفسها
        if ($newParentId && $newParentId == $unit->id) {
            throw ValidationException::withMessages([
                'parent_id' => 'لا يمكن جعل الوحدة تابعة لنفسها.'
            ]);
        }

        // التحقق من صحة الربط الهرمي
        $type = $data['type'] ?? $unit->type;
        $this->validateParent($type, $parent);

        // تحديث البيانات
        $unit->update([
            'name' => $data['name'] ?? $unit->name,
            'type' => $type,
            'parent_id' => $newParentId,
            'hierarchy_order' => $data['hierarchy_order'] ?? $unit->hierarchy_order,
        ]);

        return $unit->fresh();
    }

    /**
     * حذف وحدة تنظيمية بعد التحقق
     *
     * @param OrgUnit $unit الوحدة المراد حذفها
     * @return bool نجاح العملية
     * @throws \Exception إذا فشل التحقق
     */
    public function deleteUnit(OrgUnit $unit): bool
    {
        $this->validateDelete($unit);

        // فك ربط الوظائف قبل الحذف
        $unit->positions()->detach();

        return $unit->delete();
    }

    /**
     * جلب الوحدات حسب النوع مع العلاقات
     *
     * @param string $type نوع الوحدة
     * @return Collection
     */
    public function getUnitsByType(string $type): Collection
    {
        return OrgUnit::where('type', $type)
            ->with([
                'parent',
                'children' => function ($query) {
                    $query->orderBy('hierarchy_order')->orderBy('name');
                },
                'positions.currentEmployees.user',
                'employeeAssignments' => function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', now());
                },
                'employeeAssignments.user',
                'employeeAssignments.position',
            ])
            ->orderBy('hierarchy_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * جلب شجرة الهيكل التنظيمي كاملة
     *
     * @return Collection
     */
    public function getHierarchyTree(): Collection
    {
        return OrgUnit::whereNull('parent_id')
            ->with([
                'descendants' => function ($query) {
                    $query->orderBy('hierarchy_order')->orderBy('name');
                },
                'positions.currentEmployees.user',
            ])
            ->orderBy('hierarchy_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * جلب الوحدات الأم المسموح بها لنوع معين
     *
     * @param string $type نوع الوحدة
     * @return Collection
     */
    public function getAllowedParentsForType(string $type): Collection
    {
        $allowedTypes = self::ALLOWED_PARENTS[$type] ?? [];
        
        // إزالة null من القائمة
        $allowedTypes = array_filter($allowedTypes, fn($t) => $t !== null);

        if (empty($allowedTypes)) {
            return collect();
        }

        return OrgUnit::whereIn('type', $allowedTypes)
            ->orderBy('hierarchy_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * الحصول على إحصائيات الهيكل التنظيمي
     *
     * @return array
     */
    public function getStats(): array
    {
        return [
            'total_units' => OrgUnit::count(),
            'ministers' => OrgUnit::where('type', 'Minister')->count(),
            'undersecretaries' => OrgUnit::where('type', 'Undersecretary')->count(),
            'directorates' => OrgUnit::where('type', 'Directorate')->count(),
            'departments' => OrgUnit::where('type', 'Department')->count(),
            'sections' => OrgUnit::where('type', 'Section')->count(),
            'experts' => OrgUnit::where('type', 'Expert')->count(),
        ];
    }

    /**
     * الحصول على قواعد الربط الهرمي
     *
     * @return array
     */
    public static function getAllowedParentRules(): array
    {
        return self::ALLOWED_PARENTS;
    }

    /**
     * الحصول على البادئات
     *
     * @return array
     */
    public static function getPrefixes(): array
    {
        return self::PREFIXES;
    }

    /**
     * الحصول على الأسماء العربية
     *
     * @return array
     */
    public static function getTypeLabels(): array
    {
        return self::TYPE_LABELS;
    }
}
