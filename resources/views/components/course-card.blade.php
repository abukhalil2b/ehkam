<div class="w-full sm:w-1/3 px-4 mb-8">
    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
        <img src="{{ $imageSrc }}" alt="{{ $imageAlt }}" class="w-full h-48 object-cover">
        <div class="p-6">
            <h3 class="text-xl font-bold text-tiber mb-2">{{ $title }}</h3>
            @if($trainer)
            <p class="text-nevada mb-4">المدرب: {{ $trainer }}</p>
            @endif
            <a href="{{ $detailsUrl }}" class="text-caribeanGreen font-semibold hover:underline">
                التفاصيل &rarr;
            </a>
        </div>
    </div>
</div>
