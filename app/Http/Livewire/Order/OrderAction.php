<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderHistory;

class OrderAction extends Component
{
    public $order;
    public $delivery_status;

    protected $statusNotes = [
        'pending' => 'Order placed and awaiting processing.',
        'processing' => 'Order is being prepared and processed.',
        'delivered' => 'Order has been successfully delivered to the customer.',
        'canceled' => 'Order has been canceled by admin or customer.',
        'completed' => 'Order is completed and closed successfully.',
        'fake' => 'Order marked as fake after verification.',
    ];


    public function mount($order_id)
    {
        $this->order = Order::withTrashed()->find($order_id);

        if ($this->order) {
            $this->order->update(['viewed' => 1]);
            $this->delivery_status = $this->order->delivery_status;
        } else {
            throw new \Exception('Order not found.');
        }

        if ($this->order) {
            $this->order->update(['viewed' => 1, 'is_seen' => 1]);
        } else {
            throw new \Exception('Order not found.');
        }
    }

    public function updatedDeliveryStatus($newStatus)
    {
        if (!$this->order) {
            return;
        }

        $oldStatus = $this->order->delivery_status;

        $restoreStatuses = ['canceled', 'fake'];

        // If moving INTO canceled/fake → restore stock
        if (in_array($newStatus, $restoreStatuses) && !in_array($oldStatus, $restoreStatuses)) {
            foreach ($this->order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('quantity', $item->quantity);
                }
            }
        }

        // If moving OUT of canceled/fake → reduce stock again
        if (in_array($oldStatus, $restoreStatuses) && !in_array($newStatus, $restoreStatuses)) {
            foreach ($this->order->orderItems as $item) {
                if ($item->product) {
                    // Prevent negative stock
                    if ($item->product->quantity >= $item->quantity) {
                        $item->product->decrement('quantity', $item->quantity);
                    }
                }
            }
        }

        // Update order
        $this->order->update(['delivery_status' => $newStatus]);

        // Add meaningful note
        $note = $this->statusNotes[$newStatus] ?? "Delivery status changed to {$newStatus}.";

        OrderHistory::create([
            'order_id' => $this->order->id,
            'status' => $newStatus,
            'note' => $note,
        ]);

        $this->emit('info', $note);
    }


    public function render()
    {
        return view('livewire.order.order-action');
    }
}
