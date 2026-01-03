<x-app-layout>

    <form action="{{ route('project.update', $project->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="container py-8 mx-auto px-4">

            {{-- Indicator --}}
            <div class="mb-4">
                <label for="indicator_id" class="block text-sm font-medium text-gray-700 mb-1">
                    المؤشر
                </label>

                <select
                    id="indicator_id"
                    name="indicator_id"
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                           focus:ring-blue-500 focus:border-blue-500 p-2.5"
                    required
                >
                    <option value="" disabled>اختر المؤشر...</option>

                    @foreach ($indicators as $indicator)
                        <option value="{{ $indicator->id }}"
                            {{ old('indicator_id', $project->indicator_id) == $indicator->id ? 'selected' : '' }}>
                            {{ $indicator->title }} - {{ $indicator->current_year }}
                        </option>
                    @endforeach
                </select>

                @error('indicator_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Executor --}}
            <div class="mb-4">
                <label for="executor_id" class="block text-sm font-medium text-gray-700 mb-1">
                    المنفذ
                </label>

                <select
                    id="executor_id"
                    name="executor_id"
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                           focus:ring-blue-500 focus:border-blue-500 p-2.5"
                    required
                >
                    <option value="" disabled>اختر المنفذ...</option>

                    @foreach ($executors as $executor)
                        <option value="{{ $executor->id }}"
                            {{ old('executor_id', $project->executor_id) == $executor->id ? 'selected' : '' }}>
                            {{ $executor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Project Type --}}
            <div class="mb-4">
                <label for="cate" class="block text-sm font-medium text-gray-700 mb-1">
                    نوع المشروع
                </label>

                <select
                    id="cate"
                    name="cate"
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                           focus:ring-blue-500 focus:border-blue-500 p-2.5"
                    required
                >
                    <option value="" disabled>اختر نوع المشروع</option>
                    <option value="مشروع" {{ old('cate', $project->cate) == 'مشروع' ? 'selected' : '' }}>
                        مشروع
                    </option>
                    <option value="مبادرة تمكينية" {{ old('cate', $project->cate) == 'مبادرة تمكينية' ? 'selected' : '' }}>
                        مبادرة تمكينية
                    </option>
                </select>
            </div>

            {{-- Title --}}
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    الاسم
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $project->title) }}"
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                           focus:ring-blue-500 focus:border-blue-500 p-2.5"
                    placeholder="أدخل اسم المشروع"
                    required
                >
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    الوصف
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                           focus:ring-blue-500 focus:border-blue-500 p-2.5"
                    placeholder="أدخل وصف المشروع"
                >{{ old('description', $project->description) }}</textarea>
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="mt-7 px-6 py-2 text-sm font-medium rounded-md text-white bg-green-600
                       hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                       focus:ring-green-500"
            >
                حفظ المشروع
            </button>

        </div>
    </form>

</x-app-layout>
