@extends('layouts.pos-app')

@section('title', 'Opening Stock — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-screen-2xl">
        <div class="pos-dashboard-card">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-800">Opening Stock</h1>
                    <p class="mt-1 text-sm text-slate-500">Add multiple items for initial inventory. Purchases and sales will adjust balance later.</p>
                </div>
                <a href="{{ route('pos.vendors.create', ['redirect_to' => 'pos.stock.opening.create']) }}" class="pos-btn-primary w-auto! px-5 py-2.5">Add Vendor</a>
            </div>

            <form method="POST" action="{{ route('pos.stock.opening.store') }}" class="mt-6 space-y-5" id="opening-stock-form">
                @csrf

                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label for="vendor_id" class="pos-label">Vendor Name</label>
                        <select id="vendor_id" name="vendor_id" class="pos-input @error('vendor_id') pos-input-error @enderror">
                            <option value="">Select vendor (optional)</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}" @selected(old('vendor_id') == $vendor->id)>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reference_no" class="pos-label">Reference No *</label>
                        <input id="reference_no" name="reference_no" type="text" value="{{ old('reference_no') }}" placeholder="Enter reference number" required class="pos-input @error('reference_no') pos-input-error @enderror">
                        @error('reference_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="entry_date" class="pos-label">Entry Date *</label>
                        <input id="entry_date" name="entry_date" type="date" value="{{ old('entry_date', now()->format('Y-m-d')) }}" required class="pos-input @error('entry_date') pos-input-error @enderror">
                        @error('entry_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label for="notes" class="pos-label">Notes</label>
                        <input id="notes" name="notes" type="text" value="{{ old('notes') }}" placeholder="Optional notes" class="pos-input @error('notes') pos-input-error @enderror">
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
                    <table class="min-w-full text-sm" id="opening-items-table">
                        <thead class="bg-sky-50 text-left text-slate-700">
                            <tr>
                                <th class="px-3 py-2 font-semibold">SL#</th>
                                <th class="min-w-56 px-3 py-2 font-semibold">Product Name *</th>
                                <th class="min-w-40 px-3 py-2 font-semibold">HSN/SAC</th>
                                <th class="px-3 py-2 font-semibold">Price *</th>
                                <th class="px-3 py-2 font-semibold">Qty *</th>
                                <th class="px-3 py-2 font-semibold">Total</th>
                                <th class="px-3 py-2 font-semibold"></th>
                            </tr>
                        </thead>
                        <tbody id="opening-items-body" class="divide-y divide-slate-200"></tbody>
                    </table>
                </div>

                @error('items')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="flex justify-between">
                    <button type="button" id="add-row-btn" class="pos-btn-ghost">+ Add Item</button>
                    <p class="text-sm font-semibold text-slate-700">Grand Total: <span id="grand-total">Rs 0.00</span></p>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('pos.stock') }}" class="pos-btn-ghost">Cancel</a>
                    <a href="{{ route('pos.stock.index') }}" class="pos-btn-ghost">Inventory List</a>
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Save Opening Stock</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        (() => {
            const body = document.getElementById('opening-items-body');
            const addRowBtn = document.getElementById('add-row-btn');
            const grandTotalEl = document.getElementById('grand-total');
            const hsnOptions = @json($hsnSacOptions ?? []);
            const hsnDescriptions = @json($hsnDescriptions ?? []);
            const oldItems = @json(old('items', []));
            let rowIndex = 0;

            const hsnSelectHtml = (selected = '') => {
                const options = ['<option value="">Select HSN/SAC</option>']
                    .concat(hsnOptions.map((hsn) => `<option value="${hsn}" ${selected === hsn ? 'selected' : ''}>${hsn}</option>`));

                return `<select name="items[__INDEX__][hsn_sac]" class="pos-input item-hsn">${options.join('')}</select><p class="mt-1 text-xs text-slate-500 item-hsn-help"></p>`;
            };

            const createRow = (data = {}) => {
                const index = rowIndex++;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-3 py-2 row-sl">${index + 1}</td>
                    <td class="px-3 py-2">
                        <input type="text" name="items[${index}][product_name]" value="${data.product_name ?? ''}" placeholder="Product name" required class="pos-input item-product">
                    </td>
                    <td class="px-3 py-2">
                        ${hsnSelectHtml(data.hsn_sac ?? '').replaceAll('__INDEX__', String(index))}
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" step="0.01" min="0" name="items[${index}][price]" value="${data.price ?? ''}" placeholder="0.00" required class="pos-input item-price">
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" min="1" name="items[${index}][qty]" value="${data.qty ?? '1'}" placeholder="1" required class="pos-input item-qty">
                    </td>
                    <td class="px-3 py-2">
                        <input type="text" readonly class="pos-input item-total bg-slate-50" value="Rs 0.00">
                    </td>
                    <td class="px-3 py-2">
                        <button type="button" class="pos-btn-ghost remove-row py-1.5 text-xs">Remove</button>
                    </td>
                `;

                body.appendChild(row);
                bindRow(row);
                updateTotals();
            };

            const bindRow = (row) => {
                const priceInput = row.querySelector('.item-price');
                const qtyInput = row.querySelector('.item-qty');
                const totalInput = row.querySelector('.item-total');
                const hsnInput = row.querySelector('.item-hsn');
                const hsnHelp = row.querySelector('.item-hsn-help');
                const removeBtn = row.querySelector('.remove-row');

                const updateRowTotal = () => {
                    const price = Number.parseFloat(priceInput.value || '0');
                    const qty = Number.parseInt(qtyInput.value || '0', 10);
                    const total = (Number.isFinite(price) ? price : 0) * (Number.isFinite(qty) ? qty : 0);
                    totalInput.value = `Rs ${total.toFixed(2)}`;
                    updateTotals();
                };

                const updateHsnHelp = () => {
                    const description = hsnDescriptions[hsnInput.value] || '';
                    hsnHelp.textContent = description ? `Description: ${description}` : '';
                };

                priceInput.addEventListener('input', updateRowTotal);
                qtyInput.addEventListener('input', updateRowTotal);
                hsnInput.addEventListener('change', updateHsnHelp);
                removeBtn.addEventListener('click', () => {
                    row.remove();
                    renumberRows();
                    updateTotals();
                });

                updateRowTotal();
                updateHsnHelp();
            };

            const renumberRows = () => {
                body.querySelectorAll('tr').forEach((row, index) => {
                    row.querySelector('.row-sl').textContent = String(index + 1);
                });
            };

            const updateTotals = () => {
                let grand = 0;
                body.querySelectorAll('tr').forEach((row) => {
                    const price = Number.parseFloat(row.querySelector('.item-price')?.value || '0');
                    const qty = Number.parseInt(row.querySelector('.item-qty')?.value || '0', 10);
                    grand += (Number.isFinite(price) ? price : 0) * (Number.isFinite(qty) ? qty : 0);
                });
                grandTotalEl.textContent = `Rs ${grand.toFixed(2)}`;
            };

            addRowBtn?.addEventListener('click', () => createRow());

            if (oldItems.length > 0) {
                oldItems.forEach((item) => createRow(item));
            } else {
                createRow();
            }
        })();
    </script>
@endsection
