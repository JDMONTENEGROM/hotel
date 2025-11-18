<?php

namespace App\Repositories\Implementation;

use App\Models\Customer;
use App\Models\User;
use App\Repositories\Interface\CustomerRepositoryInterface;
use Illuminate\Support\Str;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function get($request)
    {
        $term = $request->q ?? $request->search ?? null;
        return Customer::with('user')->orderBy('id', 'DESC')
            ->when($term, function ($query) use ($term) {
                $query->where(function ($q) use ($term) {
                    $q->where('cedula', 'like', $term.'%')
                      ->orWhere('cedula', 'like', '%'.$term.'%')
                      ->orWhere('name', 'like', '%'.$term.'%')
                      ->orWhere('id', 'like', '%'.$term.'%');
                });
            })
            ->paginate(8)
            ->appends($request->all());
    }

    public function count($request)
    {
        $term = $request->q ?? $request->search ?? null;
        return Customer::with('user')->orderBy('id', 'DESC')
            ->when($term, function ($query) use ($term) {
                $query->where(function ($q) use ($term) {
                    $q->where('cedula', 'like', $term.'%')
                      ->orWhere('cedula', 'like', '%'.$term.'%')
                      ->orWhere('name', 'like', '%'.$term.'%')
                      ->orWhere('id', 'like', '%'.$term.'%');
                });
            })
            ->count();
    }

    public static function store($request)
    {
        $passwordSeed = $request->birthdate ?: Str::random(12);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($passwordSeed),
            'role' => 'Customer',
            'random_key' => Str::random(60),
        ]);

        // Eliminado soporte de avatar: se usa imagen por defecto cuando no hay avatar

        return Customer::create([
            'name' => $user->name,
            'cedula' => $request->cedula,
            'address' => $request->address,
            'job' => $request->job,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'user_id' => $user->id,
        ]);
    }
}
