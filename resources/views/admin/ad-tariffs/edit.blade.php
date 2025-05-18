@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Редактировать тариф на рекламу</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Редактировать: {{ $adTariff->name }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.ad-tariffs.update', $adTariff->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>Название</label>
                                <input type="text" class="form-control" name="name" value="{{ $adTariff->name }}">
                            </div>

                            <div class="form-group">
                                <label>Описание</label>
                                <textarea class="form-control" name="description">{{ $adTariff->description }}</textarea>
                            </div>

                            @if ($adTariff->slug === 'basic' || $adTariff->slug === 'priority')
                                <div class="form-group">
                                    <label>Базовая цена</label>
                                    <input type="number" step="0.1" class="form-control" name="base_price" value="{{ $adTariff->base_price }}">
                                </div>
                            @endif

                            @if ($adTariff->slug === 'vip')
                                <div class="form-group">
                                    <label>Фиксированная цена</label>
                                    <input type="number" step="0.01" class="form-control" name="fixed_price" value="{{ $adTariff->fixed_price }}">
                                </div>
                                <div class="form-group">
                                    <label>Цена за неделю</label>
                                    <input type="number" step="0.01" class="form-control" name="weekly_price" value="{{ $adTariff->weekly_price }}">
                                </div>
                                <div class="form-group">
                                    <label>Цена за месяц</label>
                                    <input type="number" step="0.01" class="form-control" name="monthly_price" value="{{ $adTariff->monthly_price }}">
                                </div>
                            @endif

                            <div class="form-group">
                                <label>Статус</label>
                                <select class="form-control" name="is_active">
                                    <option value="1" {{ $adTariff->is_active ? 'selected' : '' }}>Активный</option>
                                    <option value="0" {{ !$adTariff->is_active ? 'selected' : '' }}>Неактивный</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Обновить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection