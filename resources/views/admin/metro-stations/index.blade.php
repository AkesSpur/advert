@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Metro Stations</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Metro Stations</h4>
                            <div class="card-header-action">
                                <a href="{{route('admin.metro-stations.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Create New</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped dataTable no-footer" id="table-2" role="grid">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Profiles Count</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($metroStations as $metroStation)
                                        <tr>
                                            <td>{{$metroStation->id}}</td>
                                            <td>{{$metroStation->name}}</td>
                                            <td>{{ $metroStation->profiles_count }}</td>
                                            <td>
                                                <a href="{{route('admin.metro-stations.edit', $metroStation->id)}}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                <a href="{{route('admin.metro-stations.destroy', $metroStation->id)}}" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
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