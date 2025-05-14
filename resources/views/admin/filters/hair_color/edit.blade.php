@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Управление фильтрами</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Редактировать цвет волос</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.filters.hair-color.update', $hairColor->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Название (RU)</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $hairColor->name) }}">
                        @error('name')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="title">Title (SEO)</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $hairColor->title) }}">
                        @error('title')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="meta_description">Meta Description (SEO)</label>
                        <textarea class="form-control" id="meta_description" name="meta_description">{{ old('meta_description', $hairColor->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="h1_header">H1 Заголовок (SEO)</label>
                        <input type="text" class="form-control" id="h1_header" name="h1_header" value="{{ old('h1_header', $hairColor->h1_header) }}">
                        @error('h1_header')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="sort_order">Порядок сортировки</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $hairColor->sort_order ?? 0) }}">
                        @error('sort_order')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Статус <span class="text-danger">*</span></label>
                        <select class="form-control" name="status">
                            <option value="1" {{ old('status', $hairColor->status) == '1' ? 'selected' : '' }}>Активно</option>
                            <option value="0" {{ old('status', $hairColor->status) == '0' ? 'selected' : '' }}>Неактивно</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('admin.filters.hair-color.index') }}" class="btn btn-danger">Отмена</a>
                </form>
            </div>
        </div>
    </section>
@endsection