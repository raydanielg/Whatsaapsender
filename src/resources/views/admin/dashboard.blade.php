@extends("admin.layouts.app")
@section("panel")
  
  <main class="main-body">
    <div class="container-fluid px-0 main-content">
      <div class="page-header">
        <h2>{{ $title }}</h2>
      </div>
      <div class="row g-4">
        <div class="col-12">
          <div class="row g-4">
            <div class="col-xxl-10 order-lg-1 order-xxl-0">
              <div class="row g-4">
                <div class="col-xxl-4 col-xl-4">
                  <div class="card feature-card">
                    <div class="card-header pb-0">
                      <div class="card-header-left">
                        <h4 class="card-title">{{ translate("SMS Statistics") }}</h4>
                      </div>
                      <div class="card-header-right">
                        <span class="fs-3 text-info">
                          <i class="ri-message-2-line"></i>
                        </span>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row g-3">
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-primary">
                                <i class="ri-message-2-line"></i>
                              </span>
                              <small>{{ translate("Total") }}</small>
                            </div>
                            <p class="feature-status-count">{{formatNumber($logs['sms']['all'])}}</p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-success">
                                <i class="ri-mail-check-line"></i>
                              </span>
                              <small>{{ translate("Success") }}</small>
                            </div>
                            <p class="feature-status-count">{{ formatNumber($logs['sms']['success']) }}</p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-warning">
                                <i class="ri-hourglass-fill"></i>
                              </span>
                              <small>{{ translate("Pending") }}</small>
                            </div>
                            <p class="feature-status-count">{{ formatNumber($logs['sms']['pending'],0) }}</p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-danger">
                                <i class="ri-mail-close-line"></i>
                              </span>
                              <small>{{ translate("Failed") }}</small>
                            </div>
                            <p class="feature-status-count">{{ formatNumber($logs["sms"]["failed"]) }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xxl-4 col-xl-4">
                  <div class="card feature-card">
                    <div class="card-header pb-0">
                      <div class="card-header-left">
                        <h4 class="card-title">{{ translate("Email Statistics") }}</h4>
                      </div>
                      <div class="card-header-right">
                        <span class="fs-3 text-danger">
                          <i class="ri-mail-line"></i>
                        </span>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row g-3">
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-primary">
                                <i class="ri-mail-line"></i>
                              </span>
                              <small>{{ translate("Total") }}</small>
                            </div>
                            
                            <p class="feature-status-count">{{ formatNumber($logs["email"]["all"]) }}</p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-success">
                                <i class="ri-mail-check-line"></i>
                              </span>
                              <small>{{ translate("Success") }}</small>
                            </div>
                            <p class="feature-status-count">{{ formatNumber($logs["email"]["success"]) }}</p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-warning">
                                <i class="ri-hourglass-fill"></i>
                              </span>
                              <small>{{ translate("Pending") }}</small>
                            </div>
                            <p class="feature-status-count">{{ formatNumber($logs["email"]["pending"],0) }}</p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-danger">
                                <i class="ri-mail-close-line"></i>
                              </span>
                              <small>{{ translate("Failed") }}</small>
                            </div>
                            <p class="feature-status-count">{{ formatNumber($logs["email"]["failed"]) }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xxl-4 col-xl-4">
                  <div class="card feature-card">
                    <div class="card-header pb-0">
                      <div class="card-header-left">
                        <h4 class="card-title">{{ translate("Whatsapp Statistics") }}</h4>
                      </div>
                      <div class="card-header-right">
                        <span class="fs-3 text-success">
                          <i class="ri-whatsapp-line"></i>
                        </span>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row g-3">
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-primary">
                                <i class="ri-whatsapp-line"></i>
                              </span>
                              <small>{{ translate("Total") }}</small>
                            </div>
                            <p class="feature-status-count">{{ formatNumber($logs["whats_app"]["all"]) }}</p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-success">
                                <i class="ri-mail-check-line"></i>
                              </span>
                              <small>{{ translate("Success") }}</small>
                            </div>
                            <p class="feature-status-count">{{ formatNumber($logs["whats_app"]["success"]) }}</p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-warning">
                                <i class="ri-hourglass-fill"></i>
                              </span>
                              <small>{{ translate("Pending") }}</small>
                            </div>
                            <p class="feature-status-count">{{ formatNumber($logs["whats_app"]["pending"],0) }}</p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-danger">
                                <i class="ri-mail-close-line"></i>
                              </span>
                              <small>{{ translate("Failed") }}</small>
                            </div>
                            <p class="feature-status-count">{{ formatNumber($logs["whats_app"]["failed"]) }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xxl-2">
              <div class="membership-card card-height-100">
                <div class="membership-content">
                  <h5>{{ translate("WhatsApp Cloud API") }}</h5>
                  <p> {{ translate("As an alternative solution to the WhatsApp Node Modules, you can try out our cloud api system") }} </p>
                </div>
                <a href="{{ route("admin.gateway.whatsapp.cloud.api") }}" class="membership-btn">{{ translate("Try now") }}</a>
                <span class="membership-shape">
                  <svg xmlns="http://www.w3.org/2000/svg" width="101" height="110" viewBox="0 0 101 110" fill="none">
                    <circle cx="99.6525" cy="127.091" r="42.5448" transform="rotate(-64.4926 99.6525 127.091)" fill="{{ site_settings('trinary_color') }}" fill-opacity="0.4" />
                    <circle cx="92.0618" cy="89.32" r="67" transform="rotate(-64.4926 92.0618 89.32)" fill="{{ site_settings('trinary_color') }}" fill-opacity="0.4" />
                  </svg>
                </span>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xxl-4 col-xl-5">
          <div class="card card-height-100">
            <div class="card-header">
              <h4 class="card-title">{{ translate("Application Usage") }}</h4>
            </div>
            <div class="card-body">
              <div id="application_usage" 
                   class="apex-charts" 
                   data-name='["application_usage"]'
                   data-sms-heading="SMS"
                   {{-- data-sms-color="#0D7FD1" --}}
                   data-sms-color="{{ site_settings("primary_color") }}"
                   data-sms="{{ $logs["sms"]["all"] }}"
                   data-whatsapp-heading="WhatsApp"
                   {{-- data-whatsapp-color="#195458" --}}
                   data-whatsapp-color="{{ site_settings("secondary_color") }}"
                   data-whatsapp="{{ $logs["whats_app"]["all"] }}"
                   data-email-heading="Email"
                   {{-- data-email-color="#32B586" --}}
                   data-email-color="{{ site_settings("trinary_color") }}"
                   data-email="{{ $logs["email"]["all"] }}">
              </div>
            </div>
          </div>
        </div>
        <div class="col-xxl-8 col-xl-7">
          <div class="card card-height-100">
            <div class="card-header pb-0">
              <div class="card-header-left">
                <h4 class="card-title">{{ translate("Subscribptions") }}</h4>
              </div>
            </div>
            <div class="card-body">
             
              <div id="subscription-chart" 
                   class="apex-charts"
                   data-chartData="{{ json_encode($totalUser) }}"
                   data-tool-tip-theme="{{ site_settings("theme_mode") == \App\Enums\StatusEnum::TRUE->status() ? 'light' : 'dark' }}"
                   data-legend-theme="{{ site_settings("theme_mode") == \App\Enums\StatusEnum::TRUE->status() ? '#000000a2' : '#ffffffa9' }}">
              </div>
            </div>
          </div>
        </div>

       
        <div class="col-xxl-6">
          <div class="card">
            <div class="card-header">
              <div class="card-header-left">
                <h4 class="card-title">{{ translate("New Users") }}</h4>
              </div>
            </div>
            <div class="card-body px-0 pt-0">
              <div class="table-container">
                <div class="default_table">
                  <table>
                    <thead>
                      <tr>
                        <th scope="col">{{ translate("Name") }}</th>
                        <th scope="col">{{ translate("Email/Phone") }}</th>
                        <th scope="col">{{ translate("Status") }}</th>
                        <th scope="col">{{ translate("Joined At") }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($customers as $customer) 
                        <tr>
                          <td>
                            <p class="text-dark fw-medium">{{ $customer?->name }}</p>
                          </td>
                          <td>
                            <a href="mailto:{{ $customer?->email }}" class="text-dark">{{ $customer?->email }}</a>
                          </td>
                          <td>
                            <span class="i-badge dot {{ $customer->status == \App\Enums\StatusEnum::TRUE->status() ? 'success' : 'danger' }}-soft pill">{{ $customer->status == \App\Enums\StatusEnum::TRUE->status() ? translate("Active") : translate("Banned") }}</span>
                          </td>
                          <td>
                            <span>{{ $customer?->created_at->diffForHumans() }}</span>
                            <p>{{ $customer?->created_at->toDayDateTimeString() }}</p>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xxl-6">
          <div class="card">
            <div class="card-header">
              <div class="card-header-left">
                <h4 class="card-title">{{ translate("Latest Payment Log") }}</h4>
              </div>
            </div>
            <div class="card-body px-0 pt-0">
              <div class="table-container">
                <div class="default_table">
                  <table>
                    <thead>
                      <tr>
                        <th scope="col">{{ translate("Customer") }}</th>
                        <th scope="col">{{ translate("Payment Gateway") }}</th>
                        <th scope="col">{{ translate("Amount") }}</th>
                        <th scope="col">{{ translate("TrxID") }}</th>
                        <th scope="col">{{ translate("Date/Time") }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($paymentLogs as $paymentLog)
                        <tr>
                          <td>
                            <p class="text-dark fw-medium">{{ $paymentLog?->user?->name }}</p>
                          </td>
                          <td>
                            <span>{{ $paymentLog->paymentGateway ? $paymentLog->paymentGateway->name : translate("N\A") }}</span>
                          </td>
                          <td>
                            <span class="text-dark fw-semibold">{{shortAmount(@$paymentLog->amount)}} {{ $paymentLog->paymentGateway ? $paymentLog->paymentGateway->currency_code : translate("N\A") }}</span>
                          </td>
                          <td>
                            <p>{{$paymentLog->trx_number}}</p>
                            @php echo payment_status($paymentLog->status)  @endphp
                          </td>
                          <td>
                            <span>{{ $paymentLog?->created_at->diffForHumans() }}</span>
                            <p> {{ $customer?->created_at->toDayDateTimeString() }}</p>
                          </p>
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
        </div>
      </div>
    </div>
  </main>

@endsection
