@extends('layouts.pos-app')

@section('title', 'Create Purchase — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-screen-2xl">
        <div class="pos-dashboard-card">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-2xl font-semibold text-slate-800">Create Purchase</h1>
                <a href="{{ route('pos.vendors.create', ['redirect_to' => 'pos.purchases.create']) }}" class="pos-btn-primary w-auto! px-5 py-2.5">Add Vendor</a>
            </div>

            @if (session('success'))
                <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('pos.purchases.store') }}" class="mt-6 space-y-5" id="purchase-form">
                @csrf
                <input type="hidden" name="items_json" id="items_json" value="{{ old('items_json') }}">

                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label for="vendor_id" class="pos-label">Vendor Name *</label>
                        <select id="vendor_id" name="vendor_id" required class="pos-input @error('vendor_id') pos-input-error @enderror">
                            <option value="">Select vendor</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}" @selected(old('vendor_id') == $vendor->id)>
                                    {{ $vendor->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="invoice_no" class="pos-label">Invoice No *</label>
                        <input id="invoice_no" name="invoice_no" type="text" value="{{ old('invoice_no') }}" placeholder="Enter invoice number" required class="pos-input @error('invoice_no') pos-input-error @enderror">
                        @error('invoice_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="invoice_date" class="pos-label">Invoice Date *</label>
                        <input id="invoice_date" name="invoice_date" type="date" value="{{ old('invoice_date') }}" required class="pos-input @error('invoice_date') pos-input-error @enderror">
                        @error('invoice_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @error('items_json')
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $message }}
                    </div>
                @enderror

                <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
                    <table class="min-w-full text-sm" id="purchase-items-table">
                        <thead class="bg-sky-50 text-left text-slate-700">
                            <tr>
                                <th class="px-3 py-2 font-semibold">SL#</th>
                                <th class="px-3 py-2 font-semibold min-w-64">Product Name *</th>
                                <th class="px-3 py-2 font-semibold min-w-48">HSN/SAC</th>
                                <th class="px-3 py-2 font-semibold">Price *</th>
                                <th class="px-3 py-2 font-semibold">Qty *</th>
                                <th class="px-3 py-2 font-semibold">Amount</th>
                                <th class="px-3 py-2 font-semibold"></th>
                            </tr>
                        </thead>
                        <tbody id="purchase-items-body" class="divide-y divide-slate-200"></tbody>
                    </table>
                </div>

                <div class="flex justify-between">
                    <button type="button" id="add-row-btn" class="pos-btn-ghost">+ Add Item</button>
                </div>

                <div class="grid gap-3 sm:ml-auto sm:max-w-sm">
                    <div class="flex items-center justify-between rounded-md bg-sky-100 px-3 py-2">
                        <span class="text-sm font-semibold text-sky-800">Grand Total</span>
                        <span id="grand-total" class="font-bold text-sky-900">Rs 0.00</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('pos.purchases.index') }}" class="pos-btn-ghost">List Purchases</a>
                    <a href="{{ route('pos.purchases') }}" class="pos-btn-ghost">Cancel</a>
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Save Purchase</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        (() => {
            const hsnOptions = @json($hsnSacOptions ?? []);
            const hsnDescriptions = @json($hsnDescriptions ?? []);
            const tbody = document.getElementById('purchase-items-body');
            const addRowBtn = document.getElementById('add-row-btn');
            const grandTotalEl = document.getElementById('grand-total');
            const formEl = document.getElementById('purchase-form');
            const itemsJsonEl = document.getElementById('items_json');

            const formatMoney = (n) => `Rs ${Number(n || 0).toFixed(2)}`;

            const renderGrandTotal = () => {
                let total = 0;
                tbody.querySelectorAll('tr').forEach((row) => {
                    total += Number(row.querySelector('.item-amount').value || 0);
                });
                grandTotalEl.textContent = formatMoney(total);
            };

            const rowTemplate = (sl) => `
                <tr>
                    <td class="px-3 py-2 align-top text-slate-700">${sl}</td>
                    <td class="px-3 py-2 min-w-64">
                        <input type="text" class="pos-input item-product" placeholder="Enter product name">
                    </td>
                    <td class="px-3 py-2 min-w-48">
                        <select class="pos-input item-hsn">
                            <option value="">Select HSN/SAC</option>
                            ${hsnOptions.map((h) => `<option value="${h}">${h}</option>`).join('')}
                        </select>
                        <p class="mt-1 text-xs text-rose-600 item-hsn-help"></p>
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" min="0" step="0.01" class="pos-input item-price" placeholder="0.00" value="">
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" min="1" step="1" class="pos-input item-qty" placeholder="1" value="1">
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" readonly class="pos-input item-amount bg-slate-50" value="0">
                    </td>
                    <td class="px-3 py-2 align-top">
                        <button type="button" class="pos-btn-ghost remove-row">X</button>
                    </td>
                </tr>
            `;

            const bindRowEvents = (row) => {
                const productEl = row.querySelector('.item-product');
                const hsnEl = row.querySelector('.item-hsn');
                const hsnHelp = row.querySelector('.item-hsn-help');
                const priceEl = row.querySelector('.item-price');
                const qtyEl = row.querySelector('.item-qty');
                const amountEl = row.querySelector('.item-amount');

                const updateHsnDescription = () => {
                    const description = hsnDescriptions[hsnEl.value] || '';
                    hsnHelp.textContent = description ? `Description: ${description}` : '';
                };

                const calcAmount = () => {
                    const price = Number(priceEl.value || 0);
                    const qty = Number.parseInt(qtyEl.value || '0', 10);
                    const amount = (Number.isFinite(price) ? price : 0) * (Number.isFinite(qty) ? qty : 0);
                    amountEl.value = amount.toFixed(2);
                    renderGrandTotal();
                };

                hsnEl.addEventListener('change', updateHsnDescription);
                priceEl.addEventListener('input', calcAmount);
                qtyEl.addEventListener('input', calcAmount);

                row.querySelector('.remove-row').addEventListener('click', () => {
                    if (tbody.querySelectorAll('tr').length <= 1) {
                        productEl.value = '';
                        hsnEl.value = '';
                        priceEl.value = '';
                        qtyEl.value = '1';
                        updateHsnDescription();
                        calcAmount();
                        return;
                    }
                    row.remove();
                    refreshSerials();
                    renderGrandTotal();
                });

                updateHsnDescription();
                calcAmount();

                return {
                    productEl,
                    hsnEl,
                    priceEl,
                    qtyEl,
                    amountEl,
                    calcAmount,
                    updateHsnDescription,
                };
            };

            const refreshSerials = () => {
                [...tbody.querySelectorAll('tr')].forEach((row, index) => {
                    row.children[0].textContent = String(index + 1);
                });
            };

            const addRow = (item = null) => {
                tbody.insertAdjacentHTML('beforeend', rowTemplate(tbody.querySelectorAll('tr').length + 1));
                const row = tbody.lastElementChild;
                const helpers = bindRowEvents(row);

                if (item) {
                    helpers.productEl.value = item.product_name || '';
                    helpers.hsnEl.value = item.hsn_sac || '';
                    helpers.priceEl.value = item.price ?? '';
                    helpers.qtyEl.value = item.qty ?? 1;
                    helpers.updateHsnDescription();
                    helpers.calcAmount();
                }

                return row;
            };

            addRowBtn.addEventListener('click', () => addRow());

            formEl.addEventListener('submit', (e) => {
                const items = [...tbody.querySelectorAll('tr')].map((row) => ({
                    product_name: (row.querySelector('.item-product').value || '').trim(),
                    hsn_sac: row.querySelector('.item-hsn').value || '',
                    price: Number(row.querySelector('.item-price').value || 0),
                    qty: Number.parseInt(row.querySelector('.item-qty').value || '0', 10),
                    amount: Number(row.querySelector('.item-amount').value || 0),
                }));

                const validItems = items.filter((item) => item.product_name !== '' && item.qty >= 1 && item.price >= 0);

                if (!validItems.length) {
                    e.preventDefault();
                    alert('Please add at least one item with product name, price, and quantity.');
                    return;
                }

                itemsJsonEl.value = JSON.stringify(validItems);
            });

            const oldItemsJson = itemsJsonEl.value;
            let restoredItems = [];

            if (oldItemsJson) {
                try {
                    restoredItems = JSON.parse(oldItemsJson);
                } catch {
                    restoredItems = [];
                }
            }

            if (Array.isArray(restoredItems) && restoredItems.length) {
                restoredItems.forEach((item) => addRow(item));
            } else {
                addRow();
            }
        })();
    </script>
@endsection
