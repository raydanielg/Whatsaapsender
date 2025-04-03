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
                            <li class="breadcrumb-item active" aria-current="page"> {{ translate("Users") }} </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <h4 class="card-title">{{ translate("User List") }}</h4>
                </div>
                <div class="card-header-right">
                    <button class="i-btn btn--primary btn--sm word-create" type="button" data-bs-toggle="modal" data-bs-target="#createWord">
                        <i class="ri-add-fill fs-16"></i> {{ translate("Add A New Spam Word") }} 
                    </button>
                </div>
            </div>
            <div class="card-body px-0 pt-0">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">{{ translate("Name") }}</th>
                                <th scope="col">{{ translate("Value") }}</th>
                                <th scope="col">{{ translate("Option") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($offensiveData as $key => $data)
                                <tr class="@if($loop->even)@endif">
                                    <td data-label="{{ translate('Name')}}">
                                        {{$key}}</p>
                                    </td>

                                    <td data-label="{{ translate('Value')}}">
                                        <form action="{{route('admin.system.spam.word.update')}}" method="POST">
                                            @csrf
                                            <div class="d-flex align-items-center justify-content-md-start justify-content-end gap-3">
                                                <input type="hidden" name="key" value="{{$key}}" class="form-control">
                                                <input type="text" name="value" value="{{$data}}" class="form-control">

                                                <button type="submit" class="i-btn success--btn btn--sm btn-sm text--light">
                                                    <i class="ri-save-line"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>

                                    <td data-label={{ translate('Action')}}>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="icon-btn btn-ghost btn-sm success-soft circle word-delete"
                                                    type="button" 
                                                    data-id="{{$key}}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#worddelete">
                                                    <i class="ri-delete-bin-line"></i>
                                                    <span class="tooltiptext"> {{ translate("Delete") }} </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ translate('No Data Found')}}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@section("modal")
<div class="modal fade" id="createWord" tabindex="-1" aria-labelledby="createWord" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('admin.system.spam.word.store')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Add Spam Word") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-md-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="key" class="form-label"> {{ translate('Spam Word')}} </label>
                                <input type="text" id="key" name="key" placeholder="{{ translate('Enter the spam word')}}" class="form-control" aria-label="key"/>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="value" class="form-label"> {{ translate('Value')}} </label>
                                <input type="text" id="value" name="value" class="form-control" aria-label="value" placeholder="{{ translate('Enter value')}}" />
                                <p class="form-element-note">{{ translate("This value will be used as a replacement value for the spam word") }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
                    <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade actionModal" id="worddelete" tabindex="-1" aria-labelledby="worddelete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('admin.system.spam.word.delete')}}" method="POST">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="id" value="">
                <div class="action-message">
                    <h5>{{ translate("Are you sure to delete this language?") }}</h5>
                    <p>{{ translate("By clicking on 'Delete', you will permanently remove the language from the application") }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="i-btn btn--dark outline btn--lg" data-bs-dismiss="modal"> {{ translate("Cancel") }} </button>
                <button type="submit" class="i-btn btn--danger btn--lg" data-bs-dismiss="modal"> {{ translate("Delete") }} </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection

@push('script-push')
<script>
	(function($){
		"use strict";
		$('.word-delete').on('click', function(){
			var modal = $('#worddelete');
			modal.find('input[name=id]').val($(this).data('id'));
			modal.modal('show');
		});
        $('.word-create').on('click', function() {
            
            $('#createWord').modal('show');
        });

	})(jQuery);
</script>
@endpush
