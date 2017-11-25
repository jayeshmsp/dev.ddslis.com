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
                            <a class="btn btn-sm btn-primary" href="user/create">
                                Add User
                            </a>
                        </h3>
                    </div>
                @endpermission 
                <div class="table-responsive">
                    <table id="users" class="table table-striped table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>User ID #</th>
                                <th>Company</th>
                                <th>Fisrt Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Contact Id</th>
                                <th>Application Status</th>
                                {{-- <th>User Type</th> --}}
                                <th>Platform</th>
                                <th>Last Login</th>
                                <th>Login Type</th>
                                <th>Login IP</th>
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
<div id="resetPassModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change password</h4>
      </div>
         
      <div class="modal-body">
        <form name="reset_pass_form" id="reset_pass_form">
            <div class="form-group">
              <input type="hidden" id="resetPassID" name="user_id" value="">  
              <label for="pwd">Enter new password:</label>
              <input type="password" name="password" class="form-control" id="pwd">
            </div>
            <div class="form-group">
                <span class="pass-error error text-danger" style="display: none" ></span>
                <span class="pass-success success text-success" style="display: none" ></span>
            </div>
        </form>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="savePass" >Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
    $(document).ready(function(){
        oTable = $('#users').DataTable({
            responsive: true,
            //"fixedHeader": true,
            "processing": true,
            "serverSide": true,
            "ajax": "{{ url('user/getDatas') }}",
            "columns": [
                {data:'id',id:'id'},
                {data:'company_name',id:'company_name'},
                {data:'first_name',id:'first_name'},
                {data:'last_name',id:'last_name'},
                {data:'email',id:'email'},
                {data:'username',id:'username'},
                {data:'contact_id',id:'contact_id'},
                {data:'status',id:'status'},
                /*{data:'role_id',id:'role_id'},*/
                {data:'platform',id:'platform'},
                {data:'last_login',id:'last_login'},
                {data:'provider',id:'provider'},
                {data:'login_ip',id:'login_ip'},
                {data:'action',id:'action',orderable: false, searchable: false},
            ]
        });


        var url = "{{url('/change-password')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('body').on('click','.reset-pass-modal',function(){
           $("#resetPassID").val($(this).attr('data-userId'));
           $("#resetPassModal").modal("show"); 
        });
        
        
        $("#savePass").click(function(){
            var formData = $("#reset_pass_form").serialize();
            $.post(url, formData, function(result){
                $(".pass-success").show();
                $(".pass-success").text(result.msg);
                setTimeout(function(){
                    $(".pass-success").hide();
                    $("#resetPassModal").modal('hide');
                    $('#reset_pass_form')[0].reset();
                },2000);
            }).fail(function(result) {
                $(".pass-error").show();
                var result = $.parseJSON(result.responseText);
                console.log(result);
                $(".pass-error").text(result.msg);
                setTimeout(function(){
                    $(".pass-error").hide();
                },3000);
            });
        })
    });
</script>

@endsection