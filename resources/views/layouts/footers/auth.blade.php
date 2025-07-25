<footer class="footer">
  <div class="container">
    <nav class="float-left">
      <ul>
        <li>
          <a href="{{config('app.website')}}" target="_blank">
            {{ __('FBM') }}
          </a>
        </li>
        <li>
          <a href="{{asset('soporte_servicios/licenciamiento')}}">
            {{ __('Licencias') }}
          </a>
        </li>
      </ul>
    </nav>
    <div class="copyright float-right">
      &copy;
      <script>
      document.write(new Date().getFullYear())
      </script>, Todos los derechos reservados
    </div>
  </div>
</footer>
