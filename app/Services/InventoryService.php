<?php

namespace App\Services;

use App\Models\DamageEntry;
use App\Models\InvoiceItem;
use App\Models\OpeningStockItem;
use App\Models\Purchase;
use Illuminate\Support\Collection;

class InventoryService
{
    public function productKey(string $name, ?string $hsnSac): string
    {
        return mb_strtolower(trim($name)) . '|' . mb_strtolower(trim($hsnSac ?? ''));
    }

    public function stockSummary(): Collection
    {
        $products = [];

        OpeningStockItem::query()
            ->get(['product_name', 'hsn_sac', 'qty', 'price'])
            ->each(function (OpeningStockItem $item) use (&$products): void {
                $this->addQty($products, $item->product_name, $item->hsn_sac, 'opening_qty', (int) $item->qty, (float) $item->price);
            });

        Purchase::query()
            ->get(['product_name', 'hsn_sac', 'qty', 'price'])
            ->each(function (Purchase $item) use (&$products): void {
                $this->addQty($products, $item->product_name, $item->hsn_sac, 'purchase_qty', (int) $item->qty, (float) $item->price);
            });

        InvoiceItem::query()
            ->get(['description', 'hsn_sac', 'qty', 'rate'])
            ->each(function (InvoiceItem $item) use (&$products): void {
                $this->addQty($products, $item->description, $item->hsn_sac, 'sold_qty', (int) $item->qty, (float) $item->rate);
            });

        DamageEntry::query()
            ->get(['product_name', 'hsn_sac', 'qty', 'price'])
            ->each(function (DamageEntry $item) use (&$products): void {
                $this->addQty($products, $item->product_name, $item->hsn_sac, 'damage_qty', (int) $item->qty, $item->price !== null ? (float) $item->price : null);
            });

        return collect($products)
            ->map(function (array $row) {
                $row['balance_qty'] = $row['opening_qty'] + $row['purchase_qty'] - $row['sold_qty'] - $row['damage_qty'];

                return $row;
            })
            ->sortBy('product_name')
            ->values();
    }

    private function addQty(array &$products, string $name, ?string $hsnSac, string $field, int $qty, ?float $price = null): void
    {
        $key = $this->productKey($name, $hsnSac);

        if (!isset($products[$key])) {
            $products[$key] = [
                'product_name' => $name,
                'hsn_sac' => $hsnSac ?: '-',
                'opening_qty' => 0,
                'purchase_qty' => 0,
                'sold_qty' => 0,
                'damage_qty' => 0,
                'balance_qty' => 0,
                'last_price' => $price,
            ];
        }

        $products[$key][$field] += $qty;

        if ($price !== null) {
            $products[$key]['last_price'] = $price;
        }
    }

    public function movementLedger(?string $search = null): Collection
    {
        $rows = collect();

        OpeningStockItem::query()
            ->with('entry')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('product_name', 'like', '%' . $search . '%')
                        ->orWhere('hsn_sac', 'like', '%' . $search . '%')
                        ->orWhereHas('entry', function ($entryQuery) use ($search) {
                            $entryQuery
                                ->where('reference_no', 'like', '%' . $search . '%')
                                ->orWhere('vendor_name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->get()
            ->each(function (OpeningStockItem $item) use ($rows): void {
                $rows->push([
                    'date' => optional($item->entry?->entry_date)->format('Y-m-d'),
                    'type' => 'Opening Stock',
                    'reference' => $item->entry?->reference_no,
                    'vendor' => $item->entry?->vendor_name ?: '-',
                    'product_name' => $item->product_name,
                    'hsn_sac' => $item->hsn_sac ?: '-',
                    'price' => (float) $item->price,
                    'qty_in' => (int) $item->qty,
                    'qty_out' => 0,
                    'sort_at' => optional($item->entry?->entry_date)?->format('Y-m-d') . ' ' . str_pad((string) $item->id, 8, '0', STR_PAD_LEFT),
                ]);
            });

        Purchase::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('product_name', 'like', '%' . $search . '%')
                        ->orWhere('hsn_sac', 'like', '%' . $search . '%')
                        ->orWhere('invoice_no', 'like', '%' . $search . '%')
                        ->orWhere('vendor_name', 'like', '%' . $search . '%');
                });
            })
            ->get()
            ->each(function (Purchase $purchase) use ($rows): void {
                $rows->push([
                    'date' => optional($purchase->invoice_date)->format('Y-m-d'),
                    'type' => 'Purchase',
                    'reference' => $purchase->invoice_no,
                    'vendor' => $purchase->vendor_name ?: '-',
                    'product_name' => $purchase->product_name,
                    'hsn_sac' => $purchase->hsn_sac ?: '-',
                    'price' => (float) $purchase->price,
                    'qty_in' => (int) $purchase->qty,
                    'qty_out' => 0,
                    'sort_at' => optional($purchase->invoice_date)?->format('Y-m-d') . ' ' . str_pad((string) $purchase->id, 8, '0', STR_PAD_LEFT),
                ]);
            });

        InvoiceItem::query()
            ->with('invoice')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('description', 'like', '%' . $search . '%')
                        ->orWhere('hsn_sac', 'like', '%' . $search . '%')
                        ->orWhereHas('invoice', function ($invoiceQuery) use ($search) {
                            $invoiceQuery->where('invoice_no', 'like', '%' . $search . '%');
                        });
                });
            })
            ->get()
            ->each(function (InvoiceItem $item) use ($rows): void {
                $rows->push([
                    'date' => optional($item->invoice?->invoice_date)->format('Y-m-d'),
                    'type' => 'Sale',
                    'reference' => $item->invoice?->invoice_no,
                    'vendor' => '-',
                    'product_name' => $item->description,
                    'hsn_sac' => $item->hsn_sac ?: '-',
                    'price' => (float) $item->rate,
                    'qty_in' => 0,
                    'qty_out' => (int) $item->qty,
                    'sort_at' => optional($item->invoice?->invoice_date)?->format('Y-m-d') . ' ' . str_pad((string) $item->id, 8, '0', STR_PAD_LEFT),
                ]);
            });

        DamageEntry::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('product_name', 'like', '%' . $search . '%')
                        ->orWhere('hsn_sac', 'like', '%' . $search . '%')
                        ->orWhere('reason', 'like', '%' . $search . '%');
                });
            })
            ->get()
            ->each(function (DamageEntry $entry) use ($rows): void {
                $rows->push([
                    'date' => optional($entry->entry_date)->format('Y-m-d'),
                    'type' => 'Damage',
                    'reference' => 'D' . str_pad((string) $entry->id, 4, '0', STR_PAD_LEFT),
                    'vendor' => $entry->reason ?: '-',
                    'product_name' => $entry->product_name,
                    'hsn_sac' => $entry->hsn_sac ?: '-',
                    'price' => (float) ($entry->price ?? 0),
                    'qty_in' => 0,
                    'qty_out' => (int) $entry->qty,
                    'sort_at' => optional($entry->entry_date)?->format('Y-m-d') . ' ' . str_pad((string) $entry->id, 8, '0', STR_PAD_LEFT),
                ]);
            });

        return $rows->sortByDesc('sort_at')->values();
    }
}
