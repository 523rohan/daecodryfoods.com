<!-- rohan -->
 <!-- Offcanvas -->
 <div class="offcanvas offcanvas-end" id="offcanvasPhonepe" tabindex="-1">
     <div class="offcanvas-header border-bottom">
         <h5 class="offcanvas-title">{{ localize('PhonePe Configuration') }}</h5>
         <span
             class="btn btn-outline-danger rounded-circle btn-icon d-inline-flex align-items-center justify-content-center"
             data-bs-dismiss="offcanvas">
             <i data-feather="x"></i>
         </span>
     </div>
     <div class="offcanvas-body" data-simplebar>
         <form action="{{ route('payment-gateway-setting.store') }}" method="POST" enctype="multipart/form-data">
             @csrf
             <input type="hidden" name="payment_method" value="phonepe">
             <input type="hidden" value="1" name="is_virtual">
             <div class="mb-3">
                 <label for="PHONEPE_MERCHANT_ID" class="form-label">{{ localize('Merchant ID') }}</label>
                 <input type="text" id="PHONEPE_MERCHANT_ID" name="types[PHONEPE_MERCHANT_ID]" class="form-control"
                     value="{{ paymentGatewayValue('phonepe', 'PHONEPE_MERCHANT_ID') }}">
             </div>
             <div class="mb-3">
                 <label for="PHONEPE_SALT_KEY" class="form-label">{{ localize('Salt Key') }}</label>
                 <input type="text" id="PHONEPE_SALT_KEY" name="types[PHONEPE_SALT_KEY]" class="form-control"
                     value="{{ paymentGatewayValue('phonepe', 'PHONEPE_SALT_KEY') }}">
             </div>
             <div class="mb-3">
                 <label for="PHONEPE_SALT_INDEX" class="form-label">{{ localize('Salt Index') }}</label>
                 <input type="text" id="PHONEPE_SALT_INDEX" name="types[PHONEPE_SALT_INDEX]" class="form-control"
                     value="{{ paymentGatewayValue('phonepe', 'PHONEPE_SALT_INDEX') }}">
             </div>
             <div class="mb-3">
                 <label for="PHONEPE_CLIENT_ID" class="form-label">{{ localize('Client ID') }}</label>
                 <input type="text" id="PHONEPE_CLIENT_ID" name="types[PHONEPE_CLIENT_ID]" class="form-control"
                     value="{{ paymentGatewayValue('phonepe', 'PHONEPE_CLIENT_ID') }}">
             </div>
             <div class="mb-3">
                 <label for="PHONEPE_CLIENT_SECRET" class="form-label">{{ localize('Client Secret') }}</label>
                 <input type="text" id="PHONEPE_CLIENT_SECRET" name="types[PHONEPE_CLIENT_SECRET]" class="form-control"
                     value="{{ paymentGatewayValue('phonepe', 'PHONEPE_CLIENT_SECRET') }}">
             </div>
             <div class="mb-3">
                 <label for="PHONEPE_CLIENT_VERSION" class="form-label">{{ localize('Client Version') }}</label>
                 <input type="text" id="PHONEPE_CLIENT_VERSION" name="types[PHONEPE_CLIENT_VERSION]" class="form-control"
                     value="{{ paymentGatewayValue('phonepe', 'PHONEPE_CLIENT_VERSION') }}">
             </div>
             <div class="mb-3">
                 <label class="form-label">{{ localize('Sandbox Mode') }}</label>
                 <select id="sandbox" class="form-control select2" name="sandbox" data-toggle="select2">
                     <option value="1" {{ paymentGateway('phonepe')->sandbox == '1' ? 'selected' : '' }}>
                         {{ localize('Enable') }}</option>
                     <option value="0" {{ paymentGateway('phonepe')->sandbox == '0' ? 'selected' : '' }}>
                         {{ localize('Disable') }}</option>
                 </select>
             </div>
             <div class="mb-3">
                 <label class="form-label">{{ localize('Enable PhonePe') }}</label>
                 <select id="enable_phonepe" class="form-control select2" name="is_active" data-toggle="select2">
                     <option value="0" {{ paymentGateway('phonepe')->is_active == '0' ? 'selected' : '' }}>
                         {{ localize('Disable') }}</option>
                     <option value="1" {{ paymentGateway('phonepe')->is_active == '1' ? 'selected' : '' }}>
                         {{ localize('Enable') }}</option>
                 </select>
             </div>
             <div class="mb-3">
                 <button class="btn btn-primary" type="submit">
                     <i data-feather="save" class="me-1"></i> {{ localize('Save Configuration') }}
                 </button>
             </div>
         </form>
     </div>
 </div>
