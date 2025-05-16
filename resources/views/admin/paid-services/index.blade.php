@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Платные услуги</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Все платные услуги</h4>
                            <div class="card-header-action">
                                <a href="{{route('admin.paid-services.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Создать новый</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Имя</th>
                                            <th>Подсчет профилей</th>
                                            <th>Действие</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paidServices as $paidService)
                                        <tr>
                                            <td>{{$paidService->id}}</td>
                                            <td>{{$paidService->name}}</td>
                                            <td>{{$paidService->profiles_count}}</td>
                                            <td>
                                                <a href="{{route('admin.paid-services.edit', $paidService->id)}}" class="mb-1 btn btn-primary"><i class="fas fa-edit"></i></a>
                                                <a href="{{route('admin.paid-services.destroy', $paidService->id)}}" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="text-muted">
                                    Показать {{ $paidServices->firstItem() }}–{{ $paidServices->lastItem() }} из {{ $paidServices->total() }}
                                </div>
                                <div>
                                    {{ $paidServices->links() }}
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
