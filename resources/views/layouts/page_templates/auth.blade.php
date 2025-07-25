<div class="wrapper ">
  @include('layouts.navbars.sidebar')
  <div class="main-panel">
    @include('layouts.navbars.navs.auth')

    <div class="content pt-0">
      <div class="container-fluid">
        <div class="card">
          <div class="bg-fbm-blue card-header">
            <h4 class="card-title text-white">{!! mb_strtoupper(@$sTitulo) !!}</h4>
            <p class="card-category">{!! @$sDescripcion !!}</p>
          </div>
          <div class="card-body {{@$sClassCardBody}}">

            @yield('content')

          </div>
        </div>
      </div>
    </div>

    @include('layouts.footers.auth')
  </div>
</div>
