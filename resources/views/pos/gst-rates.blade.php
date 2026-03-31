@extends('layouts.pos-app')

@section('title', 'GST Rates — ' . config('app.name'))

@section('page-content')
    <section>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-800">GST Rates</h1>

        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 pos-dashboard-card">
            <h2 class="text-lg font-semibold text-slate-800">Configure GST Rate (HSN/SAC)</h2>

            <form method="POST" action="{{ route('pos.settings.gst-rates.store') }}" class="mt-4 space-y-4" id="gst-rate-form">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="pos-label" for="hsn_sac">HSN/SAC</label>
                        <input id="hsn_sac" name="hsn_sac" type="text" class="pos-input" value="{{ old('hsn_sac') }}" placeholder="Enter HSN/SAC" required>
                    </div>
                    <div>
                        <label class="pos-label" for="description">Description</label>
                        <input id="description" name="description" type="text" class="pos-input" value="{{ old('description') }}" placeholder="Enter description">
                    </div>
                    <div>
                        <label class="pos-label" for="gst_type">GST Type</label>
                        <select id="gst_type" name="gst_type" class="pos-input">
                            <option value="simple" @selected(old('gst_type', 'simple') === 'simple')>Simple</option>
                            <option value="slab" @selected(old('gst_type') === 'slab')>Slab</option>
                        </select>
                    </div>
                    <div id="simple-rate-wrap">
                        <label class="pos-label" for="simple_rate">GST Rate (%)</label>
                        <input id="simple_rate" name="simple_rate" type="number" min="0" max="100" step="0.01" class="pos-input" value="{{ old('simple_rate') }}" placeholder="e.g. 18">
                    </div>
                </div>

                <div id="slab-wrap" class="hidden">
                    <div class="mb-2 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-700">Slab Rates</h3>
                        <button type="button" id="add-slab-row" class="pos-btn-ghost py-1.5 text-xs">+ Add Slab</button>
                    </div>
                    <p class="mb-2 text-xs text-slate-500">Leave "To Amount" empty to consider Infinity.</p>
                    <div class="overflow-x-auto rounded-lg border border-slate-200">
                        <table class="min-w-full text-sm">
                            <thead class="bg-sky-50 text-left text-slate-700">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">From Amount</th>
                                    <th class="px-3 py-2 font-semibold">To Amount (Optional)</th>
                                    <th class="px-3 py-2 font-semibold">GST %</th>
                                    <th class="px-3 py-2 font-semibold"></th>
                                </tr>
                            </thead>
                            <tbody id="slab-body"></tbody>
                        </table>
                    </div>
                    @error('slabs')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Save GST Rate</button>
                </div>
            </form>
        </div>

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">HSN/SAC</th>
                            <th class="px-4 py-3 font-semibold">Description</th>
                            <th class="px-4 py-3 font-semibold">GST Type</th>
                            <th class="px-4 py-3 font-semibold">Rate</th>
                            <th class="px-4 py-3 font-semibold">Updated On</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($rates as $rate)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $rate->hsn_sac }}</td>
                                <td class="px-4 py-3">{{ $rate->description ?: '-' }}</td>
                                <td class="px-4 py-3 capitalize">{{ $rate->gst_type }}</td>
                                <td class="px-4 py-3">
                                    @if ($rate->gst_type === 'simple')
                                        {{ number_format((float) $rate->simple_rate, 2) }}%
                                    @else
                                        <div class="space-y-1 text-xs">
                                            @foreach ($rate->slabs as $slab)
                                                <div>
                                                    {{ number_format((float) $slab->min_amount, 2) }} -
                                                    {{ $slab->max_amount !== null ? number_format((float) $slab->max_amount, 2) : 'Infinity' }}
                                                    : <span class="font-semibold">{{ number_format((float) $slab->rate, 2) }}%</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ optional($rate->updated_at)->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">No GST rates configured yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pos-pagination mt-4">
            {{ $rates->links() }}
        </div>
    </section>

    <script>
        (() => {
            const gstType = document.getElementById('gst_type');
            const simpleWrap = document.getElementById('simple-rate-wrap');
            const slabWrap = document.getElementById('slab-wrap');
            const slabBody = document.getElementById('slab-body');
            const addSlabBtn = document.getElementById('add-slab-row');

            const toggleSections = () => {
                const slabMode = gstType.value === 'slab';
                simpleWrap.classList.toggle('hidden', slabMode);
                slabWrap.classList.toggle('hidden', !slabMode);
                if (slabMode && slabBody.children.length === 0) {
                    addSlabRow();
                }
            };

            const addSlabRow = () => {
                const index = slabBody.children.length;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-3 py-2"><input type="number" step="0.01" min="0" name="slabs[${index}][min_amount]" class="pos-input" placeholder="1"></td>
                    <td class="px-3 py-2"><input type="number" step="0.01" min="0" name="slabs[${index}][max_amount]" class="pos-input" placeholder="999"></td>
                    <td class="px-3 py-2"><input type="number" step="0.01" min="0" max="100" name="slabs[${index}][rate]" class="pos-input" placeholder="5"></td>
                    <td class="px-3 py-2"><button type="button" class="pos-btn-ghost py-1.5 text-xs remove-slab">Remove</button></td>
                `;
                slabBody.appendChild(row);
                row.querySelector('.remove-slab').addEventListener('click', () => {
                    row.remove();
                    renumberRows();
                });
            };

            const renumberRows = () => {
                [...slabBody.querySelectorAll('tr')].forEach((row, index) => {
                    row.querySelectorAll('input').forEach((input) => {
                        if (input.name.includes('[min_amount]')) input.name = `slabs[${index}][min_amount]`;
                        if (input.name.includes('[max_amount]')) input.name = `slabs[${index}][max_amount]`;
                        if (input.name.includes('[rate]')) input.name = `slabs[${index}][rate]`;
                    });
                });
            };

            gstType.addEventListener('change', toggleSections);
            addSlabBtn.addEventListener('click', addSlabRow);
            toggleSections();
        })();
    </script>
@endsection
