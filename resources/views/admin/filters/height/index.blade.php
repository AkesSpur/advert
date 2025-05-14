@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Управление фильтрами</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Рост</h4>
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
                            @foreach ($heights as $height)
                                <tr>
                                    <td>{{ $height->id }}</td>
                                    <td>{{ $height->name }}</td>
                                    <td>{{ $height->title }}</td>
                                    <td>{{ Str::limit($height->meta_description, 50) }}</td>
                                    <td>{{ $height->h1_header }}</td>
                                    <td>{{ $height->sort_order }}</td>
                                    <td>
                                        @if ($size->status)
                                            <span class="badge badge-success">Активен</span>
                                        @else
                                            <span class="badge badge-danger">Неактивен</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.filters.height.edit', $height->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
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