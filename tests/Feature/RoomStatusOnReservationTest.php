<?php

namespace Tests\Feature;

use App\Events\RefreshDashboardEvent;
use App\Models\Customer;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RoomStatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomStatusOnReservationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoomStatusSeeder::class);
        $this->seed(TypeSeeder::class);
    }

    private function makeSuperUser(): User
    {
        return User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'role' => 'Super',
            'is_active' => true,
        ]);
    }

    private function makeCustomer(): Customer
    {
        $user = User::factory()->create([
            'name' => 'Carlos',
            'email' => 'carlos@test.com',
            'role' => 'Customer',
            'is_active' => true,
        ]);

        return Customer::create([
            'name' => 'Carlos',
            'cedula' => '1000000000',
            'address' => null,
            'job' => null,
            'birthdate' => null,
            'gender' => 'Male',
            'user_id' => $user->id,
        ]);
    }

    private function makeRoom(): Room
    {
        $typeId = Type::query()->value('id');
        $vacantId = RoomStatus::where('code', 'V')->value('id');

        return Room::create([
            'type_id' => $typeId,
            'room_status_id' => $vacantId,
            'number' => '101A',
            'capacity' => 2,
            'price' => 100000,
            'view' => 'Vista',
        ]);
    }

    public function test_reservation_today_sets_room_occupied()
    {
        $admin = $this->makeSuperUser();
        $this->actingAs($admin);

        $customer = $this->makeCustomer();
        $room = $this->makeRoom();

        Event::fake([RefreshDashboardEvent::class]);

        $checkIn = Carbon::now()->format('Y-m-d');
        $checkOut = Carbon::now()->addDay()->format('Y-m-d');
        $dayDiff = \App\Helpers\Helper::getDateDifference($checkIn, $checkOut);
        $downPayment = ($room->price * $dayDiff) * 0.15 + 100;

        $this->post(route('transaction.reservation.payDownPayment', [$customer->id, $room->id]), [
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'downPayment' => $downPayment,
        ])->assertRedirect(route('transaction.index'));

        $occupiedId = RoomStatus::where('code', 'O')->value('id');
        $this->assertEquals($occupiedId, Room::find($room->id)->room_status_id);

        Event::assertDispatched(RefreshDashboardEvent::class);
    }

    public function test_future_reservation_keeps_room_vacant()
    {
        $admin = $this->makeSuperUser();
        $this->actingAs($admin);

        $customer = $this->makeCustomer();
        $room = $this->makeRoom();

        $checkIn = Carbon::now()->addDays(3)->format('Y-m-d');
        $checkOut = Carbon::now()->addDays(5)->format('Y-m-d');
        $dayDiff = \App\Helpers\Helper::getDateDifference($checkIn, $checkOut);
        $downPayment = ($room->price * $dayDiff) * 0.15 + 100;

        $this->post(route('transaction.reservation.payDownPayment', [$customer->id, $room->id]), [
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'downPayment' => $downPayment,
        ])->assertRedirect(route('transaction.index'));

        $vacantId = RoomStatus::where('code', 'V')->value('id');
        $this->assertEquals($vacantId, Room::find($room->id)->room_status_id);
    }
}