<?php

namespace App\Services\Payment;

use App\Enum\Payments\PlanStatusEnum;
use App\Enum\Payments\SlotStatusEnum;
use App\Models\Plan;
use App\Models\PlanPackage;
use App\Models\Slot;

class SlotService
{
    protected $slotModel;

    protected $stripeService;

    public function __construct(Slot $slot, StripeService $stripeService)
    {
        $this->slotModel = $slot;
        $this->stripeService = $stripeService;
    }

    public function find_all($keyword)
    {
        return $this->slotModel::where("slug","LIKE","%$keyword%")
            ->orWhere("title","LIKE","%$keyword%")
            ->orWhere("price","LIKE","%$keyword%")
            ->orWhere("good_type","LIKE","%$keyword%")
            ->orderBy('id', 'asc')->paginate(20);
    }

    public function find_by_id($id)
    {
        return $this->slotModel::with(['packages', 'packages.plan'])->findOrFail($id);
    }

    public function store(array $slotData): ?Slot
    {
        $product = $this->stripeService->create_product_charge($slotData);
        if ($product->id) {
            $slot = $this->slotModel::create([
                'title'               => $slotData['title'],
                'slug'                => $slotData['slug'],
                'description'         => $slotData['description'],
                'good_type'           => $slotData['good_type'],
                'good_number'         => $slotData['good_number'],
                'price'               => $slotData['price'],
                'stripe_plan'         => $product->default_price,
                'stripe_product'      => $product->id,
            ]);

            if ($slot->id) {
                $slot->packages()->sync($slotData['packages']);
                return $slot;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function update(array $slotData, Slot $slot): bool
    {
        try {
            $slot->update([
                'title'               => $slotData['title'],
                'slug'                => $slotData['slug'],
                'description'         => $slotData['description'],
                'good_type'           => $slotData['good_type'],
                'good_number'         => $slotData['good_number'],
            ]);

            $slot->packages()->sync($slotData['packages']);

            if ($slot->price != intval($slotData['price'])) {
                $slotData['stripe_product'] = $slot->stripe_product;
                $price = $this->stripeService->create_price_charge($slotData);
                if ($price->id) {
                    $slot->update([
                        'price'               => $slotData['price'],
                        'stripe_plan'         => $price->id,
                    ]);
                } else {
                    return false;
                }
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }

    public function update_status(Slot $slot): Slot
    {
        $slot->update([
            'status' =>
                $slot->status == SlotStatusEnum::ACTIVE ?
                    SlotStatusEnum::INACTIVE : SlotStatusEnum::ACTIVE
        ]);
        return $slot;
    }

    /**
     * @throws \Throwable
     */
    public function delete(Slot $slot): bool
    {
        $product = $this->stripeService->archive_product($slot->stripe_product);
        if ($product->id && !$product->active) {
            return $slot->deleteOrFail();
        }
        return false;
    }
}
