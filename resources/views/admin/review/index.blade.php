@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Отзывы</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Все отзывы</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped dataTable no-footer" role="grid">
                                    <thead>
                                        <tr>
                                            {{-- <th>Id</th> --}}
                                            <th>Профиль_id</th>
                                            <th>Имя</th>
                                            <th>Как</th>
                                            <th>Статус</th>
                                            <th>Действие</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reviews as $review)
                                        <tr>
                                            {{-- <td>{{$review->id}}</td> --}}
                                            <td> <a href="{{ route('profiles.view', [
                                                'slug'=>$review->profile->slug,
                                                'id'=>$review->profile->id,
                                                ]) }}"
                                                class="text-underlined"
                                                >{{$review->profile_id}}
                                                                                    </a></td>
                                            <td>{{$review->name}}</td>
                                            <td>{{$review->comment}}</td>
                                            <td>
                                                @if($review->approved)
                                                    <span class="badge badge-success">Одобрено</span>
                                                @else
                                                    <span class="badge badge-warning">Ожидает проверки</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$review->approved)
                                                    <a href="{{route('admin.reviews.approve', $review->id)}}" class="btn btn-primary"><i class="fas fa-check"></i></a>
                                                @endif
                                                <a href="{{route('admin.reviews.destroy', $review->id)}}" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="text-muted">
                                    Показать {{ $reviews->firstItem() }}–{{ $reviews->lastItem() }} из {{ $reviews->total() }}
                                </div>
                                <div>
                                    {{ $reviews->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
