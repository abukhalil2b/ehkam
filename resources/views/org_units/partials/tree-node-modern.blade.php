@if($level == 0)
    @push('styles')
        <style>
            /* 
                   Professional CSS Org Chart 
                   Based on standard Pseudo-element tree technique
                */
            .tree ul {
                padding-top: 20px;
                position: relative;
                transition: all 0.5s;
                display: flex;
                justify-content: center;
            }

            .tree li {
                float: left;
                text-align: center;
                list-style-type: none;
                position: relative;
                padding: 20px 5px 0 5px;
                /* Spacing between nodes */
                transition: all 0.5s;
            }

            /* Connectors */
            .tree li::before,
            .tree li::after {
                content: '';
                position: absolute;
                top: 0;
                right: 50%;
                border-top: 2px solid #cbd5e1;
                /* Slate-300 */
                width: 50%;
                height: 20px;
            }

            .tree li::after {
                right: auto;
                left: 50%;
                border-left: 2px solid #cbd5e1;
            }

            /* Remove connectors from singular/first/last nodes */
            .tree li:only-child::after,
            .tree li:only-child::before {
                display: none;
            }

            .tree li:only-child {
                padding-top: 0;
            }

            .tree li:first-child::before,
            .tree li:last-child::after {
                border: 0 none;
            }

            /* Adding back the vertical line for the last child's first half */
            .tree li:last-child::before {
                border-right: 2px solid #cbd5e1;
                border-radius: 0 5px 0 0;
            }

            .tree li:first-child::after {
                border-radius: 5px 0 0 0;
            }

            /* Downward connector from parent */
            .tree ul ul::before {
                content: '';
                position: absolute;
                top: 0;
                left: 50%;
                border-left: 2px solid #cbd5e1;
                width: 0;
                height: 20px;
            }

            /* The Card */
            .tree-card {
                display: inline-block;
                background: white;
                border: 1px solid #e2e8f0;
                padding: 16px;
                border-radius: 8px;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
                transition: all 0.2s;
                position: relative;
                z-index: 10;
                min-width: 200px;
                max-width: 280px;
            }

            .tree-card:hover {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                border-color: #94a3b8;
                transform: translateY(-2px);
            }

            /* Node Types Colors (Subtle borders) */
            .type-Minister {
                border-top: 4px solid #10b981;
            }

            /* Emerald */
            .type-Undersecretary {
                border-top: 4px solid #14b8a6;
            }

            /* Teal */
            .type-Directorate {
                border-top: 4px solid #0ea5e9;
            }

            /* Sky */
            .type-Department {
                border-top: 4px solid #f59e0b;
            }

            /* Amber */
            .type-Section {
                border-top: 4px solid #64748b;
            }

            /* Slate */
            .type-Expert {
                border-top: 4px solid #8b5cf6;
            }

            /* Violet */

            /* Scoped scrollbar for positions list */
            .pos-scroll::-webkit-scrollbar {
                width: 3px;
            }

            .pos-scroll::-webkit-scrollbar-track {
                background: #f1f5f9;
            }

            .pos-scroll::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 2px;
            }
        </style>
    @endpush
@endif

{{-- Recursion Start --}}
@php
    $startTag = $level == 0 ? '<div class="tree"><ul>' : '';
    $endTag = $level == 0 ? '</ul></div>' : '';
@endphp

{!! $startTag !!}
<li>
    <div class="tree-card type-{{ $unit->type }}">
        {{-- Header --}}
        <div class="mb-3 border-b border-gray-100 pb-2">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-1">
                {{ $unit->type }}
            </span>
            <h4 class="font-bold text-gray-800 text-base leading-tight">
                {{ $unit->name }}
            </h4>
            <div class="flex justify-center mt-2">
                <span class="text-[10px] font-mono bg-gray-100 text-gray-500 px-2 py-0.5 rounded">
                    {{ $unit->unit_code }}
                </span>
            </div>
        </div>

        {{-- Stats --}}
        <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
            <div class="flex flex-col items-center flex-1 border-l border-gray-100">
                <span class="font-bold text-lg text-gray-700">{{ $unit->positions->count() }}</span>
                <span>وظيفة</span>
            </div>
            <div class="flex flex-col items-center flex-1">
                <span class="font-bold text-lg text-gray-700">
                    {{ $unit->positions->sum(fn($p) => $p->employees->count()) }}
                </span>
                <span>موظف</span>
            </div>
        </div>

        {{-- Toggle Button for Children (Alpine) --}}
        @if($unit->children->count() > 0)
            <button @click="$el.closest('li').querySelector('ul').classList.toggle('hidden')"
                class="w-6 h-6 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center mx-auto text-gray-400 hover:text-gray-600 transition mt-1"
                title="تبديل العرض">
                <span class="material-icons text-sm">unfold_more</span>
            </button>
        @endif
    </div>

    {{-- Children --}}
    @if($unit->children->count() > 0)
        <ul>
            @foreach($unit->children as $child)
                @include('org_units.partials.tree-node-modern', ['unit' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @endif
</li>
{!! $endTag !!}