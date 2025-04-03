
@extends('admin.layouts.app')
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
        <div class="page-header">
            <div class="page-header-left">
                <h2>{{ $title }}</h2>
                <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route("admin.blog.index") }}">{{ translate("Blog List") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"> {{ $title }} </li>
                    </ol>
                </nav>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="form-header">
                <div class="row gy-4 align-items-center">
                    <div class="col-xxl-2 col-xl-3">
                        <h4 class="card-title">{{ translate("Create a New Blog") }}</h4>
                    </div>
                   
                </div>
            </div>
            <div class="card-body pt-0">
                <form id="contact_store" action="{{route('admin.blog.save')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" hidden name="uid" value="{{ $blog->uid }}">
                    <div class="form-element">
                        <div class="row gy-3">
                            <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ translate("Blog Title & Image") }}</h5>
                            </div>
                            <div class="col-xxl-7 col-xl-9">
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            <label for="title" class="form-label"> {{ translate("Title") }} </label>
                                            <input type="text" id="title" name="title" class="form-control" placeholder="{{ translate("Enter blog title") }}" aria-label="blog title" value="{{ $blog->title }}"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inner">
                                            <label for="image" class="form-label"> {{ translate("image") }} <sup class="text-danger"> *{{ config('setting.file_path.blog_images')['size'] }}</sup> </label>
                                            
                                            <input type="file" id="image" name="image" class="form-control" placeholder="{{ translate("Enter blog title") }}" aria-label="blog image" />
                                            <p class="form-element-note">{{ translate("Accepted Image Type: ").implode(', ', json_decode(site_settings("mime_types"), true)) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-element">
                        <div class="row gy-3">
                            <div class="col-xxl-2 col-xl-3">
                                <h5 class="form-element-title">{{ translate("Blog Description") }}</h5>
                            </div>
                            <div class="col-xxl-7 col-xl-9">
                                <div class="row gy-4">
                                    <div class="col-12">
                                        <div class="form-inner">
                                            <label for="description" class="form-label"> {{ translate("Description") }} </label>
                                            <textarea class="form-control" name="description" id="description" rows="12" placeholder="{{ translate('Enter blog description') }}" aria-label="{{ translate('blog description') }}">{{ $blog->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-9">
                            <div class="form-action justify-content-end">
                            <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Submit") }} </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection
@section("modal")

@endsection
@push('script-push')
<script>
	(function($){
		"use strict";
        ck_editor("#description");
	})(jQuery);
</script>
@endpush

