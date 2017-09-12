@extends('layouts.app')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">

                <!-- form start -->
                {!! Form::model($item, array('method' => 'PATCH', 'url' => $ctrl_url.'/'.$item['id'],'class'=>'form-horizontal','id'=>'setting-form')) !!}
                    @include($view_path.'.partials.form')

                    <div class="form-group m-b-0">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" name="save" class="btn btn-info waves-effect waves-light m-t-10">Update</button>
                            <a class="btn btn-danger waves-effect waves-light m-t-10" href="{{ url($ctrl_url) }}">Cancel</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
            </div>
        </div>
    </div>
</section>
@stop
@section('footer') 
    <div id="addQuestionModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add security question form</h4>
      </div>
         
      <div class="modal-body">
        <form name="question_form" id="question_form">
            <div class="form-group">
              <label for="question">Enter question:</label>
              <input type="text" name="question" class="form-control" id="question">
            </div>
            <div class="form-group">
                <span class="question-error error text-danger" style="display: none" ></span>
                <span class="question-success success text-success" style="display: none" ></span>
            </div>
        </form>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveQue" >Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
    $(document).ready(function(){
        var url = "{{url('/add-question')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".add-security-question-btn").click(function(){
           $("#addQuestionModal").modal("show");
        });
        
        $("#saveQue").click(function(){
            var formData = $("#question_form").serialize();
            $.post(url, formData, function(result){
                $(".question-success").show();
                $(".question-success").text(result.msg);
                $(".question-div").append(result.question);
                setTimeout(function(){
                    $(".question-success").hide();
                    $("#addQuestionModal").modal('hide');
                    $('#question_form')[0].reset();
                },2000);
            }).fail(function(result) {
                $(".question-error").show();
                var result = $.parseJSON(result.responseText);
                console.log(result);
                $(".question-error").text(result.msg);
                setTimeout(function(){
                    $(".question-error").hide();
                },3000);
            });
        })
    });
</script>
@endsection