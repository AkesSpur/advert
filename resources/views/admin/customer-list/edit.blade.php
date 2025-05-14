@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Кошелек пользователя</h1>

          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Редактировать баланс кошелька пользователя</h4>
                  </div>
                  <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group">
                          <label>Сумма</label>
                          <input type="number" class="form-control" name="amount" value="" required>
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
