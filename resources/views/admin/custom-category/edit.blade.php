@extends('admin.layouts.master')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Пользовательские категории</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Редактировать пользовательскую категорию</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.custom-category.update', $customCategory->id) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <!-- Basic Information -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Название категории</label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name', $customCategory->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Slug (оставьте пустым для автоматической генерации)</label>
                                            <input type="text" class="form-control" name="slug" value="{{ old('slug', $customCategory->slug) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO Information -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="mt-3 mb-3">SEO информация</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>SEO заголовок</label>
                                            <input type="text" class="form-control" name="title" value="{{ old('title', $customCategory->title) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>H1 заголовок</label>
                                            <input type="text" class="form-control" name="h1" value="{{ old('h1', $customCategory->h1) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Статус</label>
                                            <select class="form-control" name="status">
                                                <option value="1" {{ old('status', $customCategory->status) == '1' ? 'selected' : '' }}>Активный</option>
                                                <option value="0" {{ old('status', $customCategory->status) == '0' ? 'selected' : '' }}>Неактивный</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Meta описание</label>
                                            <textarea class="form-control" name="meta_description" rows="3">{{ old('meta_description', $customCategory->meta_description) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filter Criteria -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="mt-4 mb-3">Критерии фильтрации</h5>
                                    </div>
                                </div>

                                <!-- Age Filters -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Возраст</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="age_filters[]" value="age-18" id="age-18" {{ in_array('age-18', $customCategory->age_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="age-18">18 лет</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="age_filters[]" value="age-under-20" id="age-under-20" {{ in_array('age-under-20', $customCategory->age_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="age-under-20">до 20 лет</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="age_filters[]" value="age-under-22" id="age-under-22" {{ in_array('age-under-22', $customCategory->age_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="age-under-22">Молодые (до 22 лет)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="age_filters[]" value="age-under-25" id="age-under-25" {{ in_array('age-under-25', $customCategory->age_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="age-under-25">до 25 лет</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="age_filters[]" value="age-under-30" id="age-under-30" {{ in_array('age-under-30', $customCategory->age_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="age-under-30">до 30 лет</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="age_filters[]" value="age-30-35" id="age-30-35" {{ in_array('age-30-35', $customCategory->age_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="age-30-35">30-35 лет</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="age_filters[]" value="age-35-40" id="age-35-40" {{ in_array('age-35-40', $customCategory->age_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="age-35-40">35-40 лет</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="age_filters[]" value="age-28-40" id="age-28-40" {{ in_array('age-28-40', $customCategory->age_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="age-28-40">Зрелые (28-40 лет)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="age_filters[]" value="age-over-40" id="age-over-40" {{ in_array('age-over-40', $customCategory->age_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="age-over-40">40+ лет</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Weight Filters -->
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Вес</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="weight_filters[]" value="weight-under-45" id="weight-under-45" {{ in_array('weight-under-45', $customCategory->weight_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="weight-under-45">Тощие (до 45 кг)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="weight_filters[]" value="weight-under-50" id="weight-under-50" {{ in_array('weight-under-50', $customCategory->weight_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="weight-under-50">Худые (до 50 кг)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="weight_filters[]" value="weight-50-65" id="weight-50-65" {{ in_array('weight-50-65', $customCategory->weight_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="weight-50-65">Пышные (50-65 кг)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="weight_filters[]" value="weight-over-65" id="weight-over-65" {{ in_array('weight-over-65', $customCategory->weight_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="weight-over-65">Толстые (65+ кг)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Size Filters -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Размер груди</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="size_filters[]" value="size-under-1" id="size-under-1" {{ in_array('size-under-1', $customCategory->size_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="size-under-1">Маленькая (до 1)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="size_filters[]" value="size-1-2" id="size-1-2" {{ in_array('size-1-2', $customCategory->size_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="size-1-2">Грудь 1-2</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="size_filters[]" value="size-2-3" id="size-2-3" {{ in_array('size-2-3', $customCategory->size_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="size-2-3">Грудь 2-3</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="size_filters[]" value="size-over-3" id="size-over-3" {{ in_array('size-over-3', $customCategory->size_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="size-over-3">Большая (3+)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hair Color Filters -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Цвет волос</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="hair_color_filters[]" value="blondes" id="blondes" {{ in_array('blondes', $customCategory->hair_color_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="blondes">Блондинки</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="hair_color_filters[]" value="brunettes" id="brunettes" {{ in_array('brunettes', $customCategory->hair_color_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="brunettes">Брюнетки</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="hair_color_filters[]" value="redheads" id="redheads" {{ in_array('redheads', $customCategory->hair_color_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="redheads">Рыжие</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="hair_color_filters[]" value="Wolverines" id="wolverines" {{ in_array('Wolverines', $customCategory->hair_color_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="wolverines">Русые</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="hair_color_filters[]" value="brown-haired" id="brown-haired">
                                                            <label class="form-check-label" for="brown-haired">Шатенки</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Height Filters -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Рост</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="height_filters[]" value="height-under-150" id="height-under-150" {{ in_array('height-under-150', $customCategory->height_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="height-under-150">Маленькие (до 150 см)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="height_filters[]" value="height-under-165" id="height-under-165" {{ in_array('height-under-165', $customCategory->height_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="height-under-165">Низкие (до 165 см)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="height_filters[]" value="height-165-180" id="height-165-180" {{ in_array('height-165-180', $customCategory->height_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="height-165-180">165-180 см</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="height_filters[]" value="height-over-180" id="height-over-180" {{ in_array('height-over-180', $customCategory->height_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="height-over-180">Высокие (180+ см)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boolean Filters -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Дополнительные фильтры</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="filter_is_vip" value="1" id="filter_is_vip" {{ old('filter_is_vip', $customCategory->filter_is_vip) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="filter_is_vip">VIP</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="filter_is_new" value="1" id="filter_is_new" {{ old('filter_is_new', $customCategory->filter_is_new) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="filter_is_new">Новые</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="filter_is_verified" value="1" id="filter_is_verified" {{ old('filter_is_verified', $customCategory->filter_is_verified) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="filter_is_verified">Проверенные</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="filter_has_video" value="1" id="filter_has_video" {{ old('filter_has_video', $customCategory->filter_has_video) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="filter_has_video">С видео</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="filter_is_cheapest" value="1" id="filter_is_cheapest" {{ old('filter_is_cheapest', $customCategory->filter_is_cheapest) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="filter_is_cheapest">Дешевые</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Filters -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Цена</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="price_filters[]" value="price-under-2000" id="price-under-2000" {{ in_array('price-under-2000', $customCategory->price_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="price-under-2000">Дешевые (до 2000 рублей в час)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="price_filters[]" value="price-1500" id="price-1500" {{ in_array('price-1500', $customCategory->price_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="price-1500">За 1500 руб.</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="price_filters[]" value="price-under-2500" id="price-under-2500" {{ in_array('price-under-2500', $customCategory->price_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="price-under-2500">До 2500 руб.</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="price_filters[]" value="price-under-5000" id="price-under-5000" {{ in_array('price-under-5000', $customCategory->price_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="price-under-5000">До 5000 руб.</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="price_filters[]" value="price-under-8000" id="price-under-8000" {{ in_array('price-under-8000', $customCategory->price_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="price-under-8000">До 8000 руб.</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="price_filters[]" value="price-over-5000" id="price-over-5000" {{ in_array('price-over-5000', $customCategory->price_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="price-over-5000">Дорогие (5000+ рублей в час)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="price_filters[]" value="price-over-8000" id="price-over-8000" {{ in_array('price-over-8000', $customCategory->price_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="price-over-8000">8000+ руб.</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="price_filters[]" value="price-over-10000" id="price-over-10000" {{ in_array('price-over-10000', $customCategory->price_filters ?? '[]') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="price-over-10000">Элитные (10000+ рублей в час)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Services -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Услуги</h6>
                                            </div>
                                            <div class="card-body">
                                                <select class="form-control select2" name="service_ids[]" multiple>
                                                    @foreach($customCategory->services as $service)
                                                        <option value="{{ $service->id }}" {{ in_array($service->id, $customCategory->services->pluck('id')->toArray() ?? []) ? 'selected' : '' }}>{{ $service->name }} ({{ $service->profiles->count() }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Metro Stations -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Станции метро</h6>
                                            </div>
                                            <div class="card-body">
                                                <select class="form-control select2" name="metro_station_ids[]" multiple>
                                                    @foreach($customCategory->metroStations as $station)
                                                        <option value="{{ $station->id }}" {{ in_array($station->id, $customCategory->metroStations->pluck('id')->toArray() ?? []) ? 'selected' : '' }}>{{ $station->name }} ({{ $station->profiles->count() }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Neighborhoods -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Районы</h6>
                                            </div>
                                            <div class="card-body">
                                                <select class="form-control select2" name="neighborhood_ids[]" multiple>
                                                    @foreach($customCategory->neighborhoods as $neighborhood)
                                                        <option value="{{ $neighborhood->id }}" {{ in_array($neighborhood->id, $customCategory->neighborhoods->pluck('id')->toArray() ?? []) ? 'selected' : '' }}>{{ $neighborhood->name }} ({{ $neighborhood->profiles->count() }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Обновить категорию</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush