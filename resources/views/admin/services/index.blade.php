@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Услуги</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Все услуги</h4>
                            <div class="card-header-action">
                                <a href="{{route('admin.services.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Создать новый</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Имя</th>
                                            <th>Title</th>
                                            <th>Meta Description</th>
                                            <th>H1 Заголовок</th>
                                            <th>Статус</th>
                                            <th>Подсчет профилей</th>
                                            <th>Действие</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($services as $service)
                                        <tr>
                                            <td>{{$service->id}}</td>
                                            <td>{{$service->name}}</td>
                                            <td>{{ $service->title }}</td>
                                            <td>{{ Str::limit($service->meta_description, 50) }}</td>
                                            <td>{{ $service->h1_header }}</td>
                                            <td>
                                                @if ($service->status)
                                                    <span class="badge badge-success">Активен</span>
                                                @else
                                                    <span class="badge badge-danger">Неактивен</span>
                                                @endif
                                            </td>
                                            <td>{{$service->profiles_count}}</td>
                                            <td>
                                                <a href="{{route('admin.services.edit', $service->id)}}" class="mb-1 btn btn-primary"><i class="fas fa-edit"></i></a>
                                                <a href="{{route('admin.services.destroy', $service->id)}}" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="text-muted">
                                    Showing {{ $services->firstItem() }}–{{ $services->lastItem() }} of {{ $services->total() }}
                                </div>
                                <div>
                                    {{ $services->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection