@if($items)
    @foreach ($items as $value)
        {{$value->created_at}} [{{$value->ip}}] [{{$value->action}}]  {{$value->request_input}} , {{$value->response_output}}
        <br>
        <br>
    @endforeach
@else
    There are no records
@endif
