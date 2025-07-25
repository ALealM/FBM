<div class="content">
  <h1>Compra de Prueba</h1>
  <h3>US$ 19.99</h3>
  <form action="/stripe/store_cargo" method="POST">
    {{ csrf_field() }}
    <script class="stripe-button" src="https://checkout.stripe.com/checkout.js"
    data-key="{{ config('services.stripe.key') }}"
    data-amount="1990"
    data-name="Compra"
    data-description="Prueba compra"
    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
    data-locale="auto">
    </script>
  </form>
</div>
