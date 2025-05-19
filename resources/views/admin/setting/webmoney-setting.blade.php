<div class="tab-pane fade" id="list-webmoney" role="tabpanel" aria-labelledby="list-webmoney-list">
    <div class="card border">
        <div class="card-body">
            <form action="{{ route('admin.webmoney-setting.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>WebMoney Merchant Purse (Z-Purse)</label>
                    <input type="text" class="form-control" name="webmoney_merchant_purse" value="{{ $webmoneySettings['merchant_purse'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label>WebMoney Secret Key</label>
                    <input type="text" class="form-control" name="webmoney_secret_key" value="{{ $webmoneySettings['secret_key'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label>WebMoney Simulation Mode</label>
                    <select name="webmoney_sim_mode" class="form-control">
                        <option value="0" {{ ($webmoneySettings['sim_mode'] ?? '0') == '0' ? 'selected' : '' }}>Disabled</option>
                        <option value="1" {{ ($webmoneySettings['sim_mode'] ?? '0') == '1' ? 'selected' : '' }}>Enabled</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>