<?php

namespace App\Http\Controllers;

use App\Models\DamageEntry;
use App\Models\GstRate;
use App\Models\OpeningStockEntry;
use App\Models\Vendor;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockController extends Controller
{
    public function __construct(private readonly InventoryService $inventory)
    {
    }

    public function dashboard(): View
    {
        return view('pos.stock');
    }

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $summary = $this->inventory->stockSummary();
        $movements = $this->inventory->movementLedger($search);

        if ($search) {
            $needle = mb_strtolower($search);
            $summary = $summary->filter(function (array $row) use ($needle) {
                return str_contains(mb_strtolower($row['product_name']), $needle)
                    || str_contains(mb_strtolower((string) $row['hsn_sac']), $needle);
            })->values();
        }

        return view('pos.stock-index', compact('summary', 'movements', 'search'));
    }

    public function createOpeningStock(): View
    {
        return view('pos.opening-stock-create', $this->formOptions());
    }

    public function storeOpeningStock(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'reference_no' => ['required', 'string', 'max:255'],
            'entry_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.hsn_sac' => ['nullable', 'string', 'max:50'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $items = collect($data['items'])
            ->map(function (array $item): array {
                return [
                    'product_name' => trim($item['product_name']),
                    'hsn_sac' => trim((string) ($item['hsn_sac'] ?? '')) ?: null,
                    'price' => (float) $item['price'],
                    'qty' => (int) $item['qty'],
                    'total_amount' => (float) $item['price'] * (int) $item['qty'],
                ];
            })
            ->filter(fn (array $item) => $item['product_name'] !== '')
            ->values();

        if ($items->isEmpty()) {
            return back()->withErrors(['items' => 'Please add at least one valid item.'])->withInput();
        }

        $vendorName = null;
        if (!empty($data['vendor_id'])) {
            $vendorName = Vendor::query()->whereKey($data['vendor_id'])->value('name');
        }

        DB::transaction(function () use ($data, $items, $vendorName): void {
            $entry = OpeningStockEntry::create([
                'vendor_id' => $data['vendor_id'] ?? null,
                'vendor_name' => $vendorName,
                'reference_no' => $data['reference_no'],
                'entry_date' => $data['entry_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $entry->items()->createMany($items->all());
        });

        return redirect()->route('pos.stock.index')->with('success', 'Opening stock saved successfully.');
    }

    public function createDamageEntry(): View
    {
        return view('pos.damage-entry-create', $this->formOptions());
    }

    public function storeDamageEntry(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'entry_date' => ['required', 'date'],
            'product_name' => ['required', 'string', 'max:255'],
            'hsn_sac' => ['nullable', 'string', 'max:50'],
            'qty' => ['required', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DamageEntry::create([
            'entry_date' => $data['entry_date'],
            'product_name' => trim($data['product_name']),
            'hsn_sac' => trim((string) ($data['hsn_sac'] ?? '')) ?: null,
            'qty' => (int) $data['qty'],
            'price' => isset($data['price']) && $data['price'] !== '' ? (float) $data['price'] : null,
            'reason' => $data['reason'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('pos.stock.index')->with('success', 'Damage entry saved successfully.');
    }

    private function formOptions(): array
    {
        $vendors = Vendor::orderBy('name')->get();
        $gstRates = GstRate::query()
            ->orderBy('hsn_sac')
            ->get(['hsn_sac', 'description']);
        $hsnSacOptions = $gstRates->pluck('hsn_sac');
        $hsnDescriptions = $gstRates->mapWithKeys(fn ($rate) => [$rate->hsn_sac => (string) ($rate->description ?? '')]);

        return compact('vendors', 'hsnSacOptions', 'hsnDescriptions');
    }
}
