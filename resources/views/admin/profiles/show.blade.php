@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
    <h1>Просмотр анкеты #{{ $profile->id }}</h1>
    <div class="section-header-button ml-auto">
      <a href="{{route('admin.profiles.index')}}" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> <span class="ml-1"> Назад к списку</span>
      </a>
    </div>
    </div>

    <div class="section-body">
    <!-- Status and Actions -->
    <div class="row mb-4">
      <div class="col-12">
      <div class="card">
        <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
          <h6 class="text-muted mb-1">Статус анкеты:</h6>
          <div>
            @if($profile->trashed())
        <span class="badge badge-danger">Удалена</span>
        @elseif(!$profile->is_active)
        <span class="badge badge-warning">Отключена</span>
        @else
        <span class="badge badge-success">Активна</span>
        @endif

            @if($profile->is_verified)
        <span class="badge badge-info">Верифицирована</span>
        @else
        <span class="badge badge-light">Не верифицирована</span>
        @endif
          </div>
          </div>
          <div class="text-right">
          @if($profile->trashed())
        <form action="{{ route('admin.profiles.restore', $profile->id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-success">
        <i class="fas fa-trash-restore mr-1"></i> Восстановить
        </button>
        </form>

        <form action="{{ route('admin.profiles.destroy', $profile->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
        onclick="return confirm('Вы уверены? Это действие нельзя отменить!')">
        <i class="fas fa-trash mr-1"></i> Удалить навсегда
        </button>
        </form>
      @else
        <form action="{{ route('admin.profiles.disable', $profile->id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-warning">
        <i class="fas fa-ban mr-1"></i> Отключить анкету
        </button>
        </form>
      @endif
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>

    <div class="row">
      <!-- Profile Information -->
      <div class="col-12 col-md-6 col-lg-6">
      <div class="card">
        <div class="card-header">
        <h4>Информация об анкете</h4>
        @if (!$profile->trashed())
      <div class="card-header-action">
        <a href="{{ route('profiles.view', $profile->id) }}" class="btn btn-icon btn-primary" target="_blank"
        data-toggle="tooltip" title="Просмотреть профиль на сайте">
        <i class="fas fa-external-link-alt"></i>
        </a>
      </div>
      @endif
        </div>
        <div class="card-body">
        <div class="user-item">
          <div class="user-details">
          <div class="user-picture mb-2">
            @if($profile->primaryImage)
        <img alt="image" src="{{ asset('storage/' . $profile->primaryImage->path) }}"
        class="img-fluid rounded" width="100">
        @else
        <div class="avatar avatar-xl bg-primary text-white">
        <i class="fas fa-user"></i>
        </div>
        @endif
          </div>
          <div class="user-name">
            <h5 class="mb-1 text-capitalize">
              {{ $profile->name }}, {{ $profile->age }}
            @if ($profile->is_verified)
            <span class="text-success"><i class="fas fa-check-circle fa-2x"></i></span>
            @endif
            </h5>
            <div class="text-muted">ID анкеты: {{ $profile->id }}</div>
            <div class="text-muted">Дата создания: {{ $profile->created_at->format('d.m.Y') }}</div>
            <div class="text-muted">Пользователь: {{ $profile->user->name }} (ID: {{ $profile->user->id }})</div>
          </div>
          </div>
        </div>

        <div class="mt-4">
          <div class="section-title mt-0">Контактная информация</div>
          <ul class="list-unstyled list-unstyled-border">
          @if($profile->email)
        <li class="media">
        <div class="media-icon"><i class="far fa-envelope"></i></div>
        <div class="media-body">
        <div class="media-title">Email</div>
        <div class="text-muted">{{ $profile->email }}</div>
        </div>
        </li>
      @endif
          @if($profile->phone)
        <li class="media">
        <div class="media-icon"><i class="fas fa-phone"></i></div>
        <div class="media-body">
        <div class="media-title">Телефон</div>
        <div class="text-muted">{{ formatNumber($profile->phone) }}</div>
        </div>
        </li>
      @endif
          @if($profile->has_telegram || $profile->has_whatsapp || $profile->has_viber)
        <li class="media">
          <div class="media-icon"><i class="fas fa-comments"></i></div>
          <div class="media-body">
          <div class="media-title">Мессенджеры</div>
          <div class="text-muted">
          @if($profile->has_telegram)
        <span class="badge badge-info">Telegram</span>
        @endif
          @if($profile->has_whatsapp)
        <span class="badge badge-success">WhatsApp</span>
        @endif
          @if($profile->has_viber)
        <span class="badge badge-purple">Viber</span>
        @endif
          </div>
          </div>
        </li>
      @endif
          </ul>
        </div>

        @if($profile->description || $profile->bio)
        <div class="mt-4">
        <div class="section-title mt-0">Описание</div>
        <div class="p-3 bg-light rounded">
        @if($profile->description)
        <p>{{ $profile->description }}</p>
      @endif
        @if($profile->bio)
        <p>{{ $profile->bio }}</p>
      @endif
        </div>
        </div>
      @endif

        @if($profile->services && $profile->services->count() > 0)
        <div class="mt-4">
        <div class="section-title mt-0">Услуги</div>
        <div>
        @foreach($profile->services as $service)
        <span class="badge badge-primary mr-1 mb-1">{{ $service->name }}</span>
      @endforeach
        </div>
        </div>
      @endif
        </div>
      </div>
      </div>

      <!-- Profile Photos and Verification -->
      <div class="col-12 col-md-6 col-lg-6">
      <!-- Verification Status -->
      @if($profile->verificationPhoto)
      <div class="card">
        <div class="card-header">
        <h4>Верификация</h4>
        </div>
        <div class="card-body">
        <div class="mb-3">
        <div class="d-flex align-items-center mb-2">
        <div class="mr-3">
          @if($profile->verificationPhoto->status === 'approved')
        <div class="text-success"><i class="fas fa-check-circle fa-2x"></i></div>
        @elseif($profile->verificationPhoto->status === 'rejected')
        <div class="text-danger"><i class="fas fa-times-circle fa-2x"></i></div>
        @else
        <div class="text-warning"><i class="fas fa-clock fa-2x"></i></div>
        @endif
        </div>
        <div>
          <h6 class="mb-0">
          @if($profile->verificationPhoto->status === 'approved')
        Верификация одобрена
        @elseif($profile->verificationPhoto->status === 'rejected')
        Верификация отклонена
        @else
        Ожидает проверки
        @endif
          </h6>
          <small class="text-muted">{{ $profile->verificationPhoto->created_at->format('d.m.Y H:i') }}</small>
        </div>
        </div>
        </div>

        <!-- Verification Photo -->
        <div class="verification-photo-container">
        <div class="verification-photo">
        <a href="{{ asset('storage/' . $profile->verificationPhoto->photo_path) }}"
          class="verification-photo-link" data-title="Фото для верификации">
          <img src="{{ asset('storage/' . $profile->verificationPhoto->photo_path) }}" alt="Verification Photo"
          class="img-fluid rounded">
        </a>
        </div>
        <div class="text-muted mt-2">
        <small>Пользователь должен держать лист бумаги с ID анкеты: {{ $profile->id }}</small>
        </div>
        </div>

        @if($profile->verificationPhoto->status === 'pending')
      <div class="mt-3">
        <div class="row">
        <div class="col-6">
        <form action="{{ route('admin.verifications.process', $profile->verificationPhoto->id) }}"
        method="POST">
        @csrf
        <input type="hidden" name="action" value="approve">
        <button type="submit" class="btn btn-success btn-block">
        <i class="fas fa-check"></i> Одобрить
        </button>
        </form>
        </div>
        <div class="col-6">
        <form action="{{ route('admin.verifications.process', $profile->verificationPhoto->id) }}"
        method="POST">
        @csrf
        <input type="hidden" name="action" value="reject">
        <button type="submit" class="btn btn-danger btn-block">
        <i class="fas fa-times"></i> Отклонить
        </button>
        </form>
        </div>
        </div>
      </div>
      @endif
        </div>
      </div>
    @endif

      <!-- Profile Photos -->
      <div class="card">
        <div class="card-header">
        <h4>Фотографии профиля</h4>
        </div>
        <div class="card-body">
        <div class="profile-gallery">
          @if($profile->images && $profile->images->count() > 0)
        <div class="row">
        @foreach($profile->images as $image)
        <div class="col-4 mb-3">
        <a href="{{ asset('storage/' . $image->path) }}" class="profile-photo-link" data-title="Фото профиля">
        <img src="{{ asset('storage/' . $image->path) }}" alt="Profile Photo"
        class="img-fluid rounded profile-thumbnail">
        </a>
        </div>
      @endforeach
        </div>
      @else
        <div class="text-center py-3">
        <p class="text-muted">Нет фотографий профиля</p>
        </div>
      @endif
        </div>
        </div>
      </div>

      <!-- Ads -->
      <div class="card">
        <div class="card-header">
        <h4>Объявления</h4>
        </div>
        <div class="card-body">
        @if($profile->tariffs && $profile->tariffs->count() > 0)
        <ul class="list-unstyled list-unstyled-border">
        @foreach($profile->tariffs as $ad)
        <li class="media">
        <div class="media-icon {{ $ad->is_active ? 'bg-success' : 'bg-warning' }} py-1 px-2 text-white">
        <i class="fas {{ $ad->is_active ? 'fa-check' : 'fa-pause' }}"></i>
        </div>
        <div class="media-body">
        <div class="media-title">{{ $ad->title }}</div>
        <div class="text-small text-muted">
        ID: {{ $ad->id }} |
        Создано: {{ $ad->created_at->format('d.m.Y') }} |
        Статус: {{ $ad->is_active ? 'Активно' : 'Отключено' }}
        </div>
        </div>
        </li>
      @endforeach
        </ul>
      @else
      <div class="text-center py-3">
        <p class="text-muted">Нет объявлений</p>
      </div>
      @endif
        </div>
      </div>
      </div>
    </div>

    <!-- Ad Spending Statistics -->
    <div class="card">
      <div class="card-body">
      @if($profile->tariffs && $profile->tariffs->count() > 0)
      <!-- Detailed Tariff History -->
      <h6 class="section-title mt-0">История рекламных тарифов</h6>
      <div class="table-responsive">
      <table class="table table-striped">
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
      $duration = $tariff->expires_at ? $tariff->created_at->diffInDays($tariff->expires_at) : $tariff->created_at->diffInDays(now());
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
    @else
      <div class="text-center py-3">
      <p class="text-muted">Нет данных о расходах на рекламу</p>
      </div>
    @endif
      </div>
    </div>
    </div>

  </section>
