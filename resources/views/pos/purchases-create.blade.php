@extends('layouts.pos-app')

@section('title', 'Create Purchase — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-5xl">
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

            <form method="POST" action="{{ route('pos.purchases.store') }}" class="mt-6 grid gap-5 md:grid-cols-2">
                @csrf

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
                    <p id="hsn_description_help" class="mt-1 text-xs text-rose-600"></p>
                    @error('hsn_sac')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="pos-label">Price *</label>
                    <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" placeholder="0.00" required class="pos-input @error('price') pos-input-error @enderror">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="qty" class="pos-label">Qty *</label>
                    <input id="qty" name="qty" type="number" min="1" value="{{ old('qty') }}" placeholder="1" required class="pos-input @error('qty') pos-input-error @enderror">
                    @error('qty')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="total_preview" class="pos-label">Total Amount</label>
                    <input id="total_preview" type="text" readonly class="pos-input bg-slate-50" value="Rs 0.00">
                </div>

                <div class="md:col-span-2 flex items-center justify-end gap-3">
                    <a href="{{ route('pos.purchases.index') }}" class="pos-btn-ghost">List Purchases</a>
                    <a href="{{ route('pos.purchases') }}" class="pos-btn-ghost">Cancel</a>
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Save Purchase</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        (() => {
            const priceInput = document.getElementById('price');
            const qtyInput = document.getElementById('qty');
            const totalPreviewInput = document.getElementById('total_preview');
            const hsnInput = document.getElementById('hsn_sac');
            const hsnHelp = document.getElementById('hsn_description_help');
            const hsnDescriptions = @json($hsnDescriptions ?? []);

            if (!priceInput || !qtyInput || !totalPreviewInput) return;

            const updateTotal = () => {
                const price = Number.parseFloat(priceInput.value || '0');
                const qty = Number.parseInt(qtyInput.value || '0', 10);
                const total = (Number.isFinite(price) ? price : 0) * (Number.isFinite(qty) ? qty : 0);

                totalPreviewInput.value = `Rs ${total.toFixed(2)}`;
            };

            priceInput.addEventListener('input', updateTotal);
            qtyInput.addEventListener('input', updateTotal);
            updateTotal();

            const updateHsnDescription = () => {
                if (!hsnInput || !hsnHelp) return;
                const key = hsnInput.value || '';
                const description = hsnDescriptions[key] || '';
                hsnHelp.textContent = description ? `Description: ${description}` : '';
            };

            if (hsnInput) {
                hsnInput.addEventListener('change', updateHsnDescription);
                updateHsnDescription();
            }
        })();
    </script>
@endsection
