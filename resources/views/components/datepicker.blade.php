<input class="date form-control" type="text">


@section('js')

<script type="text/javascript">

    $('.date').datepicker({  

       format: 'mm-dd-yyyy'

     });  

</script>  

@endsection