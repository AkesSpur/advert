@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Услуги</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Редактировать услугу</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.services.update', $service->id)}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label>Имя <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{old('name', $service->name)}}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="title">Title (SEO)</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $service->title) }}">
                                    @error('title')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="meta_description">Meta Description (SEO)</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description">{{ old('meta_description', $service->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="h1_header">H1 Заголовок (SEO)</label>
                                    <input type="text" class="form-control" id="h1_header" name="h1_header" value="{{ old('h1_header', $service->h1_header) }}">
                                    @error('h1_header')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Статус <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status">
                                        <option value="1" {{ old('status', $service->status) == '1' ? 'selected' : '' }}>Активно</option>
                                        <option value="0" {{ old('status', $service->status) == '0' ? 'selected' : '' }}>Неактивно</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Обновление</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection