@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Пополнить кошелек пользователя</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">

                  </div>
                  <div class="card-body">
                    <form action="{{route('admin.fund-user',$userId)}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Сумма</label>
                            <input class="form-control" type="number" name='amount' >
                        </div>

                        <button type="submmit" class="btn btn-primary">Пополнить</button>
                    </form>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </section>

@endsection
