@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Profile</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{route('admin.dashboard')}}">Приборная панель</a></div>
        <div class="breadcrumb-item">Профиль</div>
      </div>
    </div>
    <div class="section-body">

      <div class="row mt-sm-4">

        <div class="col-12 col-md-12 col-lg-7">
          <div class="card">
            <form method="post" class="needs-validation" novalidate="" action="{{route('admin.profile.update')}}">
                @csrf
              <div class="card-header">
                <h4>Обновить профиль</h4>
              </div>
              <div class="card-body">
                  <div class="row">

                    <div class="form-group col-md-6 col-12">
                      <label>Имя</label>
                      <input type="text" name="name" class="form-control" required value="{{Auth::user()->name}}">

                    </div>
                    <div class="form-group col-md-6 col-12">
                      <label>Электронная почта</label>
                      <input type="text" name="email" class="form-control" required value="{{Auth::user()->email}}" >

                    </div>
                  </div>


              </div>
              <div class="card-footer text-right">
                <button class="btn btn-primary">Сохранить изменения</button>
              </div>
            </form>
          </div>
        </div>


        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">

              <form method="post" class="needs-validation" novalidate="" action="{{route('admin.password.update')}}" enctype="multipart/form-data">
                  @csrf
                <div class="card-header">
                  <h4>Обновить пароль</h4>
                </div>
                <div class="card-body">
                    <div class="row">

                      <div class="form-group col-12">
                        <label>Текущий пароль</label>
                        <input type="password" required min="8" name="current_password" class="form-control" >
                      </div>
                      <div class="form-group col-12">
                        <label>Новый пароль</label>
                        <input type="password" required min="8" name="password" class="form-control" >
                      </div>
                      <div class="form-group col-12">
                        <label>Подтвердите пароль</label>
                        <input type="password" required name="password_confirmation" class="form-control" >
                      </div>

                    </div>


                </div>
                <div class="card-footer text-right">
                  <button class="btn btn-primary">Сохранить изменения</button>
                </div>
              </form>
            </div>
          </div>

      </div>
    </div>
  </section>
@endsection
