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
                    <h4>Редактировать категорию</h4>

                  </div>
                  <div class="card-body">
                    <form action="{{route('admin.category.update', $category->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                          <label>Предварительный просмотр</label>
                          <br>
                          <img src="{{ asset($category->thumb_image) }}" style="width:200px" alt="">
                      </div>
                      <div class="form-group">
                          <label>Изображение</label>
                          <input type="file" class="form-control" name="image">
                      </div>

                        <div class="form-group">
                            <label>Имя</label>
                            <input type="text" class="form-control" name="name" value="{{$category->name}}">
                        </div>

                        <div class="form-group">
                            <label for="inputState">Раздел</label>
                            <select id="inputState" class="form-control" name="section_id">
                              @foreach ($sections as $section)
                                  <option {{$category->section_id == $section->id?'selected': ''}} value="{{$section->id}}">{{$section->name}}</option>
                              @endforeach
                            </select> 
                        </div>

                        <div class="form-group">
                          <label> Описание</label>
                          <textarea name="description" class="form-control summernote">{!! $category->description !!}</textarea>
                      </div>

                        <div class="form-group">
                            <label for="inputState">Статус</label>
                            <select id="inputState" class="form-control" name="status">
                              <option {{$category->status == 1 ? 'selected': ''}} value="1">Активный</option>
                              <option {{$category->status == 0 ? 'selected': ''}} value="0">Неактивный</option>
                            </select>
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
