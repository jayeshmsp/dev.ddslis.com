<div class="form-group {{ $errors->has('company_number') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Company Number</label>
    <div class="col-sm-6">
        <label> {{ $item->company_number or $company_number+1 }} </label>
        {!! Form::hidden('company_number',(isset($item->company_number) && !empty($item->company_number)) ?$item->company_number:$company_number+1 ) !!}
        {!! $errors->first('company_number', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('company_secret_key') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Company Secret Key</label>
    <div class="col-sm-6">
        {!! Form::text('company_secret_key',old('company_secret_key'),['id'=>'company_secret_key','class'=>'form-control']) !!} 
        {!! $errors->first('company_secret_key', '<span class="help-block">:message</span>') !!}
        <br/>
        <button id="generate-secret" class="btn btn-success generate-secret-key">Generate Secret Key</button>
    </div>
</div>

<div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Company Name</label>
    <div class="col-sm-6">
        {!! Form::text('company_name',old('company_name'),['id'=>'company_name','class'=>'form-control']) !!} 
        {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('company_address') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Company Address</label>
    <div class="col-sm-6">
        {!! Form::textarea('company_address',old('company_address'),['id'=>'company_address','class'=>'form-control']) !!} 
        {!! $errors->first('company_address', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('company_web_site') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Company Web Site</label>
    <div class="col-sm-6">
        {!! Form::text('company_web_site',old('company_web_site'),['id'=>'company_web_site','class'=>'form-control']) !!} 
        {!! $errors->first('company_web_site', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('billing_address_street') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Billing Address Street</label>
    <div class="col-sm-6">
        {!! Form::text('billing_address_street',old('billing_address_street'),['id'=>'billing_address_street','class'=>'form-control']) !!} 
        {!! $errors->first('billing_address_street', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('billing_address_suite') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Billing Address Suite</label>
    <div class="col-sm-6">
        {!! Form::text('billing_address_suite',old('billing_address_suite'),['id'=>'billing_address_suite','class'=>'form-control']) !!} 
        {!! $errors->first('billing_address_suite', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('billing_address_city') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Billing Address City</label>
    <div class="col-sm-6">
        {!! Form::text('billing_address_city',old('billing_address_city'),['id'=>'billing_address_city','class'=>'form-control']) !!} 
        {!! $errors->first('billing_address_city', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('billing_address_state') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Billing address State</label>
    <div class="col-sm-6">
        {!! Form::text('billing_address_state',old('billing_address_state'),['id'=>'billing_address_state','class'=>'form-control','maxlength'=>'2']) !!} 
        {!! $errors->first('billing_address_state', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('billing_address_zip_code') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Billing Address Zip-code</label>
    <div class="col-sm-6">
        {!! Form::text('billing_address_zip_code',old('billing_address_zip_code'),['id'=>'billing_address_zip_code','class'=>'form-control','oninput'=>"this.value=this.value.replace(/[^0-9]/g,'');",'maxlength'=>'5']) !!} 
        {!! $errors->first('billing_address_zip_code', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('contact_first_name') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Contact First Name</label>
    <div class="col-sm-6">
        {!! Form::text('contact_first_name',old('contact_first_name'),['id'=>'contact_first_name','class'=>'form-control']) !!} 
        {!! $errors->first('contact_first_name', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('contact_last_name') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Contact Last Name</label>
    <div class="col-sm-6">
        {!! Form::text('contact_last_name',old('contact_last_name'),['id'=>'contact_last_name','class'=>'form-control']) !!} 
        {!! $errors->first('contact_last_name', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('contact_email_address') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Contact Email Address</label>
    <div class="col-sm-6">
        {!! Form::text('contact_email_address',old('contact_email_address'),['id'=>'contact_email_address','class'=>'form-control']) !!} 
        {!! $errors->first('contact_email_address', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('contact_phone_number') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Contact Phone Number</label>
    <div class="col-sm-2">
        {!! Form::select('contact_phone_type',$contact_phone_type,old('contact_phone_type'),array('class'=>'form-control','id'=>'contact_phone_type')) !!}
        {!! $errors->first('contact_phone_type', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="col-sm-4">
        {!! Form::text('contact_phone_number',old('contact_phone_number'),['id'=>'contact_phone_number','class'=>'form-control']) !!} 
        {!! $errors->first('contact_phone_number', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('license_id') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">License ID</label>
    <div class="col-sm-6">
        {!! Form::text('license_id',old('license_id'),['id'=>'license_id','class'=>'form-control']) !!} 
        {!! $errors->first('license_id', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('license_type') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">License Type</label>
    <div class="col-sm-6">
        {!! Form::select('license_type',$license_type,old('license_type'),array('class'=>'form-control','id'=>'license_type')) !!}
        {!! $errors->first('license_type', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('license_start_date') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">License Start Date</label>
    <div class="col-sm-6">
        {!! Form::text('license_start_date',old('license_start_date'),array('class'=>'form-control mydatepicker','id'=>'license_start_date')) !!}
        {!! $errors->first('license_start_date', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('license_end_date') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">License End Date</label>
    <div class="col-sm-6">
        {!! Form::text('license_end_date',old('license_end_date'),array('class'=>'form-control mydatepicker','id'=>'license_end_date')) !!}
        {!! $errors->first('license_end_date', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('maximum_licensed_users') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">Maximum Licensed Users</label>
    <div class="col-sm-6">
        {!! Form::select('maximum_licensed_users',$maximum_licensed_users,old('maximum_licensed_users'),array('class'=>'form-control','id'=>'maximum_licensed_users')) !!}
        {!! $errors->first('maximum_licensed_users', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('license_valid') ? 'has-error' : '' }}">
    <label class="col-sm-3 control-label">License Valid</label>
    <div class="col-sm-6">
        {!! Form::select('license_valid',['1'=>'Valid','0'=>'Invalid'],old('license_valid'),array('class'=>'form-control','id'=>'license_valid')) !!}
        {!! $errors->first('license_valid', '<span class="help-block">:message</span>') !!}
    </div>
</div>
@section('footer')
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.generate-secret-key').click(function(e){
                e.preventDefault();
                $.post('/customer/create-secret-key', function(result){
                    $('#company_secret_key').val(result);
                });
            });
        });
    </script>
@endsection