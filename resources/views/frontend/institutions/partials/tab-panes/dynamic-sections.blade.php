@foreach ($institution->sections as $sec)
    @if (!empty(trim(strip_tags($sec->content))))
        <div id="pane-sec-{{ $sec->id }}" x-show="activeTab === 'sec_{{ $sec->id }}'" x-transition.opacity.duration.400ms style="display: none;">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1.5 h-6 bg-[#FFD700] rounded-sm"></div>
                <h2 class="text-2xl font-bold text-[#1E234B]">
                    {{ ucwords(str_replace('_', ' ', $sec->type)) }}
                </h2>
            </div>
            <div class="prose max-w-none text-gray-600 leading-relaxed text-[15px] bg-[#F8F9FA] p-6 md:p-8 rounded-2xl shadow-sm border border-gray-50">
                {!! $sec->content !!}
            </div>
        </div>
    @endif
@endforeach
