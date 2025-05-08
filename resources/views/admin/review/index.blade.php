@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Reviews</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Reviews</h4>
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
                                        @foreach($reviews as $review)
                                        <tr>
                                            {{-- <td>{{$review->id}}</td> --}}
                                            <td>{{$review->profile_id}}</td>
                                            <td>{{$review->name}}</td>
                                            <td>{{$review->comment}}</td>
                                            <td>
                                                <a href="{{route('admin.reviews.destroy', $review->id)}}" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
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