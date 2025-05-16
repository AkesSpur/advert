@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Управление фильтрами</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Цвета волос</h4>
                {{-- No create button as per user request --}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Название (RU)</th>
                                <th>Title</th>
                                <th>Meta Description</th>
                                <th>H1 Заголовок</th>
                                <th>Порядок сортировки</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hairColors as $hairColor)
                                <tr>
                                    <td>{{ $hairColor->id }}</td>
                                    <td>{{ $hairColor->name }}</td>
                                    <td>{{ $hairColor->title }}</td>
                                    <td>{{ Str::limit($hairColor->meta_description, 50) }}</td>
                                    <td>{{ $hairColor->h1_header }}</td>
                                    <td>{{ $hairColor->sort_order }}</td>
                                    <td>
                                        @if ($hairColor->status)
                                            <span class="badge badge-success">Активен</span>
                                        @else
                                            <span class="badge badge-danger">Неактивен</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.filters.hair-color.edit', $hairColor->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection