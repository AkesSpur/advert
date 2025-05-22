@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Настройки Hero секции для конкретных моделей</h1>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4>Выберите модель для настройки Hero секции</h4>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.hero-section-override.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="model_type_selector">Тип модели</label>
                            <select name="model_type" id="model_type_selector" class="form-control select2">
                                <option value="">-- Выберите тип --</option>
                                <option value="HeroSectionSetting" {{ request('model_type') == 'HeroSectionSetting' ? 'selected' : '' }}>Общие настройки Hero (по умолчанию)</option>
                                <option value="CustomCategory" {{ request('model_type') == 'CustomCategory' ? 'selected' : '' }}>Кастомная категория</option>
                                <option value="Service" {{ request('model_type') == 'Service' ? 'selected' : '' }}>Услуга</option>
                                <option value="MetroStation" {{ request('model_type') == 'MetroStation' ? 'selected' : '' }}>Станция метро</option>
                                <option value="Price" {{ request('model_type') == 'Price' ? 'selected' : '' }}>Цена</option>
                                <option value="Age" {{ request('model_type') == 'Age' ? 'selected' : '' }}>Возраст</option>
                                <option value="HairColor" {{ request('model_type') == 'HairColor' ? 'selected' : '' }}>Цвет волос</option>
                                <option value="Height" {{ request('model_type') == 'Height' ? 'selected' : '' }}>Рост</option>
                                <option value="Weight" {{ request('model_type') == 'Weight' ? 'selected' : '' }}>Вес</option>
                                <option value="Size" {{ request('model_type') == 'Size' ? 'selected' : '' }}>Размер</option>
                                <option value="Neighborhood" {{ request('model_type') == 'Neighborhood' ? 'selected' : '' }}>Район</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="model_id_selector">Конкретная модель</label>
                            <select name="model_id" id="model_id_selector" class="form-control select2">
                                <option value="">-- Сначала выберите тип --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Загрузить</button>
                    </div>
                </div>
            </form>

            @if($heroOverride)
            <h5>Настройка для: {{ $displayModelInfo['name'] }} (Тип: {{ $displayModelInfo['type'] }}, ID для сохранения: {{ $displayModelInfo['id'] }})</h5>
            <form action="{{ route('admin.hero-section-override.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="model_type" value="{{ $displayModelInfo['type'] }}">
                <input type="hidden" name="model_id" value="{{ $displayModelInfo['id'] }}">

                <div class="form-group">
                    <label for="title">Заголовок (оставьте пустым для значения по умолчанию)</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $heroOverride->title) }}">
                </div>

                <div class="form-group">
                    <label for="text_content">Текстовое содержимое (оставьте пустым для значения по умолчанию)</label>
                    <textarea name="text_content" id="text_content" class="form-control summernote">{{ old('text_content', $heroOverride->text_content) }}</textarea>
                </div>

                <div class="form-group">
                    @if($heroOverride->image)
                    <img src="{{ asset($heroOverride->image) }}" width="200px" alt="hero image override">
                    <br>
                    @endif
                    <label for="image">Изображение (оставьте пустым для значения по умолчанию)</label>
                    <input type="file" class="form-control" name="image" id="image">
                    <input type="hidden" class="form-control" name="old_image" value="{{ $heroOverride->image }}">
                </div>

                <div class="form-group">
                    <div class="control-label">Статус</div>
                    <label class="custom-switch mt-2">
                        <input type="checkbox" name="is_active" class="custom-switch-input" {{ $heroOverride->is_active ? 'checked' : '' }} value="1">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">Активно (если выключено, будет использовано значение по умолчанию)</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Обновить переопределение</button>
            </form>
            @elseif($currentModelType && $currentModelId !== null && $currentModelId !== '' && !$heroOverride)
                <p class="text-danger">Не удалось найти указанную модель или ее переопределение для Тип: {{ $currentModelType }}, ID: {{ $currentModelId }}.</p>
            @elseif($currentModelType && ($currentModelId === null || $currentModelId === ''))
                 <p>Пожалуйста, выберите конкретную модель (или тип для общих настроек фильтра).</p>
            @else
                <p>Пожалуйста, выберите тип модели для настройки.</p>
            @endif
        </div>
    </div>
</section>

@push('scripts')
<script>
$(document).ready(function() {
    const modelTypeSelector = $('#model_type_selector');
    const modelIdSelector = $('#model_id_selector');
    const initialModelType = '{{ $currentModelType }}';
    const initialModelId = '{{ $currentModelId }}'; // This could be 0 for type-level or specific ID for instance-level
    const typeLevelModels = @json($typeLevelModels);

    const modelsData = {
        HeroSectionSetting: @json($heroSectionSettings->mapWithKeys(function ($item) { return [$item->id => $item->title ?: 'Основные настройки ID '.$item->id]; })->toArray()),
        CustomCategory: @json($customCategories->mapWithKeys(function ($item) { return [$item->id => $item->name]; })->toArray()),
        Service: { 0: 'Все услуги (тип)' }, // Type-level override
        MetroStation: { 0: 'Все станции метро (тип)' },
        Price: { 0: 'Все цены (тип)' },
        Age: { 0: 'Все возрасты (тип)' },
        HairColor: { 0: 'Все цвета волос (тип)' },
        Height: { 0: 'Весь рост (тип)' },
        Weight: { 0: 'Весь вес (тип)' },
        Size: { 0: 'Все размеры (тип)' },
        Neighborhood: { 0: 'Все районы (тип)' },
    };

    function populateModelIds(selectedType) {
        modelIdSelector.empty();
        if (!selectedType) {
            modelIdSelector.append('<option value="">-- Сначала выберите тип --</option>');
            modelIdSelector.prop('disabled', true);
        } else if (typeLevelModels.includes(selectedType)) {
            // For type-level models, only one option: ID 0
            modelIdSelector.append($('<option></option>').attr('value', 0).text(modelsData[selectedType][0]));
            modelIdSelector.val(0); // Pre-select it
            modelIdSelector.prop('disabled', false); // Enable, though only one choice
        } else if (modelsData[selectedType]) {
            // For instance-level models (CustomCategory, HeroSectionSetting)
            modelIdSelector.append('<option value="">-- Выберите модель --</option>');
            $.each(modelsData[selectedType], function(id, name) {
                modelIdSelector.append($('<option></option>').attr('value', id).text(name));
            });
            modelIdSelector.prop('disabled', false);
            if (selectedType === initialModelType && initialModelId !== '' && initialModelId !== null) {
                 modelIdSelector.val(initialModelId);
            }
        } else {
            modelIdSelector.append('<option value="">-- Нет данных для этого типа --</option>');
            modelIdSelector.prop('disabled', true);
        }
        modelIdSelector.trigger('change'); // for select2 update
    }

    modelTypeSelector.on('change', function() {
        populateModelIds($(this).val());
    });

    // Initial population if model_type is already selected (e.g. on page load with query params)
    if (initialModelType) {
        populateModelIds(initialModelType);
    }

    $('.select2').select2();

});
</script>
@endpush

@endsection