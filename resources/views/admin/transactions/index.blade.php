@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Транзакции</h1>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4>Все транзакции</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Пользователь</th>
                            <th>Сумма (RUB)</th>
                            <th>Ориг. Сумма</th>
                            <th>Ориг. Валюта</th>
                            <th>Тип/Описание</th>
                            <th>Статус</th>
                            <th>ID платежа</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->user?->name }} ({{ $transaction->user?->email }})</td>
                            <td>{{ number_format($transaction->amount, 2, '.', ' ') }} {{-- Assuming RUB --}}</td>
                            <td>
                                @if($transaction->original_payment_amount)
                                    {{ number_format($transaction->original_payment_amount, 2, '.', ' ') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $transaction->original_payment_currency ?? 'N/A' }}</td>
                            <td>
                                {{-- Displaying type or description --}}
                                @if($transaction->type == 'purchase')
                                    @if(!empty($transaction->description))
                                        {{ Str::limit($transaction->description, 50) }} 
                                    @else
                                        Оплата рекламы
                                    @endif
                                @else
                                    Пополнение
                                    @if(!empty($transaction->description) && $transaction->description !== 'Пополнение')
                                     - {{ Str::limit($transaction->description, 50) }}
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($transaction->status === 'completed')
                                <span class="badge badge-success">Завершен</span>
                                @elseif ($transaction->status === 'pending')
                                <span class="badge badge-warning">В ожидании</span>
                                @elseif ($transaction->status === 'failed')
                                <span class="badge badge-danger">Неудачный</span>
                                @elseif ($transaction->status === 'cancelled')
                                <span class="badge badge-secondary">Отменен</span>
                                @else
                                <span class="badge badge-light">{{ ucfirst($transaction->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $transaction->payment_id }}</td>
                            <td>{{ $transaction->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Транзакции не найдены.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($transactions->hasPages())
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection