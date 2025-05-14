@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Станции метро</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Все станции метро</h4>
                            <div class="card-header-action">
                                <a href="{{route('admin.metro-stations.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Создать новый</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped dataTable no-footer" >
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Имя</th>
                                            <th>Подсчет профилей</th>
                                            <th>Действие</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($metroStations as $metroStation)
                                        <tr>
                                            <td>{{$metroStation->id}}</td>
                                            <td>{{$metroStation->name}}</td>
                                            <td>{{$metroStation->profiles->count()}}</td>
                                            <td>
                                                <a href="{{route('admin.metro-stations.edit', $metroStation->id)}}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                <a href="{{route('admin.metro-stations.destroy', $metroStation->id)}}" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="text-muted">
                                    Showing {{ $metroStations->firstItem() }}–{{ $metroStations->lastItem() }} of {{ $metroStations->total() }}
                                </div>
                                <div>
                                    {{ $metroStations->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
