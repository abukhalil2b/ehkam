<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('الملف الشخصي') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                        <h2 class="text-lg font-medium text-gray-900">
                           {{ auth()->user()->name }}
                           
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                          {{ auth()->user()->email }}
                        </p>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            تحديث كلمة المرور
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            تأكد من أن حسابك يستخدم كلمة مرور طويلة وعشوائية للحفاظ على الأمان.
                        </p>
                    </header>

                    <form action="{{ route('password.update') }}" method="POST" class="mt-6 space-y-6">
                        @method('PUT')
                        @csrf
                        <div>
                            <x-input-label for="update_password_current_password" value="كلمة المرور الحالية" />
                            <x-text-input id="update_password_current_password" name="current_password" type="password"
                                class="mt-1 block w-full" autocomplete="current-password" />
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="update_password_password" value="كلمة المرور الجديدة" />
                            <x-text-input id="update_password_password" name="password" type="password"
                                class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="update_password_password_confirmation" value="تأكيد كلمة المرور" />
                            <x-text-input id="update_password_password_confirmation" name="password_confirmation"
                                type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>حفظ</x-primary-button>

                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                                    class="text-sm text-gray-600">
                                    تم الحفظ.
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
