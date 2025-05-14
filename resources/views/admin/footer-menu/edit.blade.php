@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Меню нижнего колонтитула</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Меню редактирования</h4>

                  </div>
                  <div class="card-body">
                    <form action="{{route('admin.footer-menu.update', $menu->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Имя</label>
                            <input type="text" class="form-control" name="name" value="{{$menu->name}}">
                        </div>

              
                        <button type="submmit" class="btn btn-primary">Обновить</button>

                      </form>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </section>

@endsection
