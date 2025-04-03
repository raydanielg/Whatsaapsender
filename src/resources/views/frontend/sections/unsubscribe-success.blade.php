@extends('frontend.layouts.main')

@section('content')
<div class="container-fluid container-wrapper my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <img src="{{showImage(config("setting.file_path.frontend.unsubscription_image.path").'/'.getArrayValue(@$unsubscribe_content->section_value, 'unsubscription_image'),config("setting.file_path.frontend.unsubscription_image.size"))}}" alt="Unsubscribe Logo" class="mb-3">
                    <h5 class="card-title">{{getTranslatedArrayValue(@$unsubscribe_content->section_value, 'heading') }}</h5>
                    <p class="card-text my-4">{{getTranslatedArrayValue(@$unsubscribe_content->section_value, 'sub_heading') }}</p>
                    <a href="{{getTranslatedArrayValue(@$unsubscribe_content->section_value, 'btn_url') }}" class="i-btn btn--primary bg--gradient btn--xl pill w-max-content">{{getTranslatedArrayValue(@$unsubscribe_content->section_value, 'btn_name') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

