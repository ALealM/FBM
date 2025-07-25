<div class="col-lg-12">
  <div class="card">
    <div class="card-body">
      <h3 class="card-title">Mi forma de pago</h3>
      <a href="javascript:;" class="btn btn-success btn-sm metodo-pago" onclick="toggle_pago()"><i class="fa fa-credit-card mr-2"></i>{{($oEmpresa->card_last_four != NULL && $oEmpresa->card_last_four != ""?'Actualizar':'Agregar')}}</a>
      <div id="divMiFormaPago" class="metodo-pago">
        @if ($oEmpresa->card_last_four != NULL && $oEmpresa->card_last_four != "")
          <p class="card-text">
            <h4><strong>**** **** **** {{$oEmpresa->card_last_four}}</strong> {{$oEmpresa->card_brand}}</h4>
          </p>
        @endif
      </div>
      <div id="divMetodoPago" class="actualizar-metodo-pago">
        <div class="row">
          <label class="col-lg-12 col-form-label">Tarjeta</label>
          <div class="col-lg-12">
            <input id="card-holder-name" class="form-control inputSlim" type="text" value="Principal"/>
          </div>
        </div>
        <div id="card-element" class="form-control inputSlim"></div>
        <div class="card-footer justify-content-center">
          <button id="card-button" class="btn btn-success btn-sm" data-secret="{{ $intent->client_secret }}"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>
          <a href="javascript:;" class="btn btn-secondary btn-sm" onclick="toggle_pago()"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>

$("#divMetodoPago").hide();

//Stripe
const stripe = Stripe("{{ config('services.stripe.key') }}");
const elements = stripe.elements();
const cardElement = elements.create('card');
cardElement.mount('#card-element');
const cardHolderName = document.getElementById('card-holder-name');
const cardButton = document.getElementById('card-button');
const clientSecret = cardButton.dataset.secret;

cardButton.addEventListener('click', async (e) => {
  const { setupIntent, error } = await stripe.confirmCardSetup(
    clientSecret, {
      payment_method: {
        card: cardElement,
        billing_details: { name: cardHolderName.value }
      }
    }
  );
  if (error) {
    notificacion('Alerta',error.message, 'error');
    console.log(error);
  } else {
    console.log(setupIntent);
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "POST",
      url: "{{ asset ('stripe/update_default_payment_method') }}",
      data: {
        'setupIntent' : setupIntent,
      },
      cache: false,
      dataType: "json",
      success: function (result) {
        if(result.estatus === 1){
          notificacion('Forma de pago','Verificación exitosa', 'success');
          setTimeout(() => { window.location.reload(); }, 2000);
        }else {
          notificacion('Alerta',result.mensaje, 'error');
        }
      },
      error: function (result) {
        console.log("error");
      }
    });


  }
});
//Stripe end

function toggle_pago()
{
  $(".actualizar-metodo-pago").slideToggle();
  $(".metodo-pago").slideToggle();
}

</script>
