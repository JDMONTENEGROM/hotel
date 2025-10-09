@extends('template.master')
@section('title', 'Reservas')
@section('content')
    <div class="row mt-2 mb-2">
        <div class="col-lg-6 mb-2">
            <div class="d-grid gap-2 d-md-block">
                <span data-bs-toggle="tooltip" data-bs-placement="right" title="Agregar Reserva de Habitación">
                    <button type="button" class="btn btn-sm shadow-sm myBtn border rounded" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">
                        <i class="fas fa-plus"></i>
                    </button>
                </span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" title="Historial de Pagos">
                    <a href="{{route('payment.index')}}" class="btn btn-sm shadow-sm myBtn border rounded">
                        <i class="fas fa-history"></i>
                    </a>
                </span>
            </div>
        </div>
        <div class="col-lg-6 mb-2">
            <form class="d-flex" method="GET" action="{{ route('transaction.index') }}">
                <input class="form-control me-2" type="search" placeholder="Buscar por ID" aria-label="Search"
                    id="search-user" name="search" value="{{ request()->input('search') }}">
                <button class="btn btn-outline-dark" type="submit">Buscar</button>
            </form>
        </div>
    </div>
    <div class="row my-2 mt-4 ms-1">
        <div class="col-lg-12">
            <h5>Huéspedes Activos: </h5>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card p-0">
                <div class="card-body">
                    <div class="table-responsive" style="max-width: calc(100vw - 50px)">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Habitación</th>
                                    <th>Entrada</th>
                                    <th>Salida</th>
                                    <th>Días</th>
                                    <th>Precio Total</th>
                                    <th>Pagado</th>
                                    <th>Deuda</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <th>{{ ($transactions->currentpage() - 1) * $transactions->perpage() + $loop->index + 1 }}
                                        </th>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ $transaction->customer->name }}</td>
                                        <td>{{ $transaction->room->number }}</td>
                                        <td>{{ Helper::dateFormat($transaction->check_in) }}</td>
                                        <td>{{ Helper::dateFormat($transaction->check_out) }}</td>
                                        <td>{{ $transaction->getDateDifferenceWithPlural($transaction->check_in, $transaction->check_out) }}
                                        </td>
                                        <td>{{ Helper::convertToRupiah($transaction->getTotalPrice()) }}
                                        </td>
                                        <td>
                                            {{ Helper::convertToRupiah($transaction->getTotalPayment()) }}
                                        </td>
                                        <td>{{ $transaction->getTotalPrice() - $transaction->getTotalPayment() <= 0 ? '-' : Helper::convertToRupiah($transaction->getTotalPrice() - $transaction->getTotalPayment()) }}
                                        </td>
                                        <td>
                                            <a class="btn btn-light btn-sm rounded shadow-sm border p-1 m-0 {{$transaction->getTotalPrice() - $transaction->getTotalPayment() <= 0 ? 'disabled' : ''}}"
                                                href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Pagar">
                                                <i class="fas fa-money-bill-wave-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="15" class="text-center">
                                            No hay datos en esta tabla
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $transactions->onEachSide(2)->links('template.paginationlinks') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row my-2 mt-4 ms-1">
        <div class="col-lg-12">
            <h5>Expirados: </h5>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card p-0">
                <div class="card-body">
                    <div class="table-responsive" style="max-width: calc(100vw - 50px)">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Habitación</th>
                                    <th>Entrada</th>
                                    <th>Salida</th>
                                    <th>Días</th>
                                    <th>Precio Total</th>
                                    <th>Pagado</th>
                                    <th>Deuda</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactionsExpired as $transaction)
                                <tr>
                                    <th>{{ ($transactions->currentpage() - 1) * $transactions->perpage() + $loop->index + 1 }}
                                    </th>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->customer->name }}</td>
                                    <td>{{ $transaction->room->number }}</td>
                                    <td>{{ Helper::dateFormat($transaction->check_in) }}</td>
                                    <td>{{ Helper::dateFormat($transaction->check_out) }}</td>
                                    <td>{{ $transaction->getDateDifferenceWithPlural($transaction->check_in, $transaction->check_out) }}
                                    </td>
                                    <td>{{ Helper::convertToRupiah($transaction->getTotalPrice()) }}
                                    </td>
                                    <td>
                                        {{ Helper::convertToRupiah($transaction->getTotalPayment()) }}
                                    </td>
                                    <td>{{ $transaction->getTotalPrice() - $transaction->getTotalPayment() <= 0 ? '-' : Helper::convertToRupiah($transaction->getTotalPrice($transaction->room->price, $transaction->check_in, $transaction->check_out) - $transaction->getTotalPayment()) }}
                                    </td>
                                    <td>
                                        <a class="btn btn-light btn-sm rounded shadow-sm border p-1 m-0 {{$transaction->getTotalPrice($transaction->room->price, $transaction->check_in, $transaction->check_out) - $transaction->getTotalPayment() <= 0 ? 'disabled' : ''}}"
                                            href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Pagar">
                                            <i class="fas fa-money-bill-wave-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="15" class="text-center">
                                        No hay datos en esta tabla
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        {{ $transactions->onEachSide(2)->links('template.paginationlinks') }}
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">¿Tienes alguna cuenta?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <a class="btn btn-sm btn-primary m-1"
                            href="{{ route('transaction.reservation.createIdentity') }}">No, crear
                            nueva cuenta!</a>
                        <a class="btn btn-sm btn-success m-1"
                            href="{{ route('transaction.reservation.pickFromCustomer') }}">Sí, usar
                            su cuenta!</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
