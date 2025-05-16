@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Управление фильтрами: Возраст</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Панель управления</a></div>
              <div class="breadcrumb-item">Возраст</div>
            </div>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Все значения "Возраст"</h4>
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
                            @foreach ($ages as $age)
                                <tr>
                                    <td>{{ $age->id }}</td>
                                    <td>{{ $age->name }}</td>
                                    <td>{{ $age->title }}</td>
                                    <td>{{ Str::limit($age->meta_description, 50) }}</td>
                                    <td>{{ $age->h1_header }}</td>
                                    <td>{{ $age->sort_order }}</td>
                                    <td>
                                        @if ($age->status)
                                            <span class="badge badge-success">Активен</span>
                                        @else
                                            <span class="badge badge-danger">Неактивен</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.filters.age.edit', $age->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
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
