
@extends('layouts.app')

@section('title') All Branches @endsection

@section('link')
<link href="{{ URL::asset('css/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('content')
<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
    <div id="page-head">

        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <h1 class="page-header text-overflow">Branch</h1>
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->


        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
          <li>
              <i class="fa fa-home"></i><a href="{{route('dashboard')}}"> Dashboard</a>
          </li>
            <li class="active">All</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->

    </div>


    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">



        <!-- Basic Data Tables -->
        <!--===================================================-->
        <div class="panel" style="overflow:scroll; background-color: #e8ddd3;">
            <div class="panel-heading">
                <h3 class="panel-title">List of All Branches</h3>
            </div>
            <div class="panel-body">
                <form id="users-form" onsubmit="return false;" >
                  <table id="users-table" class="table table-striped table-bordered" cellpadding="10" cellspacing="0" width="100%" >
                    <thead>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                  <select id="action" name="action">
                    <option>with selected</option>
                    <option value="delete">delete</option>
                  </select>
                  <input class="btn-danger" id="apply" type="button" value="apply">
                </form>
            </div>
        </div>
        <!--===================================================-->
        <!-- End Striped Table -->


    </div>
    <!--===================================================-->
    <!--End page content-->

</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
@endsection

@section('js')
<!--DataTables [ OPTIONAL ]-->
<script src="{{ URL::asset('plugins/datatables/media/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/media/js/dataTables.bootstrap.js') }}"></script>
<!--<script src="{{ URL::asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>-->

<!--DataTables Sample [ SAMPLE ]-->
<!--<script src="{{ URL::asset('js/demo/tables-datatables.js') }}"></script>-->

<script src="{{ URL::asset('plugins/datatables/dataTables.semanticui.min.js') }}"></script>

<script src="{{ URL::asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
<!--<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>-->


<script src="{{ URL::asset('plugins/datatables/buttons.semanticui.min.js') }}"></script>

<script src="{{ URL::asset('plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('plugins/datatables/buttons.print.min.js') }}"></script>

