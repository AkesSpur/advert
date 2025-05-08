@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Comments</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Comments</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped dataTable no-footer" id="table-2" role="grid">
                                    <thead>
                                        <tr>
                                            {{-- <th>Id</th> --}}
                                            <th>Profile_id</th>
                                            <th>Name</th>
                                            <th>Comment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($comments as $comment)
                                        <tr>
                                            {{-- <td>{{$comment->id}}</td> --}}
                                            <td>{{$comment->profile_id}}</td>
                                            <td>{{$comment->name}}</td>
                                            <td>{{$comment->content}}</td>
                                            <td>
                                                <a href="{{route('admin.comments.destroy', $comment->id)}}" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
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