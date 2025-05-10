@extends('admin.layouts.master')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Верификация анкет</h1>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Ожидающие проверки</h4>
          </div>
          <div class="card-body">
            @if(count($verifications) == 0)
              <div class="empty-state" data-height="400">
                <div class="empty-state-icon">
                  <i class="fas fa-question"></i>
                </div>
                <h2>Нет ожидающих заявок на верификацию</h2>
              </div>
            @else
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Анкета</th>
                      <th>Дата заявки</th>
                      <th>Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($verifications as $verification)
                      <tr>
                        <td>{{ $verification->id }}</td>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="mr-3">
                              @if($verification->profile->primaryImage)
                                <img src="{{ asset('storage/' . $verification->profile->primaryImage->path) }}" alt="Profile" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                              @else
                                <div class="avatar avatar-sm bg-primary text-white">
                                  <i class="fas fa-user"></i>
                                </div>
                              @endif
                            </div>
                            <div>
                              <div>{{ $verification->profile->name }}, {{ $verification->profile->age }}</div>
                              <div class="text-muted small">ID: {{ $verification->profile->id }}</div>
                            </div>
                          </div>
                        </td>
                        <td>{{ $verification->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                          <a href="{{ route('admin.verifications.show', $verification->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Просмотреть
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <div class="mt-4">
                {{ $verifications->links() }}
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection