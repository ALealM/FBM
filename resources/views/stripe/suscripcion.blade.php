@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
  <div class="content">
  <h1>Suscripción</h1>
  <form action="/stripe/store_suscripcion" method="POST">
    {{ csrf_field() }}
    <script class="stripe-button" src="https://checkout.stripe.com/checkout.js"
    data-key="{{ config('services.stripe.key') }}"
    data-amount="56000"
    data-name="Suscripción"
    data-description="Suscribirte a FBM"
    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
    data-locale="auto"
    data-currency="mxn">
    </script>
  </form>
</div>


<!--input id="card-holder-name" type="text">
<div id="card-element"></div>
<button id="card-button" data-secret="{{ @$intent->client_secret }}">
    Update Payment Method
</button>


<script src="https://js.stripe.com/v3/"></script>
<script>
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
        console.log(error);
      } else {
        console.log("Verificada con exito");
      }
    });
</script-->
@endsection
