@extends('admin.layouts.master')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Проверка верификации</h1>
    <div class="section-header-button ml-auto">
      <a href="{{route('admin.verifications.index')}}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Назад к списку
      </a>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <!-- Profile Information -->
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-header">
            <h4>Информация об анкете</h4>
            <div class="card-header-action">
              <a href="{{ route('profiles.view', [
                'slug'=>$verification->profile->slug,
                'id'=>$verification->profile->id,
                ]) }}" class="btn btn-icon btn-primary" target="_blank" data-toggle="tooltip" title="Просмотреть профиль пользователя">
                <i class="fas fa-user"></i> Просмотреть профиль
              </a>
            </div>
          </div>
          <div class="card-body">
            <div class="user-item">
              <div class="user-details">
                <div class="user-picture mb-2">
                  @if($verification->profile->primaryImage)
                    <img alt="image" src="{{ asset('storage/' . $verification->profile->primaryImage->path) }}" class="img-fluid rounded" width="100">
                  @else
                    <div class="avatar avatar-xl bg-primary text-white">
                      <i class="fas fa-user"></i>
                    </div>
                  @endif
                </div>
                <div class="user-name">
                  <h5 class="mb-1">{{ $verification->profile->name }}, {{ $verification->profile->age }}</h5>
                  <div class="text-muted">ID анкеты: {{ $verification->profile->id }}</div>
                  <div class="text-muted">Дата создания: {{ $verification->profile->created_at->format('d.m.Y') }}</div>
                </div>
              </div>
            </div>

            <div class="mt-4">
              <div class="section-title mt-0">Контактная информация</div>
              <ul class="list-unstyled list-unstyled-border">
                @if($verification->profile->email)
                  <li class="media">
                    <div class="media-icon"><i class="far fa-envelope"></i></div>
                    <div class="media-body">
                      <div class="media-title">Email</div>
                      <div class="text-muted">{{ $verification->profile->email }}</div>
                    </div>
                  </li>
                @endif
                @if($verification->profile->phone)
                  <li class="media">
                    <div class="media-icon"><i class="fas fa-phone"></i></div>
                    <div class="media-body">
                      <div class="media-title">Телефон</div>
                      <div class="text-muted">{{ formatNumber($verification->profile->phone) }}</div>
                    </div>
                  </li>
                @endif
              </ul>
            </div>

            <div class="mt-4">
              <div class="section-title mt-0">Заявка на верификацию</div>
              <ul class="list-unstyled list-unstyled-border">
                <li class="media">
                  <div class="media-icon"><i class="far fa-clock"></i></div>
                  <div class="media-body">
                    <div class="media-title">Дата заявки</div>
                    <div class="text-muted">{{ $verification->created_at->format('d.m.Y H:i') }}</div>
                  </div>
                </li>
                <li class="media">
                  <div class="media-icon"><i class="fas fa-info-circle"></i></div>
                  <div class="media-body">
                    <div class="media-title">Статус</div>
                    @if($verification->status === 'pending')
                      <div class="badge badge-warning">На рассмотрении</div>
                    @elseif($verification->status === 'approved')
                      <div class="badge badge-success">Одобрено</div>
                    @elseif($verification->status === 'rejected')
                      <div class="badge badge-danger">Отклонено</div>
                    @endif
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Verification Photo and Profile Photos -->
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-header">
            <h4>Фото для верификации</h4>
          </div>
          <div class="card-body">
            <!-- Verification Photo - Separate section with clear styling -->
            <div class="mb-4 verification-photo-container">
              <div class="verification-photo">
                <a href="{{ asset('storage/' . $verification->photo_path) }}" class="verification-photo-link" data-title="Фото для верификации">
                  <img src="{{ asset('storage/' . $verification->photo_path) }}" alt="Verification Photo" class="img-fluid rounded">
                </a>
              </div>
              <div class="text-muted mt-2">
                <span>Пользователь должен держать лист бумаги с ID анкеты: {{ $verification->profile->id }}</s>
              </div>
            </div>

            @if($verification->status === 'pending')
            <div class="mt-4">
              <div class="row">
                <div class="col-6">
                  <form action="{{ route('admin.verifications.process', $verification->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="btn btn-success btn-lg btn-block">
                      <i class="fas fa-check"></i> Одобрить 
                    </button>
                  </form>
                </div>
                <div class="col-6">
                  <form action="{{ route('admin.verifications.process', $verification->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="btn btn-danger btn-lg btn-block">
                      <i class="fas fa-times"></i> Отклонить 
                    </button>
                  </form>
                </div>
              </div>
            </div>
          @endif


            <!-- Profile Photos - Separate gallery with proper spacing -->
            <div class="section-title mt-4">Фотографии профиля</div>
            <div class="profile-gallery">
              @if($verification->profile->images && $verification->profile->images->count() > 0)
                <div class="row">
                  @foreach($verification->profile->images as $image)
                  <div class="col-4 mb-3">
                    <a href="{{ asset('storage/' . $image->path) }}" class="profile-photo-link" data-title="Фото профиля">
                      <img src="{{ asset('storage/' . $image->path) }}" alt="Profile Photo" class="img-fluid rounded profile-thumbnail">
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
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<style>
  /* Custom styles for verification photos display */
  .verification-photo-container {
    margin-bottom: 30px;
    border-bottom: 1px solid #f9f9f9;
    padding-bottom: 20px;
  }
  
  .verification-photo img {
    max-width: 100%;
    height: auto;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    cursor: pointer;
  }
  
  .profile-gallery {
    margin-top: 15px;
  }
  
  .profile-thumbnail {
    width: 100%;
    height: 120px;
    object-fit: cover;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
    cursor: pointer;
  }
  
  .profile-thumbnail:hover {
    transform: scale(1.03);
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
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
    text-shadow: 0 0 3px rgba(0,0,0,0.5);
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
  $(document).ready(function() {
    // Initialize modal-based lightbox for verification photos
    $('.verification-photo-link, .profile-photo-link').on('click', function(e) {
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