@extends('admin.layouts.master')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Управление анкетами</h1>
  </div>

  <div class="section-body">
    <div class="row">
      <!-- Sidebar with filters -->
      <div class="col-12 col-md-3 col-lg-3">
              <!-- Search box -->
              <div class="card">
                <div class="card-header">
                  <h4>Поиск</h4>
                </div>
                <div class="card-body">
                  <form action="{{ route('admin.profiles.index') }}" method="GET">
                    @if(request('filter'))
                      <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="ID, имя, телефон..." name="search" value="{{ request('search') }}">
                      <div class="input-group-append">
                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              
        <div class="card">
          <div class="card-header">
            <h4>Фильтры</h4>
          </div>
          <div class="card-body">
            <ul class="nav nav-pills flex-column">
              <li class="nav-item">
                <a href="{{ route('admin.profiles.index') }}" class="nav-link {{ !request('filter') ? 'active' : '' }}">
                  Все анкеты <span class="badge badge-white">{{ $counts['all'] }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.profiles.index', ['filter' => 'verified']) }}" class="nav-link {{ request('filter') == 'verified' ? 'active' : '' }}">
                  Верифицированные <span class="badge badge-white">{{ $counts['verified'] }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.profiles.index', ['filter' => 'unverified']) }}" class="nav-link {{ request('filter') == 'unverified' ? 'active' : '' }}">
                  Неверифицированные <span class="badge badge-white">{{ $counts['unverified'] }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.profiles.index', ['filter' => 'active']) }}" class="nav-link {{ request('filter') == 'active' ? 'active' : '' }}">
                  Активные <span class="badge badge-white">{{ $counts['active'] }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.profiles.index', ['filter' => 'disabled']) }}" class="nav-link {{ request('filter') == 'disabled' ? 'active' : '' }}">
                    Не активный <span class="badge badge-white">{{ $counts['disabled'] }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.profiles.index', ['filter' => 'with_tariffs']) }}" class="nav-link {{ request('filter') == 'with_tariffs' ? 'active' : '' }}">
                    Разместил рекламу <span class="badge badge-white">{{ $counts['with_tariffs'] }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.profiles.index', ['filter' => 'deleted']) }}" class="nav-link {{ request('filter') == 'deleted' ? 'active' : '' }}">
                  Удаленные <span class="badge badge-white">{{ $counts['deleted'] }}</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
        
      </div>
      
      <!-- Main content -->
      <div class="col-12 col-md-9 col-lg-9">
        <div class="card">
          <div class="card-header">
            <h4>Список анкет</h4>
          </div>
          <div class="card-body">
            @if(count($profiles) == 0)
              <div class="empty-state" data-height="400">
                <div class="empty-state-icon">
                  <i class="fas fa-question"></i>
                </div>
                <h2>Анкеты не найдены</h2>
                <p class="lead">
                  По вашему запросу не найдено ни одной анкеты.
                </p>
              </div>
            @else
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Анкета</th>
                      <th>Статус</th>
                      <th>Реклама</th>
                      <th>Расходы</th>
                      <th>Дата создания</th>
                      <th>Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($profiles as $profile)
                      <tr>
                        <td>{{ $profile->id }}</td>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="mr-3">
                              @if($profile->primaryImage)
                                <img src="{{ asset('storage/' . $profile->primaryImage->path) }}" alt="Profile" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                              @else
                                <div class="avatar avatar-sm bg-primary text-white">
                                  <i class="fas fa-user"></i>
                                </div>
                              @endif
                            </div>
                            <div>
                              <div class="text-capitalize">
                                {{ $profile->name }}, {{ $profile->age }}
                                @if ($profile->is_verified)
                                  <span class="text-success"><i class="fas fa-check-circle fa-2x"></i></span>
                                @endif
                              </div>
                              <div class="text-muted small">{{formatNumber( $profile->phone) }}</div>
                            </div>
                          </div>
                        </td>
                        <td>
                          @if($profile->trashed())
                            <div class="badge badge-danger mb-1">Удалена</div>
                          @elseif(!$profile->is_active)
                            <div class="badge badge-warning mb-1"> Неактивный</div>
                          @else
                            <div class="badge badge-success mb-1">Активна</div>
                          @endif
                        </td>
                        <td>
                          <div class="text-muted">{{ $profile->tariffs_count }} рек.</div>
                        </td>
                        <td>
                          @php
                            $totalSpent = $profile->tariffs->flatMap->charges->sum('amount');
                          @endphp
                          <div class="d-flex align-items-center">
                            <span class="mr-2">{{ number_format($totalSpent, 0, '.', ' ') }} ₽</span>
                            @if($profile->tariffs_count > 0)
                              <button type="button" class="btn btn-sm btn-icon btn-info" data-toggle="modal" data-target="#adStatsModal-{{ $profile->id }}">
                                <i class="fas fa-chart-bar"></i>
                              </button>
                            @endif
                          </div>
                        </td>
                        <td>{{ $profile->created_at->format('d.m.Y') }}</td>
                        <td>
                          <a href="{{route('user.profiles.edit', $profile->id)}}" class="btn m-1 btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                          </a>

                          <a href="{{ route('admin.profiles.show', $profile->id) }}" class="btn btn-primary btn-sm m-1">
                            <i class="fas fa-eye"></i>
                          </a>                          
                          @if($profile->trashed())
                            <form action="{{ route('admin.profiles.restore', $profile->id) }}" method="POST" class="d-inline">
                              @csrf
                              <button type="submit" class="btn btn-success btn-sm m-1 " data-toggle="tooltip" title="Восстановить">
                                <i class="fas fa-trash-restore"></i>
                              </button>
                            </form>
                            
                            <form action="{{ route('admin.profiles.destroy', $profile->id) }}" method="POST" class="d-inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger btn-sm m-1 " data-toggle="tooltip" title="Удалить навсегда" onclick="return confirm('Вы уверены? Это действие нельзя отменить!')">
                                <i class="fas fa-trash"></i>
                              </button>
                            </form>
                          @else
                            <form action="{{ route('admin.profiles.disable', $profile->id) }}" method="POST" class="d-inline">
                              @csrf
                              <button type="submit" class="btn btn-warning btn-sm m-1 " data-toggle="tooltip" title="Отключить">
                                <i class="fas fa-ban"></i>
                              </button>
                            </form>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              
              <div class="mt-4">
                {{ $profiles->links() }}
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
    <!-- Ad Stats Modal for this profile -->
@if(count($profiles) > 0)
@foreach ($profiles as $profile)
@if($profile->tariffs_count > 0)
<div class="modal fade" id="adStatsModal-{{ $profile->id }}" tabindex="-1" role="dialog" aria-labelledby="adStatsModalLabel-{{ $profile->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="adStatsModalLabel-{{ $profile->id }}">Статистика расходов на рекламу - {{ $profile->name }} </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class=" ml-4 pl-1">
        <h6 class="text-muted"> 
          Пользователь: 
              <a href="mailto:{{ $profile->user->email }}">{{ $profile->user->email }}</a>
               (ID: {{ $profile->user->id }})
        </h6>
      </div>
      <div class="modal-body">
        @php
          // Group tariffs by type
          $basicTariffs = $profile->tariffs->filter(function($tariff) {
            return $tariff->adTariff && $tariff->adTariff->slug === 'basic';
          });
          
          $priorityTariffs = $profile->tariffs->filter(function($tariff) {
            return $tariff->adTariff && $tariff->adTariff->slug === 'priority';
          });
          
          $vipTariffs = $profile->tariffs->filter(function($tariff) {
            return $tariff->adTariff && $tariff->adTariff->slug === 'vip';
          });
          
          // Calculate spending by type
          $basicSpent = $basicTariffs->flatMap->charges->sum('amount');
          $prioritySpent = $priorityTariffs->flatMap->charges->sum('amount');
          $vipSpent = $vipTariffs->flatMap->charges->sum('amount');
          $totalSpent = $basicSpent + $prioritySpent + $vipSpent;
        @endphp
        
        <!-- Summary Cards -->
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="card card-statistic-1">
              <div class="card-icon bg-primary">
                <i class="fas fa-money-bill-wave"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Всего потрачено</h4>
                </div>
                <div class="card-body">
                  {{ number_format($totalSpent, 0, '.', ' ') }} ₽
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-statistic-1">
              <div class="card-icon bg-success">
                <i class="fas fa-ad"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Базовая реклама</h4>
                </div>
                <div class="card-body">
                  {{ number_format($basicSpent, 0, '.', ' ') }} ₽
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-statistic-1">
              <div class="card-icon bg-warning">
                <i class="fas fa-star"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Приоритет</h4>
                </div>
                <div class="card-body">
                  {{ number_format($prioritySpent, 0, '.', ' ') }} ₽
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-statistic-1">
              <div class="card-icon bg-danger">
                <i class="fas fa-crown"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>VIP</h4>
                </div>
                <div class="card-body">
                  {{ number_format($vipSpent, 0, '.', ' ') }} ₽
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Detailed Tariff History -->
        <h6 class="section-title mt-0">История рекламных тарифов</h6>
        <div class="table-responsive">
          <table class="table table-striped dataTable">
            <thead>
              <tr>
                <th>Тип</th>
                <th>Дата активации</th>
                <th>Дата окончания</th>
                <th>Длительность</th>
                <th>Статус</th>
                <th>Сумма</th>
              </tr>
            </thead>
            <tbody>
              @foreach($profile->tariffs->sortByDesc('created_at') as $tariff)
                @php
                  $tariffCharges = $tariff->charges->sum('amount');
                  $duration = $tariff->charges->count();
                  if ($tariff->is_active) {
                    $status = 'Активен';
                    $statusClass = 'success';
                  } elseif ($tariff->is_paused) {
                    $status = 'Приостановлен';
                    $statusClass = 'warning';
                  } else {
                    $status = 'Завершен';
                    $statusClass = 'secondary';
                  }
                @endphp
                <tr>
                  <td>
                    <span class="font-weight-bold">{{ $tariff->adTariff->name }}</span>
                    @if($tariff->isPriority() && $tariff->priority_level)
                      <div class="small text-muted">Уровень: {{ $tariff->priority_level }}</div>
                    @endif
                  </td>
                  <td>{{ $tariff->created_at->format('d.m.Y') }}</td>
                  <td>{{ $tariff->expires_at ? $tariff->expires_at->format('d.m.Y') : 'Бессрочно' }}</td>
                  <td>{{ $duration }} дней</td>
                  <td><span class="badge badge-{{ $statusClass }}">{{ $status }}</span></td>
                  <td>{{ number_format($tariffCharges, 0, '.', ' ') }} ₽</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>
@endif
@endforeach
@endif
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
@endpush