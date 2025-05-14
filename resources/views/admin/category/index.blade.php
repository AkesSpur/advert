@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Категория</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Все категории</h4>
                    <div class="card-header-action">
                        <a href="{{route('admin.category.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Создать новый</a>
                    </div>
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
                                                Image
                                              </th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1"
                                                aria-label="Progress" >
                                                Имя
                                              </th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1"
                                              aria-label="Progress" >
                                              Раздел
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
                                      {{-- each data --}}
                                      @foreach ($categories as $category)

                                        <tr role="row" class="odd">

                                            <td>{{$category->id}}</td>

                                            <td>
                                              <img width='100px' src='{{ asset($category->thumb_image) }}'></img>
                                          </td>

                                            <td>{{$category->name}}</td>

                                            <td>{{$category->section->name}}</td>

                                            <td>
                                                
                                              @if($category->status == true )
                                                <label class="custom-switch mt-2">
                                                    <input type="checkbox" checked name="custom-switch-checkbox" data-id="{{$category->id}}" class="custom-switch-input change-status" >
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                              @else 
                                                <label class="custom-switch mt-2">
                                                    <input type="checkbox" name="custom-switch-checkbox" data-id="{{$category->id}}" class="custom-switch-input change-status">
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                              @endif

                                            </td>

                                            <td>
                                                <a href="{{route('admin.category.edit', $category->id)}}" class='btn btn-primary'><i class='far fa-edit'></i></a>
                                                  <i class='far fa-edit'></i>
                                                </a>
                                                <a href="{{route('admin.category.destroy', $category->id)}}" class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>
                                                  <i class='far fa-trash-alt'></i>
                                                </a>
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

                $.ajax({
                    url: "{{route('admin.category.change-status')}}",
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
        })
    </script>
@endpush
