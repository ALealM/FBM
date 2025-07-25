@component('mail::message')

  <center>
    <div class="logo">
      <img style="width:100px" src="{{config('app.url')}}/material/img/FBM_LOGO.png">
    </div>
  </center>
  <br>

  {!! @$aDatos['body_mensaje'] !!}

@endcomponent
