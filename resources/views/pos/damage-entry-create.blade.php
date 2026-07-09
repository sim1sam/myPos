@extends('layouts.pos-app')

@section('title', 'Damage Entry — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-5xl">
        <div class="pos-dashboard-card">
            <h1 class="text-2xl font-semibold text-slate-800">Damage Entry</h1>
            <p class="mt-1 text-sm text-slate-500">Record damaged or lost items. Quantity will be deducted from inventory balance.</p>

            <form method="POST" action="{{ route('pos.stock.damage.store') }}" class="mt-6 grid gap-5 md:grid-cols-2">
                @csrf

                <div>
                    <label for="entry_date" class="pos-label">Entry Date *</label>
                    <input id="entry_date" name="entry_date" type="date" value="{{ old('entry_date', now()->format('Y-m-d')) }}" required class="pos-input @error('entry_date') pos-input-error @enderror">
                    @error('entry_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="product_name" class="pos-label">Product Name *</label>
                    <input id="product_name" name="product_name" type="text" value="{{ old('product_name') }}" placeholder="Enter product name" required class="pos-input @error('product_name') pos-input-error @enderror">
                    @error('product_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hsn_sac" class="pos-label">HSN/SAC</label>
                    <select id="hsn_sac" name="hsn_sac" class="pos-input @error('hsn_sac') pos-input-error @enderror">
                        <option value="">Select HSN/SAC</option>
                        @foreach ($hsnSacOptions as $hsnSac)
                            <option value="{{ $hsnSac }}" @selected(old('hsn_sac') == $hsnSac)>{{ $hsnSac }}</option>
                        @endforeach
                    </select>
                    <p id="hsn_description_help" class="mt-1 text-xs text-slate-500"></p>
                    @error('hsn_sac')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="qty" class="pos-label">Damaged Qty *</label>
                    <input id="qty" name="qty" type="number" min="1" value="{{ old('qty') }}" placeholder="1" required class="pos-input @error('qty') pos-input-error @enderror">
                    @error('qty')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="pos-label">Price (optional)</label>
                    <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" placeholder="0.00" class="pos-input @error('price') pos-input-error @enderror">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reason" class="pos-label">Reason</label>
                    <input id="reason" name="reason" type="text" value="{{ old('reason') }}" placeholder="e.g. Broken, expired" class="pos-input @error('reason') pos-input-error @enderror">
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="pos-label">Notes</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Additional notes" class="pos-input @error('notes') pos-input-error @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2 flex items-center justify-end gap-3">
                    <a href="{{ route('pos.stock') }}" class="pos-btn-ghost">Cancel</a>
                    <a href="{{ route('pos.stock.index') }}" class="pos-btn-ghost">Inventory List</a>
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Save Damage Entry</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        (() => {
            const hsnInput = document.getElementById('hsn_sac');
            const hsnHelp = document.getElementById('hsn_description_help');
            const hsnDescriptions = @json($hsnDescriptions ?? []);

            if (!hsnInput || !hsnHelp) return;

            const updateHsnDescription = () => {
                const description = hsnDescriptions[hsnInput.value] || '';
                hsnHelp.textContent = description ? `Description: ${description}` : '';
            };

            hsnInput.addEventListener('change', updateHsnDescription);
            updateHsnDescription();
        })();
    </script>
@endsection
