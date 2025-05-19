@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Настройки</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">

                    <div class="card-body">
                      <div class="row">
                        <div class="col-2">
                          <div class="list-group" id="list-tab" role="tablist">
                            <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab">Общие настройки</a>
                            <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab">Настройка почты</a>
                            <a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab">Логотип и Favicon</a>
                            <a class="list-group-item list-group-item-action" id="list-webmoney-list" data-toggle="list" href="#list-webmoney" role="tab">WebMoney Настройки</a>

                          </div>
                        </div>
                        <div class="col-10">
                          <div class="tab-content" id="nav-tabContent">

                            @include('admin.setting.general-setting')

                            @include('admin.setting.email-configuration')

                            @include('admin.setting.logo-setting')

                            @include('admin.setting.webmoney-setting')

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>

          </div>
        </section>

@endsection
