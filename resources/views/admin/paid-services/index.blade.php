@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Paid Services</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Paid Services</h4>
                            <div class="card-header-action">
                                <a href="{{route('admin.paid-services.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Create New</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped dataTable no-footer" id="table-2" role="grid">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paidServices as $paidService)
                                        <tr>
                                            <td>{{$paidService->id}}</td>
                                            <td>{{$paidService->name}}</td>
                                            <td>
                                                <a href="{{route('admin.paid-services.edit', $paidService->id)}}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                <a href="{{route('admin.paid-services.destroy', $paidService->id)}}" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
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