@endsection

@push('scripts')
  <style>
    /* Custom styles for verification photos display */
    .verification-photo-container {
    margin-bottom: 20px;
    padding-bottom: 20px;
    }

    .verification-photo img {
    max-width: 100%;
    height: auto;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    }

    .profile-gallery {
    margin-top: 15px;
    }

    .profile-thumbnail {
    width: 100%;
    height: 120px;
    object-fit: cover;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
    cursor: pointer;
    }

    .profile-thumbnail:hover {
    transform: scale(1.03);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    }

    /* Modal customization for photo viewing */
    .photo-modal .modal-dialog {
    max-width: 90%;
    margin: 30px auto;
    }

    .photo-modal .modal-content {
    background-color: transparent;
    border: none;
    }

    .photo-modal .modal-body {
    padding: 0;
    position: relative;
    }

    .photo-modal img {
    max-height: 85vh;
    margin: 0 auto;
    display: block;
    }

    .photo-modal .close {
    position: absolute;
    right: 15px;
    top: 15px;
    color: white;
    font-size: 30px;
    opacity: 0.8;
    text-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
    z-index: 1050;
    }

    .photo-modal .close:hover {
    opacity: 1;
    }
  </style>

  <!-- Photo Modal -->
  <div class="modal fade photo-modal" id="photoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <!-- Content will be dynamically inserted here -->
    </div>
    </div>
  </div>

  <script>
    $(document).ready(function () {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Initialize modal-based lightbox for photos
    $('.verification-photo-link, .profile-photo-link').on('click', function (e) {
      e.preventDefault();

      const imgSrc = $(this).attr('href');
      const imgTitle = $(this).data('title');

      // Create modal content
      const modalContent = `
      <div class="modal-body p-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        <img src="${imgSrc}" class="img-fluid" alt="${imgTitle}">
      </div>
      `;

      // Create and show modal
      $('#photoModal .modal-content').html(modalContent);
      $('#photoModal').modal('show');
    });
    });
  </script>
@endpush