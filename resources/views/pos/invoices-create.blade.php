@extends('layouts.pos-app')

@section('title', 'Create Invoice — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-screen-2xl">
        <div class="pos-dashboard-card">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-2xl font-semibold text-slate-800">Create Invoice</h1>
                <a href="{{ route('pos.customers.create', ['redirect_to' => 'pos.invoices.create']) }}" class="pos-btn-primary w-auto! px-5 py-2.5">+ Add Customer</a>
            </div>

            <form method="POST" action="{{ route('pos.invoices.store') }}" class="mt-6 space-y-5" id="invoice-form">
                @csrf
                <input type="hidden" name="items_json" id="items_json">
                <input type="hidden" name="subtotal" id="subtotal_input" value="0">
                <input type="hidden" name="sgst" id="sgst_input" value="0">
                <input type="hidden" name="cgst" id="cgst_input" value="0">
                <input type="hidden" name="igst" id="igst_input" value="0">
                <input type="hidden" name="total_amount" id="total_amount_input" value="0">
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="pos-label" for="prefix">Prefix</label>
                        <select id="prefix" name="prefix" class="pos-input">
                            <option value="">(Blank)</option>
                            <option value="Mr">Mr</option>
                            <option value="Mrs">Mrs</option>
                        </select>
                    </div>
                    <div>
                        <label class="pos-label" for="customer_id">Customer</label>
                        <select id="customer_id" name="customer_id" class="pos-input">
                            <option value="">-- Select Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_code }} - {{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="pos-label" for="gst_type">GST Type</label>
                        <select id="gst_type" name="gst_type" class="pos-input">
                            <option value="same">Same State</option>
                            <option value="other">Other State</option>
                            <option value="none">No GST</option>
                        </select>
                    </div>
                    <div>
                        <label class="pos-label" for="invoice_date">Invoice Date</label>
                        <input id="invoice_date" name="invoice_date" type="date" class="pos-input" value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="pos-label" for="due_date">Due Date</label>
                        <input id="due_date" name="due_date" type="date" class="pos-input" value="{{ now()->addDays(7)->format('Y-m-d') }}">
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
                    <table class="min-w-full text-sm" id="invoice-items-table">
                        <thead class="bg-sky-50 text-left text-slate-700">
                            <tr>
                                <th class="px-3 py-2 font-semibold">SL#</th>
                                <th class="px-3 py-2 font-semibold min-w-80">Description</th>
                                <th class="px-3 py-2 font-semibold min-w-56">HSN/SAC</th>
                                <th class="px-3 py-2 font-semibold">Rate</th>
                                <th class="px-3 py-2 font-semibold">Qty</th>
                                <th class="px-3 py-2 font-semibold">Unit</th>
                                <th class="px-3 py-2 font-semibold">Discount</th>
                                <th class="px-3 py-2 font-semibold">Amount</th>
                                <th class="px-3 py-2 font-semibold"></th>
                            </tr>
                        </thead>
                        <tbody id="invoice-items-body" class="divide-y divide-slate-200"></tbody>
                    </table>
                </div>

                <div class="flex justify-between">
                    <button type="button" id="add-row-btn" class="pos-btn-ghost">+ Add Row</button>
                </div>

                <div class="grid gap-3 sm:ml-auto sm:max-w-sm">
                    <div class="flex items-center justify-between rounded-md bg-slate-50 px-3 py-2">
                        <span class="text-sm text-slate-600">Subtotal</span>
                        <span id="subtotal" class="font-semibold text-slate-800">0.00</span>
                    </div>
                    <div class="flex items-center justify-between rounded-md bg-slate-50 px-3 py-2">
                        <span class="text-sm text-slate-600">SGST</span>
                        <span id="sgst" class="font-semibold text-slate-800">0.00</span>
                    </div>
                    <div class="flex items-center justify-between rounded-md bg-slate-50 px-3 py-2">
                        <span class="text-sm text-slate-600">CGST</span>
                        <span id="cgst" class="font-semibold text-slate-800">0.00</span>
                    </div>
                    <div class="flex items-center justify-between rounded-md bg-slate-50 px-3 py-2">
                        <span class="text-sm text-slate-600">IGST</span>
                        <span id="igst" class="font-semibold text-slate-800">0.00</span>
                    </div>
                    <div class="flex items-center justify-between rounded-md bg-sky-100 px-3 py-2">
                        <span class="text-sm font-semibold text-sky-800">Total Amount</span>
                        <span id="total-amount" class="font-bold text-sky-900">0.00</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('pos.invoices.index') }}" class="pos-btn-ghost">Invoice List</a>
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Submit Invoice</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        (() => {
            const products = @json($products);
            const hsnOptions = [...new Set(products.map((p) => p.hsn_sac))].sort();
            const tbody = document.getElementById('invoice-items-body');
            const addRowBtn = document.getElementById('add-row-btn');
            const gstTypeEl = document.getElementById('gst_type');

            const subtotalEl = document.getElementById('subtotal');
            const sgstEl = document.getElementById('sgst');
            const cgstEl = document.getElementById('cgst');
            const igstEl = document.getElementById('igst');
            const totalAmountEl = document.getElementById('total-amount');
            const formEl = document.getElementById('invoice-form');
            const itemsJsonEl = document.getElementById('items_json');
            const subtotalInput = document.getElementById('subtotal_input');
            const sgstInput = document.getElementById('sgst_input');
            const cgstInput = document.getElementById('cgst_input');
            const igstInput = document.getElementById('igst_input');
            const totalAmountInput = document.getElementById('total_amount_input');

            const formatMoney = (n) => Number(n || 0).toFixed(2);

            const renderTotals = () => {
                let subtotal = 0;
                tbody.querySelectorAll('tr').forEach((row) => {
                    subtotal += Number(row.querySelector('.item-amount').value || 0);
                });

                let sgst = 0;
                let cgst = 0;
                let igst = 0;
                if (gstTypeEl.value === 'same') {
                    sgst = subtotal * 0.09;
                    cgst = subtotal * 0.09;
                } else {
                    igst = subtotal * 0.18;
                }
                const total = subtotal + sgst + cgst + igst;

                subtotalEl.textContent = formatMoney(subtotal);
                sgstEl.textContent = formatMoney(sgst);
                cgstEl.textContent = formatMoney(cgst);
                igstEl.textContent = formatMoney(igst);
                totalAmountEl.textContent = formatMoney(total);
                subtotalInput.value = formatMoney(subtotal);
                sgstInput.value = formatMoney(sgst);
                cgstInput.value = formatMoney(cgst);
                igstInput.value = formatMoney(igst);
                totalAmountInput.value = formatMoney(total);
            };

            const rowTemplate = (sl) => `
                <tr>
                    <td class="px-3 py-2 align-top text-slate-700">${sl}</td>
                    <td class="px-3 py-2 min-w-80">
                        <select class="pos-input item-description" multiple size="4"></select>
                        <p class="mt-1 text-xs text-slate-500">Use Ctrl/Cmd to select multiple products.</p>
                        <button type="button" class="mt-2 pos-btn-ghost split-products text-xs">Add Selected as Rows</button>
                    </td>
                    <td class="px-3 py-2 min-w-56">
                        <select class="pos-input item-hsn">
                            <option value="">-- Select --</option>
                            ${hsnOptions.map((h) => `<option value="${h}">${h}</option>`).join('')}
                        </select>
                    </td>
                    <td class="px-3 py-2"><input type="number" min="0" step="0.01" class="pos-input item-rate" value="0"></td>
                    <td class="px-3 py-2"><input type="number" min="0" step="1" class="pos-input item-qty" value="1"></td>
                    <td class="px-3 py-2"><input type="text" class="pos-input item-unit" value=""></td>
                    <td class="px-3 py-2"><input type="number" min="0" step="0.01" class="pos-input item-discount" value="0"></td>
                    <td class="px-3 py-2"><input type="number" readonly class="pos-input item-amount bg-slate-50" value="0"></td>
                    <td class="px-3 py-2"><button type="button" class="pos-btn-ghost remove-row">X</button></td>
                </tr>
            `;

            const bindRowEvents = (row) => {
                const hsnEl = row.querySelector('.item-hsn');
                const descEl = row.querySelector('.item-description');
                const rateEl = row.querySelector('.item-rate');
                const qtyEl = row.querySelector('.item-qty');
                const unitEl = row.querySelector('.item-unit');
                const discountEl = row.querySelector('.item-discount');
                const amountEl = row.querySelector('.item-amount');
                const splitBtn = row.querySelector('.split-products');

                const calcAmount = () => {
                    const rate = Number(rateEl.value || 0);
                    const qty = Number(qtyEl.value || 0);
                    const discount = Number(discountEl.value || 0);
                    const amt = Math.max((rate * qty) - discount, 0);
                    amountEl.value = formatMoney(amt);
                    renderTotals();
                };

                const populateDescriptions = () => {
                const filtered = products
                        .filter((p) => p.hsn_sac === hsnEl.value)
                        .sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

                    descEl.innerHTML = filtered.length
                        ? filtered.map((p) => `<option value="${p.id}" data-rate="${p.rate}" data-unit="${p.unit || ''}">${p.name}</option>`).join('')
                        : `<option value="" disabled>No products available for selected HSN/SAC</option>`;

                    // Keep flow simple: if products exist, preselect first one.
                    if (filtered.length > 0 && descEl.options.length > 0) {
                        descEl.options[0].selected = true;
                    }
                    calcAmount();
                };

                const onDescriptionChange = () => {
                    // Sales rate is manual; do not auto-fill from purchase data.
                    calcAmount();
                };

                const splitSelectedProducts = () => {
                    const selected = [...descEl.selectedOptions].filter((opt) => opt.value);
                    if (selected.length <= 1) return;

                    // Keep first selection in current row, create new rows for remaining selections.
                    [...descEl.options].forEach((opt) => { opt.selected = false; });
                    const first = selected[0];
                    const firstMatch = [...descEl.options].find((opt) => opt.value === first.value);
                    if (firstMatch) firstMatch.selected = true;

                    selected.slice(1).forEach((opt) => {
                        const newRow = addRow();
                        const helpers = newRow._helpers;
                        helpers.hsnEl.value = hsnEl.value;
                        helpers.populateDescriptions();
                        const match = [...helpers.descEl.options].find((o) => o.value === opt.value);
                        if (match) match.selected = true;
                        helpers.calcAmount();
                    });

                    calcAmount();
                };

                hsnEl.addEventListener('change', populateDescriptions);
                descEl.addEventListener('change', onDescriptionChange);
                rateEl.addEventListener('input', calcAmount);
                qtyEl.addEventListener('input', calcAmount);
                discountEl.addEventListener('input', calcAmount);
                splitBtn.addEventListener('click', splitSelectedProducts);
                row.querySelector('.remove-row').addEventListener('click', () => {
                    row.remove();
                    refreshSerials();
                    renderTotals();
                });

                row._helpers = {
                    hsnEl,
                    descEl,
                    calcAmount,
                    populateDescriptions,
                };
            };

            const refreshSerials = () => {
                [...tbody.querySelectorAll('tr')].forEach((row, index) => {
                    row.children[0].textContent = String(index + 1);
                });
            };

            const addRow = () => {
                tbody.insertAdjacentHTML('beforeend', rowTemplate(tbody.querySelectorAll('tr').length + 1));
                const row = tbody.lastElementChild;
                bindRowEvents(row);
                renderTotals();
                return row;
            };

            gstTypeEl.addEventListener('change', renderTotals);
            addRowBtn.addEventListener('click', addRow);
            formEl.addEventListener('submit', (e) => {
                const items = [...tbody.querySelectorAll('tr')].map((row) => {
                    const selected = row.querySelector('.item-description').selectedOptions[0];
                    return {
                        description: selected ? selected.textContent.trim() : '',
                        hsn_sac: row.querySelector('.item-hsn').value || '',
                        rate: Number(row.querySelector('.item-rate').value || 0),
                        qty: Number(row.querySelector('.item-qty').value || 0),
                        unit: row.querySelector('.item-unit').value || '',
                        discount: Number(row.querySelector('.item-discount').value || 0),
                        amount: Number(row.querySelector('.item-amount').value || 0),
                    };
                }).filter((item) => item.description && item.qty > 0);

                if (!items.length) {
                    e.preventDefault();
                    alert('Please select at least one product in Description and enter Qty.');
                    return;
                }
                itemsJsonEl.value = JSON.stringify(items);
            });
            addRow();
        })();
    </script>
@endsection
