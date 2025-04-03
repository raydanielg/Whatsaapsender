@extends('admin.layouts.app')
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
        <div class="page-header">
            <div class="page-header-left">
                <h2>{{ textFormat(['_'], $title, ' ') }}</h2>
                <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"> {{ textFormat(['_'], $title, ' ') }} </li>
                    </ol>
                </nav>
                </div>
            </div>
        </div>
        <div class="card">
        
            <div class="card-body pt-0">
                <form action="{{ route("admin.system.setting.store") }}" method="POST" enctype="multipart/form-data" class="settingsForm">
                    @csrf
                    <div class="form-element">
                        <div class="row gy-4">
                          <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Page Elements") }}</h5>
                          </div>
                          <div class="col-xxl-8 col-xl-9">
                            <div class="row gy-4">
                              <div class="col-md-12">
                                <div class="form-inner">
                                  <label for="auth_heading" class="form-label"> {{ translate("Page Heading") }} <small class="text-danger">*</small></label>
                                  <input type="text" id="auth_heading" name="site_settings[{{ \App\Enums\SettingKey::AUTH_HEADING->value }}]" class="form-control" placeholder="{{ translate('Enter auth page header') }}" aria-label="{{ translate('auth page header') }}" value="{{ site_settings("auth_heading") }}"/>
                                </div>
                              </div>
                                @foreach (Arr::get(config('setting'),'auth_image_keys' ,[]) as $auth_image_key )
                                    <div class="col-lg-12">
                                        <div class="form-inner">
                                            <label for="{{$auth_image_key}}" class="form-label">
                                                {{(textFormat(['_'], $auth_image_key, ' '))}} <small class="text-danger" >* ({{config("setting")['file_path'][$auth_image_key]['size']}})</small>
                                            </label>
                                            <input class="form-control"  type="file" name="site_settings[{{$auth_image_key}}]" id="{{$auth_image_key}}" class="preview" data-size = "{{config('setting')['file_path'][$auth_image_key]['size']}}">
                                            <p class="form-element-note">{{ translate("Accepted Image Type: ").implode(', ', json_decode(site_settings("mime_types"), true)) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                    

                    <div class="row">
                        <div class="col-xxl-10">
                            <div class="form-action justify-content-end">
                            <button type="reset" class="i-btn btn--danger outline btn--md"> {{ translate("Reset") }} </button>
                            <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
