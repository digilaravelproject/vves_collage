@if ($institution->academic_diary_pdf)
    <div id="pane-academic-calendar" x-show="activeTab === 'academic_calendar'" x-transition.opacity.duration.400ms
        style="display: none;">
        <div class="flex items-center gap-3 mb-6 md:mb-10">
            <div class="w-1.5 h-6 md:h-8 bg-[#FFD700] rounded-sm"></div>
            <h2 class="text-xl md:text-3xl font-black text-[#1E234B] tracking-tight uppercase">Academic Diary</h2>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden group">
            <div class="bg-[#000165] px-6 py-4 flex items-center justify-between border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-[#FFD700]">
                        <i class="bi bi-file-earmark-pdf-fill text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black uppercase tracking-widest text-sm" style="color: #ffffff !important;">
                            Academic Schedule</h3>
                        <p class="text-white/60 text-[10px] uppercase font-bold tracking-widest">PDF Document</p>
                    </div>
                </div>
                <a href="{{ url('pdf-viewer/storage/' . $institution->academic_diary_pdf) }}" target="_blank"
                    class="shrink-0 px-4 py-2 bg-white text-[#000165] hover:bg-[#FFD700] font-black uppercase tracking-widest text-[10px] rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                    <i class="bi bi-box-arrow-up-right text-sm"></i> Open Viewer
                </a>
            </div>
            <div class="p-4 md:p-8 bg-[#F8F9FA]">
                <div class="rounded-xl overflow-hidden shadow-sm border border-gray-200 bg-white">
                    <x-pdf-viewer :src="asset('storage/' . $institution->academic_diary_pdf)" />
                </div>
            </div>
        </div>
    </div>
@endif
