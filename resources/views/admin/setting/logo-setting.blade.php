<div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
    <div class="card border">
        <div class="card-body">
            <form action="{{route('admin.logo-setting.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <img src="{{asset(@$logoSetting->logo)}}" width="150px" alt="">
                    <br>
                    <label>Логотип</label>
                    <input type="file" class="form-control" name="logo" value="">
                    <input type="hidden" class="form-control" name="old_logo" value="{{$logoSetting->logo ?? ''}}">

                </div>

                <div class="form-group">
                    <img src="{{asset(@$logoSetting->favicon)}}" width="150px" alt="">
                    <br>
                    <label>Favicon</label>
                    <input type="file" class="form-control" name="favicon" value="">
                    <input type="hidden" class="form-control" name="old_favicon" value="{{$logoSetting->favicon ?? ''}}">

                </div>



                <div class="form-group">
                    <img src="{{asset(@$logoSetting->login_image)}}" width="150px" alt="">
                    <br>
                    <label>Изображение для страницы входа</label>
                    <input type="file" class="form-control" name="login_image" value="">
                    <input type="hidden" class="form-control" name="old_login_image" value="{{$logoSetting->login_image ?? ''}}">
                </div>

                <button type="submit" class="btn btn-primary">Обновить</button>
            </form>
        </div>
    </div>
</div>
