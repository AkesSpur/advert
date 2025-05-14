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
                            <h4>Создать район</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.neighborhoods.store')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Имя</label>
                                    <input type="text" class="form-control" name="name" value="">
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Создать</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection