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
                            <h4>Создать категорию</h4>

                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                  <label>Изображение</label>
                                  <input type="file" class="form-control" name="image" required>
                                </div>

                                <div class="form-group">
                                    <label>Имя</label>
                                    <input type="text" class="form-control" name="name" value="">
                                </div>


                                <div class="form-group">
                                    <label for="section">Раздел</label>
                                    <select id="section" class="form-control main-section" name="section_id" required>
                                        <option value="">Выбрать</option>
                                        @foreach ($sections as $section)
                                            <option {{ old('section') == $section->id ? 'selected' : '' }}
                                                value="{{ $section->id }}">{{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                  <label>Описание</label>
                                  <textarea name="description" class="form-control summernote">{{ old('description') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="inputState">Статус</label>
                                    <select id="inputState" class="form-control" name="status">
                                        <option value="1">Активный</option>
                                        <option value="0">Неактивный</option>
                                    </select>
                                </div>
                                <button type="submmit" class="btn btn-primary">Создать</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
