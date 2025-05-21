@extends('admin.layouts.master') {{-- Main admin layout --}}

{{-- @section('title', 'Edit SEO Template for ' . ucfirst($pageType)) --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Редактирование SEO-шаблона для страницы {{ ucfirst($pageType) }}</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.seo_templates.update', $pageType) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title_template">Шаблон Title</label>
                            <input type="text" name="title_template" id="title_template" class="form-control @error('title_template') is-invalid @enderror" value="{{ old('title_template', $template->title_template) }}">
                            @error('title_template')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="meta_description_template">Шаблон Meta Description</label>
                            <textarea name="meta_description_template" id="meta_description_template" class="form-control @error('meta_description_template') is-invalid @enderror" rows="3">{{ old('meta_description_template', $template->meta_description_template) }}</textarea>
                            @error('meta_description_template')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="h1_template">Шаблон H1</label>
                            <input type="text" name="h1_template" id="h1_template" class="form-control @error('h1_template') is-invalid @enderror" value="{{ old('h1_template', $template->h1_template) }}">
                            @error('h1_template')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="city_override">Город для плейсхолдера <code>{город}</code> (по умолчанию: St. Petersburg)</label>
                            <input type="text" name="city_override" id="city_override" class="form-control @error('city_override') is-invalid @enderror" value="{{ old('city_override', $template->city_override ?? 'St. Petersburg') }}">
                            @error('city_override')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">Если оставить пустым, будет использовано значение по умолчанию "St. Petersburg".</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Обновить шаблон</button>
                        <div class="mt-4">
                            <h4>Доступные плейсхолдеры:</h4>
                            <p>Используйте эти плейсхолдеры в шаблонах. Они будут автоматически заменены на соответствующие значения из профиля.</p>
                            <ul>
                                <li><code>{имя}</code> - Имя профиля</li>
                                <li><code>{возраст}</code> - Возраст</li>
                                <li><code>{цвет_волос}</code> - Цвет волос</li>
                                <li><code>{город}</code> - Город (по умолчанию "St. Petersburg", можно переопределить выше)</li>
                                <li><code>{тип_профиля}</code> - Тип профиля (Индивидуалка/Интим-салон)</li>
                                <li><code>{описание}</code> - Описание профиля</li>
                                <li><code>{телефон}</code> - Телефон</li>
                                <li><code>{вес}</code> - Вес</li>
                                <li><code>{высота}</code> - Рост</li>
                                <li><code>{грудь}</code> - Размер груди</li>
                                <li><code>{метро}</code> - Станции метро (через запятую)</li>
                                <li><code>{район}</code> - Районы (через запятую)</li>
                                <li><code>{услуги}</code> - Услуги (через запятую)</li>
                                <li><code>{цена_1_часа_апартаментов}</code> - Цена за 1 час в апартаментах</li>
                                <li><code>{цена_1_час_отъезда}</code> - Цена за 1 час на выезд</li>
                            </ul>
                        </div>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Отмена</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

