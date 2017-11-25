@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ url('logs/view') }}" target="_blank" class="btn btn-success">View Raw Logs</a>
            <div class="white-box">
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-bordered table-condensed">
                        @if($items)
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="5%">Action</th>
                                <th width="45%">Input</th>
                                <th width="30%">Output</th>
                                <th width="10%">Ip</th>
                                <th width="10%">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $value)
                            <tr>
                                <td width="5%">{{$srno++ }}</td>
                                <td width="45%">{{$value->action}}</td>
                                <td width="45%">{{$value->request_input}}</td>
                                <td width="30%">{{$value->response_output}}</td>
                                <td width="10%">{{$value->ip}}</td>
                                <td width="10%">{{$value->created_at}}</td>
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