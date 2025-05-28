@extends('admin.layouts.master')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Admin list</h1>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Admin</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="table-2_wrapper"
                                    class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-striped dataTable no-footer" id="table-2" role="grid"
                                            aria-describedby="table-2_info">
                                            <thead>
                                                {{-- column title --}}
                                                <tr role="row">
                                                    <th class="sorting" tabindex="0" aria-controls="table-2"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Task Name: activate to sort column ascending">
                                                        Id
                                                      </th>
                                                    <th class="sorting" tabindex="0" aria-controls="table-2"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Task Name: activate to sort column ascending">
                                                        Имя
                                                      </th>
                                                    <th class="sorting_disabled" rowspan="1" colspan="1"
                                                        aria-label="Progress" >
                                                        Электронная почта
                                                      </th>
                                                    <th class="sorting_disabled text-center" rowspan="1" colspan="1"
                                                        aria-label="Profiles" >
                                                        Профили
                                                      </th>

                                                    <th class="sorting_disabled  text-center " rowspan="1" colspan="1"
                                                      aria-label="Progress" >
                                                      Подтвержден?
                                                    </th>  
                                                    <th class="sorting_disabled  text-center " rowspan="1" colspan="1"
                                                      aria-label="Progress" >
                                                      Счет. Сальдо
                                                    </th>  
                                                    <th class="sorting_disabled" tabindex="0" aria-controls="table-2"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Due Date: activate to sort column ascending">
                                                        Стаутс
                                                      </th>
                                                    <th class="sorting_disabled" tabindex="0" aria-controls="table-2"
                                                    rowspan="1" colspan="1"
                                                    aria-label="Status: activate to sort column ascending">
                                                    Действие
                                                    </th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                              {{-- each data --}}
                                              @foreach ($admins as $admin)

                                                <tr role="row" class="odd">
                                                    <td>{{$admin->id}}</td>

                                                    <td>{{$admin->name}}</td>

                                                    <td>
                                                        <a href="mailto:{{$admin->email}}">{{$admin->email}}</a>
                                                        <button class="btn btn-sm btn-info edit-email-btn" data-id="{{$admin->id}}" data-email="{{$admin->email}}"><i class="fas fa-envelope"></i></button>
                                                    </td>
                                                    <td class='text-center'>
                                                        @if($admin->profiles->count() > 0)
                                                            <button class="btn btn-sm btn-primary view-profiles-btn" data-id="{{$admin->id}}" data-name="{{$admin->name}}">
                                                                <i class="fas fa-eye"></i> {{ $admin->profiles->count() }}
                                                            </button>
                                                        @else
                                                            0
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        @if ($admin->email_verified_at)
                                                            <i class="fas fa-check-circle text-success" title="Email подтвержден"></i>
                                                        @else
                                                            <i class="fas fa-times-circle text-muted" title="Email не подтвержден"></i>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        {{$admin->balance}}
                                                    </td>

                                                    <td>
                                                    @if ($admin->id != 1)
                                                        
                                                      @if($admin->status == 'active')
                                                        <label class="custom-switch mt-2">
                                                            <input type="checkbox" checked name="custom-switch-checkbox" data-id="{{$admin->id}}" class="custom-switch-input change-status" >
                                                            <span class="custom-switch-indicator"></span>
                                                        </label>
                                                      @else 
                                                        <label class="custom-switch mt-2">
                                                            <input type="checkbox" name="custom-switch-checkbox" data-id="{{$admin->id}}" class="custom-switch-input change-status">
                                                            <span class="custom-switch-indicator"></span>
                                                        </label>
                                                      @endif

                                                    @endif
                                                    </td>

                                                    <td>
                                                        @if (Auth::user()->id == 1)
                                                            <div class="dropdown d-inline">
                                                                <button class="btn btn-dark dropdown-toggle" type="button"
                                                                    id="dropdownMenuButton{{ $admin->id }}" data-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </button>
                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $admin->id }}">
                                                    
                                                                    <a class="dropdown-item" href="{{ route('admin.add-fund.index', $admin->id) }}">
                                                                        <i class="fas fa-plus mr-1"></i> Добавить средства
                                                                    </a>
                                                    
                                                                    <a class="dropdown-item" href="{{ route('admin.withdraw-fund.index', $admin->id) }}">
                                                                        <i class="fas fa-wallet mr-1"></i> Вывести средства
                                                                    </a>
                                                    
                                                                    @if ($admin->email_verified_at == null)
                                                                        <button class="dropdown-item verify-email-btn" data-id="{{ $admin->id }}">
                                                                            <i class="fas fa-check-circle mt-1 mr-1 text-success"></i> 
                                                                            <span>
                                                                                Подтвердить email
                                                                            </span>
                                                                        </button>
                                                                    @endif
                                                    
                                                                    <button class="dropdown-item send-reset-link-btn" data-id="{{ $admin->id }}">
                                                                        <i class="fas fa-key mt-1 mr-1 text-warning"></i>
                                                                        <span>
                                                                            Сбросить пароль
                                                                        </span>
                                                                    </button>

                                                                    @if ($admin->id != 1)
                                                                    <a class="dropdown-item text-danger delete-item" href="{{ route('admin.admin-list.destroy', $admin->id) }}">
                                                                        <i class="far fa-trash-alt mr-1"></i> Удалить
                                                                    </a>
                                                                @endif

                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    
                                                </tr>                                                  
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </section>

    <!-- Email Edit Modal -->
    <div class="modal fade" id="emailEditModal" tabindex="-1" role="dialog" aria-labelledby="emailEditModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailEditModalLabel">Редактировать электронную почту</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="emailEditForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="admin_id" id="admin_id">
                        <div class="form-group">
                            <label for="admin_email">Адрес электронной почты</label>
                            <input type="email" class="form-control" id="admin_email" name="email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Profiles Modal -->
    <div class="modal fade" id="profilesModal" tabindex="-1" role="dialog" aria-labelledby="profilesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profilesModalLabel">Профили для <span id="userName"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Имя</th>
                                    <th>Телефон</th>
                                    <th>Статус</th>
                                    <th>Действие</th>
                                </tr>
                            </thead>
                            <tbody id="profilesTableBody">
                                <!-- Profiles will be loaded here by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
   

    <script>
        $(document).ready(function(){
            $('body').on('click', '.change-status', function(){
                let isChecked = $(this).is(':checked');
                let id = $(this).data('id');

                $.ajax({
                    url: "{{route('admin.admin-list.status-change')}}",
                    method: 'PUT',
                    data: {
                        status: isChecked,
                        id: id
                    },
                    success: function(data){
                        toastr.success(data.message)
                    },
                    error: function(xhr, status, error){
                        console.log(error);
                    }
                })

            })

            // Handle Edit Email button click
            $('.edit-email-btn').on('click', function(){
                let adminId = $(this).data('id');
                let adminEmail = $(this).data('email');
                $('#admin_id').val(adminId);
                $('#admin_email').val(adminEmail);
                $('#emailEditModal').modal('show');
            });

            // Handle Email Edit Form submission
            $('#emailEditForm').on('submit', function(e){
                e.preventDefault();
                let adminId = $('#admin_id').val();
                let newEmail = $('#admin_email').val();

                $.ajax({
                    url: "/admin/admin-list/" + adminId + "/update-email",
                    method: 'PUT',
                    data: {
                        _token: "{{ csrf_token() }}",
                        email: newEmail
                    },
                    success: function(data){
                        if(data.status == 'success'){
                            toastr.success(data.message);
                            $('#emailEditModal').modal('hide');
                            // Optionally, refresh the page or update the email in the table
                            location.reload(); 
                        } else {
                            toastr.error('Произошла ошибка.');
                        }
                    },
                    error: function(xhr, status, error){
                        let errors = xhr.responseJSON.errors;
                        if(errors && errors.email){
                            toastr.error(errors.email[0]);
                        } else {
                            toastr.error('Произошла ошибка: ' + error);
                        }
                    }
                });
            });

            // Handle View Profiles button click
            $('.view-profiles-btn').on('click', function(){
                let userId = $(this).data('id');
                let userName = $(this).data('name');
                $('#userName').text(userName);
                $('#profilesTableBody').empty(); // Clear previous profiles

                // Fetch profiles for the user
                // This assumes you have an endpoint to get profiles by user ID
                // For now, we'll use the profiles data already loaded with the user
                let admin = {!! json_encode($admins->keyBy('id')->all()) !!}[userId];
                let profileViewUrlTemplate = "{{ route('profiles.view', ['slug' => 'SLUG_PLACEHOLDER', 'id' => 'ID_PLACEHOLDER']) }}";
                if(admin && admin.profiles && admin.profiles.length > 0){
                    admin.profiles.forEach(function(profile){
                        let profileSpecificUrl = profileViewUrlTemplate
                                                    .replace('SLUG_PLACEHOLDER', profile.slug)
                                                    .replace('ID_PLACEHOLDER', profile.id);
                        let profileRow = `<tr>
                            <td><a href="${profileSpecificUrl}" class="text-underlined">${profile.id}</a></td>
                            <td>${profile.name}</td>
                            <td>${profile.phone ? profile.phone : 'N/A'}</td>
                            <td>${profile.is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Inactive</span>'}</td>
                            <td>
                                <a href="/user/profiles/${profile.id}/edit" class="btn m-1 btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <a href="/admin/profiles/${profile.id}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>`;
                        $('#profilesTableBody').append(profileRow);
                    });
                } else {
                    $('#profilesTableBody').append('<tr><td colspan="5" class="text-center">No profiles found.</td></tr>');
                }

                $('#profilesModal').modal('show');
            });

            // Handle Verify Email button click
            $('.verify-email-btn').on('click', function(){
                let adminId = $(this).data('id');
                $.ajax({
                    url: "/admin/admins/" + adminId + "/verify-email",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data){
                        if(data.status == 'success'){
                            toastr.success(data.message);
                            location.reload(); 
                        } else {
                            toastr.error(data.message || 'Произошла ошибка.');
                        }
                    },
                    error: function(xhr, status, error){
                        toastr.error('Произошла ошибка: ' + error);
                    }
                });
            });

            // Handle Send Reset Link button click
            $('.send-reset-link-btn').on('click', function(){
                let adminId = $(this).data('id');
                $.ajax({
                    url: "/admin/admins/" + adminId + "/send-reset-link",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data){
                        if(data.status == 'success'){
                            let message = 'Ссылка для сброса пароля успешно отправлена';
                            toastr.success(message);
                        } else {
                            toastr.error(data.message || 'Произошла ошибка.');
                        }
                    },
                    error: function(xhr, status, error){
                        let response = xhr.responseJSON;
                        toastr.error(response.message || 'Произошла ошибка: ' + error);
                    }
                });
            });
        })
    </script>
@endpush
