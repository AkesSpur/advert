@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Меню нижнего колонтитула</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Меню нижнего колонтитула</h4>
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
                                            <th class="sorting_disabled" rowspan="1" colspan="1"
                                                aria-label="Progress" >
                                                Имя
                                              </th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1"
                                              aria-label="Progress" >
                                              Статус
                                            </th>  
                                            <th class="sorting_disabled" tabindex="0" aria-controls="table-2"
                                                rowspan="1" colspan="1"
                                                aria-label="Due Date: activate to sort column ascending">
                                                Действие
                                              </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      {{-- Predefined Menus --}}
                                      @foreach ($menus as $menu)
                                        <tr role="row" class="odd">
                                            <td>{{$menu->id}}</td>
                                            <td>{{$menu->name}} (Предопределенное)</td>
                                            <td>
                                              @if($menu->status == true )
                                                <label class="custom-switch mt-2">
                                                    <input type="checkbox" checked name="custom-switch-checkbox" data-id="{{$menu->id}}" data-type="predefined" class="custom-switch-input change-status" >
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                              @else 
                                                <label class="custom-switch mt-2">
                                                    <input type="checkbox" name="custom-switch-checkbox" data-id="{{$menu->id}}" data-type="predefined" class="custom-switch-input change-status">
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                              @endif
                                            </td>
                                            <td>
                                                <a href="{{route('admin.footer-menu.edit', $menu->id)}}" class='btn btn-primary'><i class='far fa-edit'></i></a>
                                            </td>
                                        </tr>
                                      @endforeach

                                      {{-- Custom Categories for Footer Menu --}}
                                      @foreach ($customCategories as $category)
                                        <tr role="row" class="odd">
                                            <td>CC-{{$category->id}}</td> {{-- Prefix to distinguish from predefined --}}
                                            <td>{{$category->name}} (Пользовательская)</td>
                                            <td>
                                                <label class="custom-switch mt-2">
                                                    <input type="checkbox" {{ $category->show_in_footer_menu ? 'checked' : '' }} name="custom-switch-checkbox" data-id="{{ $category->id }}" data-type="custom" data-menu-type="footer" class="custom-switch-input change-custom-category-menu-status">
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a href="{{route('admin.custom-category.edit', $category->id)}}" class='btn btn-info'><i class='far fa-eye'></i></a> {{-- Link to custom category edit --}}
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

@endsection

@push('scripts')
    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}

    <script>
        $(document).ready(function(){
            $('body').on('click', '.change-status', function(){
                let isChecked = $(this).is(':checked');
                let id = $(this).data('id');
                let type = $(this).data('type'); // 'predefined' or 'custom'

                let url = "";
                let dataPayload = {};

                if (type === 'predefined') {
                    url = "{{route('admin.footer-menu.status-change')}}";
                    dataPayload = {
                        status: isChecked,
                        id: id,
                        _token: "{{ csrf_token() }}"
                    };
                } else { // This case should not happen with current setup
                    return;
                }

                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: dataPayload,
                    success: function(data){
                        toastr.success(data.message)
                    },
                    error: function(xhr, status, error){
                        console.log(error);
                    }
                })

            })

            // Handle Custom Category Menu Status Change for Footer
            $('body').on('click', '.change-custom-category-menu-status', function(){
                let isChecked = $(this).is(':checked');
                let id = $(this).data('id');
                // menuType is already defined in the data attribute as 'footer' for this view

                $.ajax({
                    url: "{{ route('admin.custom-category.change-menu-status') }}",
                    method: 'PUT',
                    data: {
                        status: isChecked,
                        id: id,
                        menu_type: 'footer', // Explicitly 'footer' for this view
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data){
                        toastr.success(data.message)
                    },
                    error: function(xhr, status, error){
                        console.log(error);
                        toastr.error('Произошла ошибка при обновлении статуса меню пользовательской категории.');
                    }
                })
            })
        })
    </script>
@endpush
