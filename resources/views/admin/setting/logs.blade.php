@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-bordered table-condensed">
                        @if($items->count())
                        <thead>
                            <tr>
                                <th width="10">Log #</th>
                                <th width="50">Action</th>
                                <th width="100">Affected By</th>
                                <th width="10">Record Id</th>
                                <th width="100">Date Time</th>
                                <th> IP </th>
                                <th width="50">Model</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $value)
                            <?php 
                                $prop = json_decode($value->properties);
                            ?>
                            <tr>
                                <td width="10">{{$srno++ }}</td>
                                
                                <td width="50">{{$value->description}}</td>
                                <td width="100">{{isset($users[$value->causer_id])?$users[$value->causer_id]:''}}</td>
                                <td width="10">{{$value->subject_id}}</td>
                                <td width="100">{{$value->created_at}}</td>
                                <td width="50">{{isset($prop->remote_ip)?$prop->remote_ip:''}}</td>
                                <td width="50">{{$value->subject_type}}</td>
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