@extends('admin.layouts.app')
@section('panel')
    <section>
        <div class="container-fluid p-0">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ translate('Bee Free Credentials Setup')}}<sup class="pointer" title="{{ translate('To setup bee free auth')}}">  <a href="https://developers.beefree.io/" target="_blank"><i class="fa fa-info-circle"></i></a> </sup>
                    </h4>
                </div>

                @php
                    $beefree = (json_decode($general->bee_plugin,true));
                @endphp

                <div class="card-body">
                    <form action="{{ route('admin.general.setting.beefree.update') }}" method="post" class="d-flex flex-column gap-3">
                       @csrf
                        <div class="form-wrapper">
                            <div class="row g-4">
                                @foreach($beefree as $key => $cred)
                                   <div class="mb-3 col-lg-6">
                                    <label for="{{$key}}">{{ ucwords(str_replace('_', ' ', $key))}} <sup class="text--danger">*</sup></label>
                                        @if($key == 'status')
                                           <select class="form-select" name="bee_plugin[{{$key}}]"  id="{{$key}}">
                                               <option value="1" @if($cred == 1) selected  @endif>ON</option>
                                               <option value="2" @if($cred ==  2) selected  @endif>OFF</option>
                                           </select>
                                        @else
                                            <input type="text"  name="bee_plugin[{{$key}}]" id="{{$key}}" class="form-control"  value="{{$cred}}" placeholder="********" required>
                                        @endif
                                   </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="i-btn primary--btn btn--lg">{{translate('Submit')}}</button>
                    </form>
                </div>
			</div>
		</div>
    </section>
@endsection



