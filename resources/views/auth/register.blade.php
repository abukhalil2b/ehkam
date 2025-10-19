<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">إنشاء حساب جديد</h2>
                <p class="mt-2 text-sm text-gray-600">املأ النموذج أدناه للتسجيل</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">الاسم الكامل</label>
                    <div class="mt-1">
                        <x-text-input id="name"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
                    </div>
                </div>

                <!-- Gender -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">الجنس</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="male" class="sr-only peer" checked>
                            <div
                                class="bg-gray-100 p-4 rounded-lg border-2 border-transparent peer-checked:border-indigo-500 peer-checked:bg-indigo-50 text-center hover:bg-gray-200 transition-colors duration-200">
                                <span class="block text-sm font-medium text-gray-700">ذكر</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="female" class="sr-only peer">
                            <div
                                class="bg-gray-100 p-4 rounded-lg border-2 border-transparent peer-checked:border-indigo-500 peer-checked:bg-indigo-50 text-center hover:bg-gray-200 transition-colors duration-200">
                                <span class="block text-sm font-medium text-gray-700">أنثى</span>
                            </div>
                        </label>
                    </div>
                </div>
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">رقم الجوال</label>
                    <div class="mt-1">
                        <x-text-input
                            class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            type="tel" name="phone" :value="old('phone')" required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-600" />
                    </div>
                </div>

                <!-- National ID -->
                <div>
                    <label for="civil_id" class="block text-sm font-medium text-gray-700">الرقم المدني</label>
                    <div class="mt-1">
                        <x-text-input
                            class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            type="number" name="civil_id" :value="old('civil_id')" required />
                        <x-input-error :messages="$errors->get('civil_id')" class="mt-2 text-sm text-red-600" />
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور</label>
                    <div class="mt-1">
                        <x-text-input id="password"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                    </div>
                    <p class="mt-2 text-xs text-gray-500">يجب أن تكون كلمة المرور مكونة من 8 أحرف على الأقل</p>
                </div>

                <div>
                    <x-primary-button>
                        تسجيل الحساب
                    </x-primary-button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    لديك حساب بالفعل؟
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        تسجيل الدخول
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