<script src="{{ URL::asset('plugins/datatables/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('js/functions.js') }}"></script>
<script src="{{ URL::asset('js/sweetalert.min.js') }}"></script>
<script>
function strim(text){
  return text.trim()
}
$(document).ready(function () {
  var i = 1
  users_table = $('#users-table').DataTable({
      processing: true,
      serverSide: true,
      "columnDefs": [
        { "orderable": false, "targets": 0 }
      ],
      oLanguage: {sProcessing: divLoader()},
      ajax: "{{route('branches')}}",
      columns: [
          { title: '<input id="select-all" type="checkbox" /> Select all', data: 'id', render : ( data ) => (`<input type="checkbox" name="member[]" value="${data}" />`)
          , name: 'id' },
          { title: "S/N", render: () => (i++), name: 'id' },
          { title: "Branch Name", data: 'branchname', name: 'branchname'},
          { title: "Address  ", data: 'address', name: 'address' },
          { title: "Branch Code", data: 'branchcode', name: 'branchcode', render: (code) => (`
            <div class="btn-group">
              <h5 style="background-color:pink; padding:5pt;" class="text-center">${code}</h5>
            </div>
            `)
          },
          // { title: "phone Number", data: 'phone', name: 'phone' },
          { title: "Email", data: 'email', name: 'email' },
          { title: "Branch Role", data: 'isadmin', name: 'isadmin', render: (bool) => (`
            ${(bool === 'true') ? '<strong>HeadQuaters</strong>' : 'Branch Church'}
            `)
          },
          { title: "State   .", data: 'state', name: 'state' },
          { title: "City   .", data: 'city', name: 'city' },
          { title: "Country   .", data: 'country', name: 'country' },
          { title: "Currency    ", data: 'currency_symbol', name: 'currency_symbol' },
          { title: "Action", data: 'id', name: 'action', render: (id) => (`
            <div class="btn-group">
              <button style="background-color:orange" class="btn text-light edit" data-id="${id}"><i class="fa fa-edit"></i></button>
              <a style="background-color:green" class="btn text-light" disabled href="#"><i class="fa fa-eye"></i></a>
              <a href="./branches/${id}/destroy" id="./branches/${id}/destroy" onclick="del(this);" class="btn btn-danger" />
                <span>delete<i class="fa fa-trash"></i></span>
              </a>
              <!--a id="${id}" style="background-color:#8c0e0e" class="d-member btn text-light"><i class="fa fa-trash"></i></a-->
            </div>
            `)
          },
      ],
      dom: 'Bfrtip',
      lengthChange: false,
      buttons: ['copy', 'excel', 'pdf', 'colvis']
  });

  // members edit table row
  $('#users-table').on( 'click', 'tbody tr td .edit ', function (e) {
    id = $(this).attr('data-id')
    let i = 0;
    columns = $(this).parent().closest('tr').find('td').each(function(){
      if (i == 2) {
        $(this).html(`
          <div class="col-md-9">
            <input type="text" value="${strim($(this).text())}" class="form-control" name="branchname" placeholder="Enter your email" required="">
          </div>
        `)
      } else if (i == 3) {
        $(this).html(`
          <div class="col-md-9">
            <textarea id="demo-textarea-input" value="${strim($(this).text())}" name="address" rows="5" class="form-control" placeholder="Address I">${$(this).text()}</textarea>
          </div>
        `)
      } else if (i == 4) {
        $(this).html(`
          <div class="col-md-9">
            <input type="text" value="${strim($(this).text())}" class="form-control" name="branchcode" placeholder="Enter the code" required="">
          </div>
        `)
      } else if (i == 5) {
          $(this).html(`
            <div class="col-md-9">
              <input type="email" id="demo-email-input" value="${strim($(this).text())}" class="form-control" name="email" placeholder="Enter your email" required="">
            </div>
          `)
      } else if (i == 6) {
          $(this).html(`
            <div class="col-md-9">
              <input id="demo-form-radio" class="magic-radio" value="true" type="radio" name="isadmin" ${(strim($(this).text()) === 'HeadQuaters') ? 'checked=""' : ''}>
              <label for="demo-form-radio">HeadQuaters</label>
              <input id="demo-form-radio-2" class="magic-radio" value="false" type="radio" name="isadmin" ${(strim($(this).text()) === 'Branch Church') ? 'checked=""' : ''}>
              <label for="demo-form-radio-2">Branch Church</label>
            </div>
          `)
      } else if (i == 7) {
          $(this).html(`
            <div class="col-md-9">
              <input type="text" class="form-control" value="${strim($(this).text())}" name="state" placeholder="Enter bracnh state" required>
            </div>
          `)
      } else if (i == 8) {
          $(this).html(`
            <div class="col-md-9">
              <input type="text" class="form-control" value="${strim($(this).text())}" name="city" placeholder="Enter bracnh city" required>
            </div>
          `)
      } else if (i == 9) {
          $(this).html(`
            <div class="col-md-9">
              <input type="text" class="form-control" value="${strim($(this).text())}" name="country" placeholder="Enter bracnh country" required>
            </div>
          `)
      } else if (i == 10) {
          $(this).html(`
            <div class="col-md-9">
              <input type="text" class="form-control" value="${strim($(this).text())}" name="currency" placeholder="Enter bracnh currency" required>
            </div>
          `)
      } else if (i == 11) {
          $(this).html(`
            <input type="hidden" value="${id}" name="id" />
            <button type="button" class="restore btn btn-sm btn-warning" style="float: left;">Cancel</button><div>
            <button type="submit" class="save btn btn-sm btn-success" style="float: right;">Save</button>
            @csrf
          `)
      }
      i++
    })
  });

  //for save user
  // $('#users-table').on( 'click', 'tbody tr td .save', function (e) {
  $('#users-form').on('submit', function (e) {
    e.preventDefault()
    data = {}
    data = $(this).serializeArray()
    url = "{{route('branch.update')}}"
      poster({url,data}, (res) => {users_table.ajax.reload(null, false); console.log(res);})
  })

});

function del(d){
    var confirmed = confirm('confirm to delete');
    console.log(confirmed);
    console.log(d);
    if(confirmed){
        var id = $(d).attr('id');
        console.log(id);
        $.ajax({
            url: id,
        }).done(function(){
            location.reload();
        });
    }//{{route("branch.destroy",' + id + ')}}
}
</script>
@endsection
