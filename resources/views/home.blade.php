@extends('layouts.app')

@section('content')
<style type="text/css">
.taken{
background-color: red;
}
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Bus Tickets : choose your seat</div>
                <div class="card-body">
                    @foreach($seats as $seat)
                     <form action="{{ route('update', $seat->id) }}" method="post">
                        {{ csrf_field() }}
                    <button id="button-{{ $seat->id }}" type="submit" @if($seat->user_id != null) class='taken' @endif @if($seat->user_id != null && $seat->user_id != Auth::id())
                        disabled=''
                     @endif >
                        <img src="{{ asset('/seat.png') }}" width="10%">
                    </button>
                    </form>                       
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
         $(document).ready(function(){

            $('button').click(function(e){
            var button = $(this);
            var route = $(this).parent('form').attr('action');
               e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
               $.ajax({
                  url: route,
                  method: 'post',
                  success: function(result){
                        button.toggleClass('taken');
                  }});
               });

             Echo.channel('seats')
                 .listen('SeatStatusUpdated', (e) => {
                   $('#button-'+e.seat).toggleClass('taken');
                   $('#button-'+e.seat).prop('disabled', function(i, v) { return !v; });
            });

            });
</script>
@endsection
