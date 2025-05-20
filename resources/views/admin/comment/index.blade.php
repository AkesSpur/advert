@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Комментарии</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Все комментарии</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped dataTable no-footer" role="grid">
                                    <thead>
                                        <tr>
                                            {{-- <th>Id</th> --}}
                                            <th>Профиль_id</th>
                                            <th>Имя</th>
                                            <th>Комментарий</th>
                                            <th>Статус</th>
                                            <th>Действие</th>
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
                                                @if($comment->approved)
                                                    <span class="badge badge-success">Одобрено</span>
                                                @else
                                                    <span class="badge badge-warning">Ожидает проверки</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$comment->approved)
                                                    <a href="{{route('admin.comments.approve', $comment->id)}}" class="btn btn-primary"><i class="fas fa-check"></i></a>
                                                @endif
                                                <a href="{{route('admin.comments.destroy', $comment->id)}}" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="text-muted">
                                    Показать {{ $comments->firstItem() }}–{{ $comments->lastItem() }} из {{ $comments->total() }}
                                </div>
                                <div>
                                    {{ $comments->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection