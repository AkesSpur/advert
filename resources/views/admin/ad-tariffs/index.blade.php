@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Тарифы на рекламу</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Все тарифы</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Название</th>
                                        <th>Тип</th>
                                        <th>Статус</th>
                                        <th>Действие</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($adTariffs as $tariff)
                                            <tr>
                                                <td>{{ $tariff->name }}</td>
                                                <td>
                                                    {{ ucfirst($tariff->slug) }}
                                                </td>
                                                <td>
                                                    @if ($tariff->is_active)
                                                        <span class="badge badge-success">Активный</span>
                                                    @else
                                                        <span class="badge badge-danger">Неактивный</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.ad-tariffs.edit', $tariff->id) }}" class="btn btn-primary">Редактировать</a>
                                                </td>
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection