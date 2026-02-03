<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $questionnaire->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f7f9fb 0%, #eef2f7 100%);
            min-height: 100vh;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        @media (max-width: 640px) {
            .attendance-table th,
            .attendance-table td {
                padding: 8px 10px;
                font-size: 0.85rem;
            }
        }

        /* Smooth transitions for dependent dropdowns */
        .dependent-dropdown {
            transition: opacity 0.3s ease, max-height 0.3s ease;
        }

        .dependent-dropdown.hidden {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
        }

        /* Range buttons styling */
        .range-btn {
            opacity: 0.7;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .range-btn:hover {
            opacity: 1;
        }

        .range-btn.selected {
            opacity: 1;
            transform: scale(1.15);
            box-shadow: 0 0 0 3px white, 0 0 0 5px currentColor, 0 6px 16px rgba(0, 0, 0, 0.3);
            z-index: 10;
            position: relative;
        }

        .range-btn:active {
            transform: scale(0.95);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <div class="bg-blue-800 text-white py-3 shadow-lg">
        <div class="max-w-4xl mx-auto px-2 text-center">
            <h1 class="text-2xl font-extrabold">
                المديرية العامة للتخطيط والدراسات
            </h1>
            <h2 class="text-xl font-extrabold"> وزارة الأوقاف والشؤون الدينية</h2>
        </div>
    </div>

    <div class="max-w-6xl mx-auto p-4">
        {{-- QR Code Section --}}
        <div class="hidden md:block">
            @if ($qrImage)
                <div class="p-2 mb-4 text-center">
                    <div class="flex flex-col items-center">
                        <div class="bg-white p-4 rounded-xl shadow-inner border-2 border-blue-200 inline-block">
                            {!! $qrImage !!}
                        </div>
                        <p class="mt-4 text-gray-600 font-medium flex items-center justify-center">
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            مسح الكود للإجابة
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-2xl mx-auto py-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ $questionnaire->title }}</h2>

        @php
            $submitRoute = $questionnaire->public_hash
                ? route('questionnaire.public_submit', $questionnaire->public_hash)
                : route('questionnaire.registerd_only_submit', $questionnaire->id);
        @endphp
        
        <form method="POST" action="{{ $submitRoute }}" class="bg-white p-6 rounded-2xl shadow space-y-6" id="questionnaireForm">
            @csrf

            @foreach ($questionnaire->questions as $index => $question)
                <div class="border-b pb-4 question-container" 
                     data-question-id="{{ $question->id }}"
                     data-parent-id="{{ $question->parent_question_id ?? '' }}"
                     data-question-type="{{ $question->type }}">
                    
                    <h3 class="font-bold text-lg mb-2">{{ $index + 1 }}. {{ $question->question_text }}</h3>
                    
                    @if ($question->description)
                        <p class="text-sm text-gray-600 mb-3">{{ $question->description }}</p>
                    @endif

                    {{-- Question Types --}}
                    @switch($question->type)
                        @case('text')
                            <textarea name="question_{{ $question->id }}" 
                                      class="w-full border rounded-lg p-2 focus:ring focus:ring-green-200"
                                      required></textarea>
                        @break

                        @case('date')
                            <input type="date" 
                                   name="question_{{ $question->id }}"
                                   class="w-full border rounded-lg p-2 focus:ring focus:ring-green-200" 
                                   required>
                        @break

                        @case('single')
                            @foreach ($question->choices as $choice)
                                <label class="flex items-center gap-2 mb-1">
                                    <input type="radio" 
                                           name="question_{{ $question->id }}" 
                                           value="{{ $choice->id }}"
                                           class="text-green-600" 
                                           required>
                                    <span>{{ $choice->choice_text }}</span>
                                </label>
                            @endforeach
                        @break
                        
                        @case('dropdown')
                            <div class="relative">
                                <select name="question_{{ $question->id }}" 
                                        id="question_{{ $question->id }}"
                                        class="w-full border rounded-lg p-2 focus:ring focus:ring-green-200 appearance-none bg-white dropdown-question"
                                        data-question-id="{{ $question->id }}"
                                        @if($question->parent_question_id) data-parent-question-id="{{ $question->parent_question_id }}" @endif
                                        required>
                                    <option value="" disabled selected>-- اختر من القائمة --</option>
                                    
                                    @if($question->parent_question_id)
                                        {{-- Dependent dropdown: choices will be populated by JavaScript --}}
                                        @foreach ($question->choices as $choice)
                                            <option value="{{ $choice->id }}" 
                                                    data-parent-choice-id="{{ $choice->parent_choice_id }}"
                                                    style="display:none;">
                                                {{ $choice->choice_text }}
                                            </option>
                                        @endforeach
                                    @else
                                        {{-- Independent dropdown: show all choices --}}
                                        @foreach ($question->choices as $choice)
                                            <option value="{{ $choice->id }}">{{ $choice->choice_text }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                
                                {{-- Dropdown arrow --}}
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-700">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            
                            @if($question->parent_question_id)
                                <p class="text-xs text-gray-500 mt-1">
                                    <i>يعتمد على السؤال السابق</i>
                                </p>
                            @endif
                        @break

                        @case('multiple')
                            @foreach ($question->choices as $choice)
                                <label class="flex items-center gap-2 mb-1">
                                    <input type="checkbox" 
                                           name="question_{{ $question->id }}[]" 
                                           value="{{ $choice->id }}"
                                           class="text-green-600">
                                    <span>{{ $choice->choice_text }}</span>
                                </label>
                            @endforeach
                        @break

                        @case('range')
                            @php
                                $min = $question->min_value ?? 1;
                                $max = $question->max_value ?? 10;
                                $total = $max - $min;
                                
                                // Color gradient function: Red (low) -> Yellow (mid) -> Blue (high)
                                if (!function_exists('getRangeColor')) {
                                    function getRangeColor($value, $min, $max) {
                                        $percent = ($value - $min) / max(1, $max - $min);
                                        
                                        if ($percent <= 0.5) {
                                            // Red to Yellow (0% - 50%)
                                            $r = 185;
                                            $g = intval(28 + (180 * ($percent * 2)));
                                            $b = 28;
                                        } else {
                                            // Yellow to Blue (50% - 100%)
                                            $adjustedPercent = ($percent - 0.5) * 2;
                                            $r = intval(185 - (185 * $adjustedPercent));
                                            $g = intval(208 - (130 * $adjustedPercent));
                                            $b = intval(28 + (191 * $adjustedPercent));
                                        }
                                        
                                        return "rgb($r, $g, $b)";
                                    }
                                }
                            @endphp
                            <div class="range-buttons-container">
                                <input type="hidden" 
                                       name="question_{{ $question->id }}" 
                                       id="range_input_{{ $question->id }}"
                                       required>
                                <div class="flex flex-wrap gap-2 justify-center">
                                    @for($i = $min; $i <= $max; $i++)
                                        @php
                                            $bgColor = getRangeColor($i, $min, $max);
                                        @endphp
                                        <button type="button"
                                                class="range-btn w-12 h-12 rounded-lg border-2 border-transparent bg-gray-100 text-gray-700 font-bold text-lg
                                                       hover:bg-gray-200 hover:scale-110 hover:shadow-lg transition-all duration-200
                                                       focus:outline-none focus:ring-2 focus:ring-offset-2"
                                                data-active-color="{{ $bgColor }}"
                                                data-value="{{ $i }}"
                                                data-input="range_input_{{ $question->id }}"
                                                onclick="selectRangeValue(this)">
                                            {{ $i }}
                                        </button>
                                    @endfor
                                </div>
                                <div class="flex justify-between mt-3 text-xs px-1">
                                    <span class="text-red-700 font-medium">😞 منخفض</span>
                                    <span class="text-blue-700 font-medium">😊 مرتفع</span>
                                </div>
                            </div>
                        @break
                    @endswitch

                    @if ($question->note_attachment)
                        <input type="text" 
                               name="note_{{ $question->id }}" 
                               placeholder="ملحوظة (اختياري)"
                               class="w-full mt-3 border border-gray-300 rounded-lg p-2 text-sm focus:ring focus:ring-green-200">
                    @endif

                    {{-- Validation error display --}}
                    @error("question_{$question->id}")
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    إرسال الإجابات
                </button>
            </div>
        </form>
    </div>

    <script>
        // Range button selection function
        function selectRangeValue(button) {
            const value = button.getAttribute('data-value');
            const inputId = button.getAttribute('data-input');
            const hiddenInput = document.getElementById(inputId);
            
            // Update hidden input value
            hiddenInput.value = value;
            
            // Remove selected class and reset styles from all buttons in this group
            const container = button.closest('.range-buttons-container');
            container.querySelectorAll('.range-btn').forEach(btn => {
                btn.classList.remove('selected', 'text-white', 'scale-110');
                btn.style.backgroundColor = ''; // Revert to CSS default (gray)
                btn.style.borderColor = 'transparent';
                
                // Re-add default gray classes if they were removed (optional, but good for safety)
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            
            // Remove error styling from container
            container.classList.remove('ring-2', 'ring-red-500', 'ring-offset-2', 'rounded-lg', 'p-2');
            
            // Add selected styling to clicked button
            button.classList.add('selected', 'text-white', 'scale-110');
            button.classList.remove('bg-gray-100', 'text-gray-700');
            
            const activeColor = button.getAttribute('data-active-color');
            button.style.backgroundColor = activeColor;
            button.style.borderColor = activeColor;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize dependent dropdown system
            const dropdowns = document.querySelectorAll('.dropdown-question');
            
            // Function to update dependent dropdowns
            function updateDependentDropdowns(parentQuestionId, selectedChoiceId) {
                // Find all dropdowns that depend on this parent
                const dependentDropdowns = document.querySelectorAll(
                    `.dropdown-question[data-parent-question-id="${parentQuestionId}"]`
                );
                
                dependentDropdowns.forEach(dropdown => {
                    // Reset the dropdown
                    dropdown.value = '';
                    
                    // Get all options in this dependent dropdown
                    const options = dropdown.querySelectorAll('option[data-parent-choice-id]');
                    
                    // Show/hide options based on parent selection
                    options.forEach(option => {
                        const parentChoiceId = option.getAttribute('data-parent-choice-id');
                        
                        if (parentChoiceId === selectedChoiceId) {
                            option.style.display = '';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                    
                    // Enable or disable the dropdown
                    if (selectedChoiceId) {
                        dropdown.disabled = false;
                        dropdown.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    } else {
                        dropdown.disabled = true;
                        dropdown.classList.add('bg-gray-100', 'cursor-not-allowed');
                    }
                    
                    // Recursively update any dropdowns that depend on this one
                    const thisQuestionId = dropdown.getAttribute('data-question-id');
                    updateDependentDropdowns(thisQuestionId, null);
                });
            }
            
            // Add change event listeners to all dropdowns
            dropdowns.forEach(dropdown => {
                // Initially disable dependent dropdowns
                if (dropdown.hasAttribute('data-parent-question-id')) {
                    dropdown.disabled = true;
                    dropdown.classList.add('bg-gray-100', 'cursor-not-allowed');
                }
                
                dropdown.addEventListener('change', function() {
                    const questionId = this.getAttribute('data-question-id');
                    const selectedValue = this.value;
                    
                    // Update any dependent dropdowns
                    updateDependentDropdowns(questionId, selectedValue);
                });
            });
            
            // Form validation enhancement
            const form = document.getElementById('questionnaireForm');
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const requiredDropdowns = form.querySelectorAll('.dropdown-question[required]');
                
                requiredDropdowns.forEach(dropdown => {
                    if (!dropdown.disabled && !dropdown.value) {
                        isValid = false;
                        dropdown.classList.add('border-red-500');
                        
                        // Remove error styling after user interaction
                        dropdown.addEventListener('change', function() {
                            this.classList.remove('border-red-500');
                        }, { once: true });
                    }
                });

                // Validate range button inputs
                const rangeInputs = form.querySelectorAll('.range-buttons-container input[type="hidden"][required]');
                rangeInputs.forEach(input => {
                    if (!input.value) {
                        isValid = false;
                        const container = input.closest('.range-buttons-container');
                        container.classList.add('ring-2', 'ring-red-500', 'ring-offset-2', 'rounded-lg', 'p-2');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('الرجاء الإجابة على جميع الأسئلة المطلوبة');
                }
            });
        });
    </script>

</body>

</html>