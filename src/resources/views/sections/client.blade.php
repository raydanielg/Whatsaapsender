<div class="clients py-5">
    <div class="container">
        <div class="row gx-0 gy-5 align-items-center">
            <div class="col-xl-5 col-lg-4">
                <div class="client-left">
                    <p>{{getArrayValue(@$client_content->section_value, 'sub_heading')}}</p>

                    <h4 class="section-title">{{getArrayValue(@$client_content->section_value, 'heading')}}</h4>
                </div>
            </div>

            <div class="col-xl-7 col-lg-8">
                <div class="client-right">
                    <div class="client-right-item">
                        <h3>{{$users}}+</h3>
                        <p>{{translate("Worldwide Customers") }}</p>
                    </div>

                    <div class="client-right-item">
                        <h3>{{$subscribed_users}}+</h3>
                        <p>{{translate("Worldwide Subscribed Customers")}}</p>
                    </div>

                    <div class="client-right-item">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>