@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Соседние районы</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Редактировать район</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.neighborhoods.update', $neighborhood->id)}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label>Имя</label>
                                    <input type="text" class="form-control" name="name" value="{{$neighborhood->name}}">
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Обновление</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection