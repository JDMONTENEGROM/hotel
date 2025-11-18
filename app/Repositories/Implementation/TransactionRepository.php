<?php

namespace App\Repositories\Implementation;

use App\Models\Customer;
use App\Models\Room;
use App\Models\Transaction;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Carbon\Carbon;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function store($request, Customer $customer, Room $room)
    {
        return Transaction::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $customer->id,
            'room_id' => $room->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'status' => 'Reservation',
        ]);
    }

    public function getTransaction($request)
    {
        $term = trim($request->search ?? '');
        return Transaction::with('user', 'room', 'customer')
            ->where('check_out', '>=', Carbon::now())
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($q) use ($term) {
                    $q->where('transactions.id', '=', $term)
                      ->orWhereHas('customer', function ($qc) use ($term) {
                          $qc->where('cedula', 'like', $term.'%')
                             ->orWhere('name', 'like', '%'.$term.'%');
                      })
                      ->orWhereHas('room', function ($qr) use ($term) {
                          $qr->where('number', 'like', '%'.$term.'%');
                      });
                });
            })
            ->orderBy('check_out', 'ASC')->orderBy('id', 'DESC')->paginate(20)
            ->appends($request->all());
    }

    public function getTransactionExpired($request)
    {
        $term = trim($request->search ?? '');
        return Transaction::with('user', 'room', 'customer')->where('check_out', '<', Carbon::now())
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($q) use ($term) {
                    $q->where('transactions.id', '=', $term)
                      ->orWhereHas('customer', function ($qc) use ($term) {
                          $qc->where('cedula', 'like', $term.'%')
                             ->orWhere('name', 'like', '%'.$term.'%');
                      })
                      ->orWhereHas('room', function ($qr) use ($term) {
                          $qr->where('number', 'like', '%'.$term.'%');
                      });
                });
            })
            ->orderBy('check_out', 'ASC')->paginate(20)
            ->appends($request->all());
    }
}
