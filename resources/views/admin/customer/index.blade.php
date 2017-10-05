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
                    <table id="example1" class="table">
                        @if($items->count())
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Company Name</th>
                                <th>Company Number</th>
                                <th>Company Address</th>
                                <th>Company web site</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone type</th>
                                <th>Phone Number</th>
                                <th>Email address</th>
                                <th>Company Secret Key</th>
                                <th>License id</th>
                                <th>License start date</th>
                                <th>License End Date</th>
                                <th>License Valid</th>
                                <th>License type</th>
                                <th>Maximum licensed users</th>
                                <th>Billing address street</th>
                                <th>Billing address suite</th>
                                <th>Billing address city</th>
                                <th>Billing address state</th>
                                <th>Billing address zip code</th>
                                <th width="150" >Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $value)
                            <tr>
                                <td>{{$value->id }}</td>
                                <td>{{$value->company_name or '' }}</td>
                                <td>{{$value->company_number}}</td>
                                <td>{{$value->company_address or ''}}</td>
                                <td>{{$value->company_web_site}}</td>
                                <td>{{$value->contact_first_name}}</td>
                                <td>{{$value->contact_last_name}}</td>
                                <td>{{ $contact_phone_type[$value->contact_phone_type] or ''}}</td>
                                <td>{{$value->contact_phone_number}}</td>
                                <td>{{$value->contact_email_address or ''}}</td>
                                <td>{{$value->company_secret_key}}</td>
                                <td>{{$value->license_id or ''}}</td>
                                <td>{{$value->license_start_date or ''}}</td>
                                <td>{{$value->license_end_date}}</td>
                                <td> <label class="label {{( $value->license_valid)?'label-success':'label-danger' }}"></label> {{($value->license_valid)?'Valid':'Invalid'}}</td>
                                <td>{{ $license_type[$value->license_type] or ''}}</td>
                                <td>{{$value->maximum_licensed_users or ''}}</td>
                                <td>{{$value->billing_address_street or ''}}</td>
                                <td>{{$value->billing_address_suite or ''}}</td>
                                <td>{{$value->billing_address_city or ''}}</td>
                                <td>{{$value->billing_address_state or ''}}</td>
                                <td>{{$value->billing_address_zip_code or ''}}</td>
                                
                                <td class="no-wrap">
                                @permission('user-edit')
                                    {!! Form::open(array('url' => $ctrl_url.'/'.$value->id,'method'=>'delete','class'=>'form-inline')) !!}    
                                         <a href="{{url($ctrl_url.'/'.$value->id.'/edit')}}" class="btn btn-small btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
                                         <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
                                    {!! Form::close() !!}
                                @endpermission
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <tbody>
                            <tr>
                                <th>There are no records</th>
                            </tr>
                        </tbody>
                        @endif
                    </table>
                </div>
                {!! str_replace('/?', '?', $items->appends(Request::except(array('page')))->render()) !!}
            </div>
        </div>
    </div>
</div>
@endsection