<?php

namespace App\Http\Controllers;

use App\Events\RefreshDashboardEvent;
use App\Helpers\Helper;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Transaction;
use App\Repositories\Interface\CustomerRepositoryInterface;
use App\Repositories\Interface\PaymentRepositoryInterface;
use App\Repositories\Interface\ReservationRepositoryInterface;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository,
        private CustomerRepositoryInterface $customerRepository,
        private TransactionRepositoryInterface $transactionRepository,
        private PaymentRepositoryInterface $paymentRepository
    ) {}

    public function index()
    {
        return view('transaction.check-in.index');
    }

    public function search(Request $request)
    {
        $customer = Customer::where('id', $request->document)
            ->orWhere('name', 'like', '%' . $request->document . '%')
            ->first();

        if ($customer) {
            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function create()
    {
        return view('transaction.check-in.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'job' => 'required',
            'birthdate' => 'required|date',
            'gender' => 'required|in:Male,Female',
        ]);

        $customer = $this->customerRepository->store($request);

        return redirect()
            ->route('transaction.check-in.selectRoom', ['customer' => $customer->id])
            ->with('success', 'Cliente ' . $customer->name . ' registrado correctamente');
    }

    public function selectRoom(Customer $customer)
    {
        $stayFrom = Carbon::now()->format('Y-m-d');
        $stayUntil = Carbon::now()->addDay()->format('Y-m-d');

        $occupiedRoomId = $this->getOccupiedRoomID($stayFrom, $stayUntil);

        $rooms = Room::with('type', 'roomStatus')
            ->whereNotIn('id', $occupiedRoomId)
            ->where('room_status_id', 1) // Asumiendo que 1 es el ID para habitaciones disponibles/limpias
            ->orderBy('number')
            ->get();

        return view('transaction.check-in.select-room', [
            'customer' => $customer,
            'rooms' => $rooms,
            'stayFrom' => $stayFrom,
            'stayUntil' => $stayUntil,
        ]);
    }

    public function confirmation(Customer $customer, Room $room)
    {
        $stayFrom = Carbon::now()->format('Y-m-d');
        $stayUntil = Carbon::now()->addDay()->format('Y-m-d');
        
        $price = $room->price;
        $dayDifference = 1; // Por defecto 1 día para check-in inmediato

        return view('transaction.check-in.confirmation', [
            'customer' => $customer,
            'room' => $room,
            'stayFrom' => $stayFrom,
            'stayUntil' => $stayUntil,
            'dayDifference' => $dayDifference,
            'totalPrice' => $price * $dayDifference
        ]);
    }

    public function process(Customer $customer, Room $room, Request $request)
    {
        $request->validate([
            'payment' => 'required|numeric|min:0',
        ]);

        // Crear la transacción
        $transaction = Transaction::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $customer->id,
            'room_id' => $room->id,
            'check_in' => Carbon::now()->format('Y-m-d'),
            'check_out' => Carbon::now()->addDay()->format('Y-m-d'),
            'status' => 'Check In',
        ]);

        // Registrar el pago si existe
        if ($request->payment > 0) {
            $this->paymentRepository->store($request, $transaction, 'Payment');
        }

        // Actualizar el estado de la habitación a ocupada
        $room->update(['room_status_id' => 2]); // Asumiendo que 2 es el ID para habitaciones ocupadas

        event(new RefreshDashboardEvent('Nuevo check-in realizado'));

        return redirect()->route('transaction.index')
            ->with('success', 'Check-in completado para la habitación ' . $room->number . ' por ' . $customer->name);
    }

    private function getOccupiedRoomID($stayFrom, $stayUntil)
    {
        return Transaction::where([['check_in', '<=', $stayFrom], ['check_out', '>=', $stayUntil]])
            ->orWhere([['check_in', '>=', $stayFrom], ['check_in', '<=', $stayUntil]])
            ->orWhere([['check_out', '>=', $stayFrom], ['check_out', '<=', $stayUntil]])
            ->pluck('room_id');
    }
}