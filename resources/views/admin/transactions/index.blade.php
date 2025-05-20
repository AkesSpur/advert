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
                            <th>Сумма</th>
                            <th>Тип</th>
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
                            <td>{{ $transaction->amount }} {{ config('settings.site_currency_icon') }}</td>
                            <td>
                                @if($transaction->type == 'purchase')
                            @if(isset($transaction->description))
                                {{ $transaction->description }}
                            @else
                                Оплата рекламы
                            @endif
                        @else
                            Пополнение
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
                                {{ $transaction->status }}
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
                {{ $transactions->links() }}
            @endif
        </div>
    </div>
</section>
@endsection