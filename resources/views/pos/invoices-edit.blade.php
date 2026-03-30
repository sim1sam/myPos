@extends('layouts.pos-app')

@section('title', 'Edit Invoice — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-screen-2xl">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-semibold text-slate-800">Edit Invoice {{ $invoice->invoice_no }}</h1>
            <a href="{{ route('pos.invoices.index') }}" class="pos-btn-ghost">Back</a>
        </div>

        <div class="pos-dashboard-card">
            <form method="POST" action="{{ route('pos.invoices.update', $invoice) }}" class="mt-6 space-y-5" id="invoice-edit-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="items_json" id="items_json">
                <input type="hidden" name="subtotal" id="subtotal_input" value="{{ number_format((float) $invoice->subtotal, 2, '.', '') }}">
                <input type="hidden" name="sgst" id="sgst_input" value="{{ number_format((float) $invoice->sgst, 2, '.', '') }}">
                <input type="hidden" name="cgst" id="cgst_input" value="{{ number_format((float) $invoice->cgst, 2, '.', '') }}">
                <input type="hidden" name="igst" id="igst_input" value="{{ number_format((float) $invoice->igst, 2, '.', '') }}">
                <input type="hidden" name="total_amount" id="total_amount_input" value="{{ number_format((float) $invoice->total_amount, 2, '.', '') }}">
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="pos-label" for="prefix">Prefix</label>
                        <select id="prefix" name="prefix" class="pos-input">
                            <option value="" @selected(!$invoice->prefix)>(Blank)</option>
                            <option value="Mr" @selected($invoice->prefix === 'Mr')>Mr</option>
                            <option value="Mrs" @selected($invoice->prefix === 'Mrs')>Mrs</option>
                        </select>
                    </div>
                    <div>
                        <label class="pos-label" for="customer_id">Customer</label>
                        <select id="customer_id" name="customer_id" class="pos-input">
                            <option value="">-- Select Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" @selected((int) $invoice->customer_id === $customer->id)>
                                    {{ $customer->customer_code }} - {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="pos-label" for="gst_type">GST Type</label>
                        <select id="gst_type" name="gst_type" class="pos-input">
                            <option value="same" @selected($invoice->gst_type === 'same')>Same State</option>
                            <option value="other" @selected($invoice->gst_type === 'other')>Other State</option>
                            <option value="none" @selected($invoice->gst_type === 'none')>No GST</option>
                        </select>
                    </div>
                    <div>
                        <label class="pos-label" for="invoice_date">Invoice Date</label>
                        <input id="invoice_date" name="invoice_date" type="date" class="pos-input" value="{{ optional($invoice->invoice_date)->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="pos-label" for="due_date">Due Date</label>
                        <input id="due_date" name="due_date" type="date" class="pos-input" value="{{ optional($invoice->due_date)->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="pos-label" for="status">Status</label>
                        <select id="status" name="status" class="pos-input">
                            <option value="unpaid" @selected($invoice->status === 'unpaid')>Unpaid</option>
                            <option value="paid" @selected($invoice->status === 'paid')>Paid</option>
                            <option value="cancelled" @selected($invoice->status === 'cancelled')>Cancelled</option>
                        </select>
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
                    <a href="{{ route('pos.invoices.show', $invoice) }}" class="pos-btn-ghost">View Invoice</a>
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Update Invoice</button>
                </div>
            </form>
        </div>
    </section>

    @php
        $existingItems = $invoice->items->map(function ($item) {
            return [
                'description' => $item->description,
                'hsn_sac' => $item->hsn_sac,
                'rate' => (float) $item->rate,
                'qty' => (int) $item->qty,
                'unit' => $item->unit,
                'discount' => (float) $item->discount,
                'amount' => (float) $item->amount,
            ];
        })->values();
    @endphp
    <script>
        (() => {
            const products = @json($products);
            const existingItems = @json($existingItems);
            const hsnOptions = [...new Set(products.map((p) => p.hsn_sac))].sort();
            const tbody = document.getElementById('invoice-items-body');
            const addRowBtn = document.getElementById('add-row-btn');
            const gstTypeEl = document.getElementById('gst_type');

            const subtotalEl = document.getElementById('subtotal');
            const sgstEl = document.getElementById('sgst');
            const cgstEl = document.getElementById('cgst');
            const igstEl = document.getElementById('igst');
            const totalAmountEl = document.getElementById('total-amount');
            const formEl = document.getElementById('invoice-edit-form');
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
                } else if (gstTypeEl.value === 'other') {
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
                    </td>
                    <td class="px-3 py-2 min-w-56">
                        <select class="pos-input item-hsn">
                            <option value="">-- Select --</option>
                            ${hsnOptions.map((h) => `<option value="${h}">${h}</option>`).join('')}
                        </select>
                    </td>
                    <td class="px-3 py-2"><input type="number" min="1" step="0.01" class="pos-input item-rate" value="1"></td>
                    <td class="px-3 py-2"><input type="number" min="1" step="1" class="pos-input item-qty" value="1"></td>
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
                const discountEl = row.querySelector('.item-discount');
                const amountEl = row.querySelector('.item-amount');

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
                        ? filtered.map((p) => `<option value="${p.id}">${p.name}</option>`).join('')
                        : `<option value="" disabled>No products available for selected HSN/SAC</option>`;
                };

                hsnEl.addEventListener('change', () => {
                    populateDescriptions();
                    calcAmount();
                });
                rateEl.addEventListener('input', calcAmount);
                qtyEl.addEventListener('input', calcAmount);
                discountEl.addEventListener('input', calcAmount);
                row.querySelector('.remove-row').addEventListener('click', () => {
                    row.remove();
                    refreshSerials();
                    renderTotals();
                });

                row._helpers = { hsnEl, descEl, rateEl, qtyEl, discountEl, amountEl, calcAmount, populateDescriptions };
            };

            const refreshSerials = () => {
                [...tbody.querySelectorAll('tr')].forEach((row, index) => {
                    row.children[0].textContent = String(index + 1);
                });
            };

            const addRow = (item = null) => {
                tbody.insertAdjacentHTML('beforeend', rowTemplate(tbody.querySelectorAll('tr').length + 1));
                const row = tbody.lastElementChild;
                bindRowEvents(row);

                const { hsnEl, descEl, rateEl, qtyEl, discountEl, amountEl, populateDescriptions } = row._helpers;
                if (item) {
                    hsnEl.value = item.hsn_sac || '';
                    populateDescriptions();
                    const match = [...descEl.options].find((opt) => opt.textContent.trim() === (item.description || '').trim());
                    if (match) {
                        match.selected = true;
                    } else if (item.description) {
                        descEl.innerHTML += `<option value="" selected>${item.description}</option>`;
                    }
                    rateEl.value = item.rate ?? 1;
                    qtyEl.value = item.qty ?? 1;
                    row.querySelector('.item-unit').value = item.unit || '';
                    discountEl.value = item.discount ?? 0;
                    amountEl.value = formatMoney(item.amount ?? 0);
                } else {
                    populateDescriptions();
                }

                renderTotals();
                return row;
            };

            gstTypeEl.addEventListener('change', renderTotals);
            addRowBtn.addEventListener('click', () => addRow());
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
                }).filter((item) => item.description && item.qty > 0 && item.amount >= 1);

                if (!items.length) {
                    e.preventDefault();
                    alert('Please add at least one item with minimum Amount 1.');
                    return;
                }
                itemsJsonEl.value = JSON.stringify(items);
            });

            if (existingItems.length) {
                existingItems.forEach((item) => addRow(item));
            } else {
                addRow();
            }
            renderTotals();
        })();
    </script>
@endsection
