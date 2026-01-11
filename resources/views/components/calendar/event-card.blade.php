<div @click="openEdit(e)"
     class="flex-1 p-3 rounded-lg text-white shadow-sm cursor-pointer hover:scale-[1.02] transition-transform relative overflow-hidden group/card mb-1"
     :style="`background-color: ${e.bg_color}; border-right: 4px solid rgba(0,0,0,0.1)`">
    
    <div class="font-bold text-sm truncate" x-text="e.title"></div>
    <div class="flex justify-between items-end mt-1">
        <div class="text-[10px] opacity-90 font-mono" x-text="e.startTime + ' - ' + e.endTime"></div>
        <div class="text-[10px] bg-white/20 px-1.5 py-0.5 rounded backdrop-blur-sm" x-text="e.type"></div>
    </div>
</div>