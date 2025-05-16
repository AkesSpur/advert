<div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
<div class="card border">
    <div class="card-body">
        <form action="{{route('admin.general-setting.update')}}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Название сайта</label>
                <input type="text" class="form-control" name="site_name" value="{{@$generalSettings->site_name}}">
            </div>
            <hr>
            <div class="form-group">
                <label>Дешевый порог</label>
                <input type="number" class="form-control" name="cheap_threshold" value="{{@$generalSettings->cheap_threshold}}">
            </div>
            <div class="form-group">
                <label>Профилей на страницу</label>
                <input type="number" class="form-control" name="profiles_per_page" value="{{@$generalSettings->profiles_per_page}}">
            </div>
            <hr>
            <div class="form-group">
                <label>H1 заголовок по умолчанию</label>
                <input type="text" class="form-control" name="default_h1_heading" value="{{@$generalSettings->default_h1_heading}}">
            </div>
            <div class="form-group">
                <label>SEO Title по умолчанию</label>
                <input type="text" class="form-control" name="default_seo_title" value="{{@$generalSettings->default_seo_title}}">
            </div>
            <div class="form-group">
                <label>SEO Description по умолчанию</label>
                <textarea class="form-control" name="default_seo_description">{{@$generalSettings->default_seo_description}}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
</div>
</div>
