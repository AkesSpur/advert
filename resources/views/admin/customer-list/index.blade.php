@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Список клиентов</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Все клиенты</h4>
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
                                                почта
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
                                                aria-label="Due Date: activate to sort column ascending">
                                                Действие
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      {{-- each data --}}
                                      @foreach ($customers as $customer)

                                        <tr role="row" class="odd">
                                            <td>{{$customer->id}}</td>

                                            <td>{{$customer->name}}</td>

                                            <td>
                                              <a href="mailto:{{$customer->email}}">{{$customer->email}}</a>
                                              <button class="btn btn-sm btn-info ml-1 edit-email-btn" data-id="{{$customer->id}}" data-email="{{$customer->email}}">Edit </button>
                                            </td>
                                            <td class="text-center">
                                                @if($customer->profiles->count() > 0)
                                                    <button class="btn btn-sm btn-primary view-profiles-btn" data-id="{{$customer->id}}" data-name="{{$customer->name}}">
                                                        <i class="fas fa-eye"></i> {{ $customer->profiles->count() }}
                                                    </button>
                                                @else
                                                    0
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($customer->email_verified_at)
                                                    <i class="fas fa-check-circle text-success" title="Email подтвержден"></i>
                                                @else
                                                    <i class="fas fa-times-circle text-muted" title="Email не подтвержден"></i>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                {{$customer->balance}}
                                            </td>

                                            <td>
                                              @if($customer->status == 'active')
                                                <label class="custom-switch mt-2">
                                                    <input type="checkbox" checked name="custom-switch-checkbox" data-id="{{$customer->id}}" class="custom-switch-input change-status" >
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                              @else 
                                                <label class="custom-switch mt-2">
                                                    <input type="checkbox" name="custom-switch-checkbox" data-id="{{$customer->id}}" class="custom-switch-input change-status">
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                              @endif
                                            </td>

                                            <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-dark dropdown-toggle" type="button"
                                                            id="dropdownMenuButton{{ $customer->id }}" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                            
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $customer->id }}">
                                                        @if (Auth::id() == 1)
                                                            <a class="dropdown-item has-icon" href="{{ route('admin.add-fund.index', $customer->id) }}">
                                                                <i class="fas fa-plus text-success mr-2"></i> Добавить средства
                                                            </a>
                                                            <a class="dropdown-item has-icon" href="{{ route('admin.withdraw-fund.index', $customer->id) }}">
                                                                <i class="fas fa-wallet text-warning mr-2"></i> Вывести средства
                                                            </a>
                                                        @endif
                                            
                                                        @if (is_null($customer->email_verified_at))
                                                            <button class="dropdown-item has-icon verify-email-btn" data-id="{{ $customer->id }}">
                                                                <i class="fas fa-check-circle text-success mt-1 mr-2"></i> 
                                                                <span>
                                                                    Подтвердить Email
                                                                </span>
                                                            </button>
                                                        @endif
                                            
                                                        <button class="dropdown-item has-icon send-reset-link-btn" data-id="{{ $customer->id }}">
                                                            <i class="fas fa-key text-primary mt-1 mr-2"></i> 
                                                            <span>
                                                                Сбросить пароль
                                                            </span>
                                                        </button>
                                                        <a class="dropdown-item has-icon delete-item" href="{{ route('admin.customers.destroy', $customer->id) }}">
                                                            <i class="fas fa-trash text-danger mr-2"></i> 
                                                            <span>
                                                                Удалить пользователя
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
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
        </section>

    <!-- Email Edit Modal -->
    <div class="modal fade" id="emailEditModal" tabindex="-1" role="dialog" aria-labelledby="emailEditModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailEditModalLabel">Изменить Email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="emailEditForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="customer_id" id="customer_id">
                        <div class="form-group">
                            <label for="customer_email">Адрес электронной почты</label>
                            <input type="email" class="form-control" id="customer_email" name="email" required>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}

    <script>
        $(document).ready(function(){
            $('body').on('click', '.change-status', function(){
                let isChecked = $(this).is(':checked');
                let id = $(this).data('id');

                $.ajax({
                    url: "{{route('admin.customer.status-change')}}",
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
            $('body').on('click', '.edit-email-btn', function(){
                let customerId = $(this).data('id');
                let customerEmail = $(this).data('email');
                $('#customer_id').val(customerId);
                $('#customer_email').val(customerEmail);
                $('#emailEditModal').modal('show');
            });

            // Handle Email Edit Form submission
            $('#emailEditForm').on('submit', function(e){
                e.preventDefault();
                let customerId = $('#customer_id').val();
                let newEmail = $('#customer_email').val();

                $.ajax({
                    url: "/admin/customer-list/" + customerId + "/update-email", // Make sure this route exists
                    method: 'PUT',
                    data: {
                        _token: "{{ csrf_token() }}",
                        email: newEmail
                    },
                    success: function(data){
                        if(data.status == 'success'){
                            toastr.success(data.message);
                            $('#emailEditModal').modal('hide');
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
            $('body').on('click', '.view-profiles-btn', function(){
                let userId = $(this).data('id');
                let userName = $(this).data('name');
                $('#userName').text(userName);
                $('#profilesTableBody').empty(); 

                let customer = {!! json_encode($customers->keyBy('id')->all()) !!}[userId];
                let profileViewUrlTemplate = "{{ route('profiles.view', ['slug' => 'SLUG_PLACEHOLDER', 'id' => 'ID_PLACEHOLDER']) }}";
                if(customer && customer.profiles && customer.profiles.length > 0){
                    customer.profiles.forEach(function(profile){
                        let profileSpecificUrl = profileViewUrlTemplate
                                                    .replace('SLUG_PLACEHOLDER', profile.slug)
                                                    .replace('ID_PLACEHOLDER', profile.id);
                        let profileRow = `<tr>
                            <td><a href="${profileSpecificUrl}" class="text-underlined">${profile.id}</a></td>
                            <td>${profile.name}</td>
                            <td>${profile.phone ? profile.phone : 'N/A'}</td>
                            <td>${profile.is_active ? '<span class="badge badge-success">Активный</span>' : '<span class="badge badge-warning">Неактивный</span>'}</td>
                            <td>
                                <a href="/user/profiles/${profile.id}/edit" class="btn m-1 btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <a href="/admin/profiles/${profile.id}" class="btn btn-sm m-1 btn-primary"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>`;
                        $('#profilesTableBody').append(profileRow);
                    });
                } else {
                    $('#profilesTableBody').append('<tr><td colspan="5" class="text-center">Профили не найдены.</td></tr>');
                }

                $('#profilesModal').modal('show');
            });

            // Handle Verify Email button click
            $('body').on('click', '.verify-email-btn', function(){
                let customerId = $(this).data('id');
                $.ajax({
                    url: "/admin/customers/" + customerId + "/verify-email", // Adjusted URL for customers
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
            $('body').on('click', '.send-reset-link-btn', function(){
                let customerId = $(this).data('id');
                $.ajax({
                    url: "/admin/customers/" + customerId + "/send-reset-link", // Adjusted URL for customers
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
