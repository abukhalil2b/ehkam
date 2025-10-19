<div class="absolute left-0 bottom-0 mb-5 ml-5 text-center">
    <svg class="RadiusProgress" width="60" height="60" viewBox="0 0 60 60" style="background-color: white; fill: none;">
        <circle 
            class="RadiusProgress__meter text-gray-200" 
            cx="30" cy="30" r="{{ $radius }}" 
            stroke="currentColor" 
            stroke-width="2"
            fill="none"
        ></circle>
        <circle 
            class="RadiusProgress__value text-teal-400" 
            cx="30" cy="30" r="{{ $radius }}" 
            stroke="currentColor" 
            stroke-width="5"
            stroke-dasharray="{{ $circumference }}"
            stroke-dashoffset="{{ $dashoffset }}"
            fill="none"
        ></circle>
    </svg>
    <div class="antialiased font-helvetica flex-col leading-none text-tiber p-2 absolute top-0 right-0 w-full h-full items-center flex justify-center">
        <div class="font-frutiger text-sm">{{ $percentage }}%</div>
        <div class="text-xs z-40">منجز</div>
    </div>
</div>