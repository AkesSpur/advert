@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Настройки секции Hero</h1>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4>Обновить настройки секции Hero</h4>
        </div>
        <div class="card-body">
            <form action="{{route('admin.hero-section-setting.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="title">Заголовок</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{@$heroSetting->title}}">
                </div>

                <div class="form-group">
                    <label for="text_content">Текстовое содержимое</label>
                    <textarea name="text_content" id="text_content" class="form-control summernote">{{@$heroSetting->text_content}}</textarea>
                </div>

                <div class="form-group">
                    @if(@$heroSetting->image)
                    <img src="{{asset(@$heroSetting->image)}}" width="200px" alt="hero image">
                    <br>
                    @endif
                    <label for="image">Изображение</label>
                    <input type="file" class="form-control" name="image" id="image">
                    <input type="hidden" class="form-control" name="old_image" value="{{@$heroSetting->image}}">
                </div>

                <button type="submit" class="btn btn-primary">Обновить</button>
            </form>
        </div>
    </div>
</section>
@endsection