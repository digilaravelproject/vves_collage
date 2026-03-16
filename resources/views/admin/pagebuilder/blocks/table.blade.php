@php
    $model = $model ?? 'block';
    $compact = $compact ?? false;
@endphp

<div class="space-y-3">
    <div class="flex flex-wrap gap-1">
        <button @click.prevent="addRow({{ $model }})" class="px-2 py-1 text-[10px] bg-green-50 text-green-700 border border-green-200 rounded">+ R</button>
        <button @click.prevent="removeRow({{ $model }})" :disabled="{{ $model }}.data.length <= 1" class="px-2 py-1 text-[10px] bg-red-50 text-red-700 border border-red-200 rounded disabled:opacity-50">- R</button>
        <button @click.prevent="addCol({{ $model }})" class="px-2 py-1 text-[10px] bg-green-50 text-green-700 border border-green-200 rounded">+ C</button>
        <button @click.prevent="removeCol({{ $model }})" :disabled="{{ $model }}.data[0].length <= 1" class="px-2 py-1 text-[10px] bg-red-50 text-red-700 border border-red-200 rounded disabled:opacity-50">- C</button>
    </div>

    <div class="overflow-x-auto border border-gray-200 rounded max-h-[400px]">
        <table class="min-w-full divide-y divide-gray-200 {{ $compact ? 'text-[10px]' : 'text-xs' }}">
            <thead class="bg-gray-50 sticky top-0">
                <tr>
                    <template x-for="(h, hi) in {{ $model }}.data[0]" :key="hi">
                        <th class="p-2 border-r border-gray-200 text-center">
                            <input type="text" x-model.debounce.400ms="{{ $model }}.data[0][hi].text" @input="pushHistory"
                                class="w-full text-center bg-transparent border-none p-0 font-bold focus:ring-0" />
                            <input type="text" x-model.debounce.400ms="{{ $model }}.data[0][hi].href" @input="pushHistory"
                                class="w-full text-center bg-transparent border-none p-0 text-[10px] text-blue-500 placeholder-blue-300"
                                placeholder="URL" />
                        </th>
                    </template>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <template x-for="(row, ri) in {{ $model }}.data" :key="ri">
                    <template x-if="ri > 0">
                        <tr class="hover:bg-gray-50">
                            <template x-for="(cell, ci) in row" :key="ci">
                                <td class="p-2 border-r border-gray-200 align-top">
                                    <template x-if="cell.img">
                                        <div class="relative group mb-1">
                                            <img :src="cell.img" class="w-8 h-8 object-cover rounded mx-auto border" />
                                            <button @click="cell.img = ''; pushHistory();" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px]">×</button>
                                        </div>
                                    </template>
                                    <textarea x-model.debounce.400ms="cell.text" @input="pushHistory" rows="2"
                                        class="w-full p-1 border-gray-100 rounded focus:border-blue-200 focus:ring-1 focus:ring-blue-100 resize-none leading-tight" placeholder="Text"></textarea>
                                    <input type="text" x-model.debounce.400ms="cell.href" @input="pushHistory"
                                        class="w-full mt-1 p-1 text-[10px] border border-gray-50 rounded focus:border-blue-100 outline-none bg-gray-50/50"
                                        placeholder="🔗 Link" />
                                    <template x-if="!cell.img">
                                        <label class="cursor-pointer text-blue-500 mt-1 block text-center hover:underline">
                                            + Photo
                                            <input type="file" accept="image/*" class="hidden" @change="handleTableUpload($event, {{ $model }}, ri, ci)" />
                                        </label>
                                    </template>
                                </td>
                            </template>
                        </tr>
                    </template>
                </template>
            </tbody>
        </table>
    </div>
</div>
