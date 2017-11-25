@extends('layouts.app')
@section('content')
<div class="container-fluid company-list">
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                {{-- <div class="pull-left">
                    @include('users.user.partials.search')
                </div> --}}
                @permission('user-add')
                    <div>
                        <h3 class="box-title">
                            <a class="btn btn-sm btn-primary" href="{{$ctrl_url}}/create">
                                Add Company
                            </a>
                        </h3>
                    </div>
                @endpermission
                <div class="table-responsive">
                    <table id="customers" class="table table-striped table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Company Url</th>
                                <th>Company Address</th>
                                <th>web site</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone type</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Company Secret Key</th>
                                <th>License id</th>
                                <th>License start date</th>
                                <th>License End Date</th>
                                <th>License Valid</th>
                                <th>License type</th>
                                <th>Maximum licensed users</th>
                                <th>Street</th>
                                <th>Suite</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Zip code</th>
                                <th>Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')

<script>
    $(document).ready(function(){
        oTable = $('#customers').DataTable({
            responsive: true,
            /*"fixedHeader": true,*/
            "processing": true,
            "serverSide": true,
            "ajax": "{{ url($ctrl_url.'/getDatas') }}",
            "columns": [
                {data:'company_number',id:'company_number'},
                {data:'company_name',id:'company_name'},
                {data:'company_url',id:'company_url',orderable: false, searchable: false},
                {data:'company_address',id:'company_address'},
                {data:'company_web_site',id:'company_web_site'},
                {data:'contact_first_name',id:'contact_first_name'},
                {data:'contact_last_name',id:'contact_last_name'},
                {data:'contact_phone_type',id:'contact_phone_type'},
                {data:'contact_phone_number',id:'contact_phone_number'},
                {data:'contact_email_address',id:'contact_email_address'},
                {data:'company_secret_key',id:'company_secret_key'},
                {data:'license_id',id:'license_id'},
                {data:'license_start_date',id:'license_start_date'},
                {data:'license_end_date',id:'license_end_date'},
                {data:'license_valid',id:'license_valid'},
                {data:'license_type',id:'license_type'},
                {data:'maximum_licensed_users',id:'maximum_licensed_users'},
                {data:'billing_address_street',id:'billing_address_street'},
                {data:'billing_address_suite',id:'billing_address_suite'},
                {data:'billing_address_city',id:'billing_address_city'},
                {data:'billing_address_state',id:'billing_address_state'},
                {data:'billing_address_zip_code',id:'billing_address_zip_code'},
                {data:'action',id:'action',orderable: false, searchable: false}
            ]
        });
    });
</script>

@endsection
