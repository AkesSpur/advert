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
                            @foreach ($ages as $ages)
                                <tr>
                                    <td>{{ $ages->id }}</td>
                                    <td>{{ $ages->name }}</td>
                                    <td>{{ $ages->title }}</td>
                                    <td>{{ Str::limit($ages->meta_description, 50) }}</td>
                                    <td>{{ $ages->h1_header }}</td>
                                    <td>{{ $ages->sort_order }}</td>
                                    <td>
                                        @if ($size->status)
                                            <span class="badge badge-success">Активен</span>
                                        @else
                                            <span class="badge badge-danger">Неактивен</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.filters.age.edit', $ages->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
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
