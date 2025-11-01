<x-app-layout title="عرض رمز QR">
    <div class="p-6 text-center">
    
        <div class="flex justify-center mb-4">
            {!! $qrImage !!}
        </div>

        <p class="text-gray-700 break-all">{{ $qr->content }}</p>


    </div>
</x-app-layout>
