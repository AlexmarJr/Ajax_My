@extends('layout.main')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@section('content')

<div class="container" style="background-color: rgb(255, 255, 254); border: 2px solid wheat; border-radius: 25px; padding: 35px">
    <div>
        <button class="btn btn-success" onclick="open_modal()"><i class="fa fa-plus"></i> Add New</button>
    </div>
    <hr>
    <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Price</th>
                <th>Quant</th>
                <th>OBS</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($index as $value)
                <tr>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->type }}</td>
                    <td>{{ $value->price }}</td>
                    <td>{{ $value->quant }}</td>
                    <td>{{ $value->obs }}</td>
                    <td><a href="{{ route('show.product', $value->id) }}" class="btn btn-primary edit"><i class="fa fa-info-circle"></i></a> &nbsp <a href="{{ route('destroy.product', $value->id) }}" class="btn btn-danger delete"><i class="fa fa-trash"></i></a></td>
                </tr>
            @endforeach
    </table>


    <!-- MODAL PRINCIPAL-->
    <!-- Modal -->
    <input type="hidden" id="id" value="">
<form id="formRegister" onsubmit="post_data()">
    <input id="signup-token" name="_token" type="hidden" value="{{csrf_token()}}">
    <div class="modal fade" id="adding_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Adicionar Produto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <div class="row">
               
                <div class="col-6">
                    <label>Nome</label>
                    <input class="form-control" type="text" name="name" id="name" required>
                </div>
                <div class="col-6">
                    <label> Tipo </label>
                    <select class="form-control selectpicker " name="type" id="type" required>
                        <option selected>Selecione</option>
                        <option value="drink">Bebida</option>
                        <option value="food">Comida</option>
                    </select>
                </div>
                <div class="col-6">
                    <label>Preço</label>
                    <input class="form-control money" type="" name="text" id="price" required>
                </div>
                <div class="col-6">
                    <label>Quantidade</label>
                    <input class="form-control" type="number" name="quant" id="quant" required>
                </div>
                <div class="col-12">
                    <label>Observação</label>
                    <textarea class="form-control" name="obs" id="obs" rows="2"></textarea>
                </div>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" style="" id="add" class="btn btn-primary">Save</button>
            <button type="button" style="display: none" id="update" class="btn btn-primary update">Update</button>
            </div>
        </div>
        </div>
    </div>
</form>

<script>

    var _row = null;



    $(document).ready(function() {
    $('#example').DataTable({
        responsive: true,
        "bFilter": true,
        "bInfo": false,
    });
} );

function open_modal(){
    $('#name').val('');
    $('#type').val('');
    $('#price').val('');
    $('#quant').val('');
    $('#obs').val('');
    $('#add').css({ "display": 'block'});
    $('#update').css({ "display": 'none'});
    $('#adding_modal').modal('show');

}

$('select').selectpicker();
$('.money').mask('000.000.000.000.000.00', {reverse: true});

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

    function post_data() {
        var name = $('#name').val();
        var type = $('#type').val();
        var price = $('#price').val();
        var quant = $('#quant').val();
        var obs = $('#obs').val();

        var table = $('#example').DataTable();
    $.ajax({
        type: 'post',
        url: '/create/product',
        data: {
            'name': $('#name').val(),
            'type': $('#type').val(),
            'price': $('#price').val(),
            'quant': $('#quant').val(),
            'obs': $('#obs').val(),
        },
        success: function() {
        swal('Success', 'Produto Registrado');

        var t = $('#example').DataTable();

        var button = ('<a href="" class="btn btn-primary"><i class="fa fa-info-circle"></i> </a> &nbsp <a href="" class="btn btn-danger delete"><i class="fa fa-trash"></i> </a>')

        t.row.add( [
            name,
            type,
            price,
            quant,
            obs,  
            button,
        ] ).draw( false );
            $('#name').val('');
            $('#type').val('');
            $('#price').val('');
            $('#quant').val('');
            $('#obs').val('');
        $('#adding_modal').modal('hide');
        
        },

    });

}

$('.edit').on("click",function(e){   
    _row = $(this).closest('tr');
    e.preventDefault();  
     
  
    $.ajax({
        url: $(this).attr('href'),
        type: 'get',
        dataType: 'json',
        success: function(data){
            $('#name').val(data.name);
            $('#type').val(data.type);
            $('#price').val(data.price);
            $('#quant').val(data.quant);
            $('#obs').val(data.obs);

            $('#id').val(data.id);
            
            $('#add').css({ "display": 'none'});
            $('#update').css({ "display": 'block'});
            $('#adding_modal').modal('show');

            }
        });
    })

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


    $('.update').on("click",function(e){   
        
        var id =  $('#id').val();
        var name = $('#name').val();
        var type = $('#type').val();
        var price = $('#price').val();
        var quant = $('#quant').val();
        var obs = $('#obs').val();
        
        $.ajax({
        type: 'post',
        url: '/update/product',
        data: {
            'id':$('#id').val(),
            'name': $('#name').val(),
            'type': $('#type').val(),
            'price': $('#price').val(),
            'quant': $('#quant').val(),
            'obs': $('#obs').val(),
        },
        success: function() {
            
        swal('Success', 'Produto Alterado');
        

        var t = $('#example').DataTable();

        var button = ('<a href="" class="btn btn-primary"><i class="fa fa-info-circle"></i> </a> &nbsp <a href="" class="btn btn-danger"><i class="fa fa-trash"></i> </a>')
        
        t.row.add( [
            name,
            type,
            price,
            quant,
            obs,  
            button,
        ] ).draw( false );
            $('#name').val('');
            $('#type').val('');
            $('#price').val('');
            $('#quant').val('');
            $('#obs').val('');
        $('#adding_modal').modal('hide');
        (_row).remove();
        },

    });
    })

    $('.delete').on("click",function(e){   
        $(this).parents('tr').remove();
    e.preventDefault();  
  
  
    $.ajax({
        url: $(this).attr('href'),
        type: 'get',
        dataType: 'json',
        success: function(data){
            
            swal("Alert", "Item Apagado");
            
            }
        });
    })
</script>
@endsection