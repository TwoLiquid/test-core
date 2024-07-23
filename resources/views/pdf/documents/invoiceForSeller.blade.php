<style>
  body {
    font-family: 'montserrat', sans-serif;
    margin: 0;
    padding: 0;
  }

  .header {
      width: 100%;
  }

  .header .title {
      width: 375px;
      float: left;
  }

  .header .title.credit {
      width: 500px;
      float: left;
  }

  .header .title div {
      font-size: 40px;
      font-weight: bold;
  }

  .header .label {
      float: left;
      text-align: left;
      padding-top: 16px;
  }

  .header .label.unpaid {
      width: 80px;
  }

  .header .label.unpaid div {
      background-color: #FF4C49;
  }

  .header .label.paid {
      width: 58px;
  }

  .header .label.paid div {
      background-color: #4FBF67;
  }

  .header .label.canceled {
      width: 103px;
  }

  .header .label.canceled div {
      background-color: #81818D;
  }

  .header .label.on_hold {
      width: 95px;
  }

  .header .label.on_hold div {
      background-color: #FF7A68;
  }

  .header .label.pending_payout {
      width: 162px;
  }

  .header .label.pending_payout div {
      background-color: #FF9F38;
  }

  .header .label div {
      border-radius: 8px;
      color: #FFFFFF;
      font-size: 14px;
      font-weight: bold;
      padding: 5px 10px 5px 10px;
      text-align: center;
  }

  table {
      border-spacing: 0;
  }

  table.company {
      width: 100%;
      margin-top: 30px;
  }

  table.company tr td.logo {
      width: 69%;
      vertical-align: top;
  }

  img.mello {
      width: 135px;
      opacity: 0.5;
  }

  table.company tr td.credentials {
      width: 31%;
      line-height: 24px;
      vertical-align: top;
  }

  p {
      font-size: 12px;
      font-weight: normal;
      color: #5A5A65;
  }

  p.semibold {
      font-size: 12px;
      font-weight: normal;
      color: #000000;
  }

  p.bold {
      font-size: 12px;
      font-weight: bold;
      color: #000000;
  }

  p.bolder {
      font-size: 16px;
      font-weight: bold;
      color: #000000;
  }

  hr {
      border-width: 1px;
      color: #C8C8D5;
      margin-top: 30px;
  }

  hr.thin {
      border-width: 1px;
      color: #C8C8D5;
      margin-top: 10px;
  }

  table.subject {
      width: 100%;
      margin-top: 15px;
  }

  table.subject tr td.buyer {
      width: 69%;
      line-height: 24px;
      vertical-align: top;
  }

  table.subject tr td.invoice-heading {
      width: 20%;
      line-height: 24px;
      vertical-align: top;
  }

  table.subject tr td.invoice-data {
      width: 11%;
      line-height: 24px;
      vertical-align: top;
  }

  .title {
      font-size: 16px;
      font-weight: bold;
      margin-top: 15px;
  }

  table.items {
      width: 100%;
      margin-top: 15px;
  }

  table.items td {
      width: 20%;
      line-height: 24px;
      vertical-align: top;
  }

  table.money {
      width: 100%;
      margin-top: 15px;
  }

  table.money td.empty {
      width: 69%;
      line-height: 24px;
      vertical-align: top;
  }

  table.money td.total-heading {
      width: 20%;
      line-height: 24px;
      vertical-align: top;
  }

  table.money td.total-data {
      width: 11%;
      line-height: 24px;
      vertical-align: top;
  }

  table.transactions-header {
      width: 100%;
      margin-top: 15px;
  }

  table.transactions-header td {
      width: 25%;
      line-height: 24px;
      vertical-align: top;
  }

  table.transactions-data {
      width: 100%;
      margin-top: 0;
  }

  table.transactions-data td {
      width: 25%;
      line-height: 24px;
      vertical-align: top;
  }
</style>

<div class="header">
    @if($invoice->getStatus()->isCredit())
        <div class="title credit">
            <div>Credit invoice for Seller</div>
        </div>
    @else
        <div class="title">
            <div>Invoice for Seller</div>
        </div>
    @endif

    @if($invoice->getStatus()->isUnpaid())
        <div class="label unpaid">
            <div>UNPAID</div>
        </div>
    @elseif($invoice->getStatus()->isPaid())
        <div class="label paid">
            <div>PAID</div>
        </div>
    @elseif($invoice->getStatus()->isCanceled())
        <div class="label canceled">
            <div>CANCELED</div>
        </div>
    @elseif($invoice->getStatus()->isOnHold())
        <div class="label on_hold">
            <div>ON HOLD</div>
        </div>
    @elseif($invoice->getStatus()->isPendingPayout())
        <div class="label pending_payout">
            <div>PENDING PAYOUT</div>
        </div>
    @endif
</div>

<table class="company">
    <tr>
        <td class="logo">
            <img class="mello" alt="" src="{{ resource_path('img/logo.png') }}" />
        </td>
        <td class="credentials">
            <p>Seller</p>
            <p class="bolder">{{ $seller->billing->last_name }} {{ $seller->billing->first_name }}</p>
            <p>{{ $seller->billing->address }}</p>
            <p>{{ $seller->billing->countryPlace->name }}</p>
            <p>{{ $seller->billing->postal_code }}</p>
            <p>{{ $seller->billing->city }}</p>
        </td>
    </tr>
</table>

<hr>

<table class="subject">
    <tr>
        <td class="buyer">
            <p>Buyer</p>
            <p class="bolder">{{ config('company.credentials.name') }}</p>
            <p>{{ config('company.credentials.address') }}</p>
            <p>{{ config('company.credentials.country') }}</p>
            <p>{{ config('company.credentials.phone') }}</p>
            <p>{{ config('company.credentials.btw') }}</p>
            <p>{{ config('company.credentials.email') }}</p>
            <p>{{ config('company.credentials.site') }}</p>
        </td>
        <td class="invoice-heading">
            <p>Invoice #</p>
            <p>Invoice date</p>
            <p>Payment method</p>
            <p>Sale date</p>
        </td>
        <td class="invoice-data">
            <p class="semibold">{{ $invoice->full_id }}</p>
            <p class="semibold">{{ $invoice->created_at->toFormattedDateString() }}</p>
            <p class="semibold">{{ $invoice->order->method->name }}</p>
            <p class="semibold">{{ $invoice->order->created_at->toFormattedDateString() }}</p>
        </td>
    </tr>
</table>

<hr>

<div class="title">Order items</div>

<table class="items">
    <tr>
        <td><p>Order Item ID</p></td>
        <td><p>Vybe type</p></td>
        <td><p>Price ($)</p></td>
        <td><p>Quantity</p></td>
        <td><p>Amount ($)</p></td>
    </tr>
    @foreach($invoice->items as $orderItem)
        <tr>
            <td><p class="bold">{{ $orderItem->full_id }}</p></td>
            <td><p class="bold">{{ $orderItem->vybe->getType()->name }}</p></td>
            <td><p class="bold">{{ number_format($orderItem->price, 2) }}</p></td>
            <td><p class="bold">{{ $orderItem->quantity }}</p></td>
            <td><p class="bold">{{ number_format($orderItem->price * $orderItem->quantity, 2) }}</p></td>
        </tr>
    @endforeach
</table>

<hr>

<table class="money">
    <tr>
        <td class="empty">
        </td>
        <td class="total-heading">
            <p>Subtotal</p>
            <p>Payment fee</p>
            <p>TAX</p>
            <p>Total</p>
        </td>
        <td class="total-data">
            <p class="bold">{{ number_format($subtotal, 2) }}</p>
            <p class="bold">{{ number_format($payment_fee + $payment_fee_tax, 2) }}</p>
            <p class="bold">{{ number_format($amount_tax_total, 2) }}</p>
            <p class="bold">{{ number_format($subtotal + $payment_fee + $payment_fee_tax + $amount_tax_total, 2) }}</p>
        </td>
    </tr>
</table>

<hr>

<div class="title">Transactions</div>

<table class="transactions-header">
    <tr>
        <td><p>Date</p></td>
        <td><p>Payment method</p></td>
        <td><p>Transaction ID</p></td>
        <td><p>Amount ($)</p></td>
    </tr>
</table>

<hr class="thin">

<table class="transactions-data">
    @foreach($invoice->transactions as $orderTransaction)
        <tr>
            <td><p class="bold">{{ $orderTransaction->created_at->toFormattedDateString() }}</p></td>
            <td><p class="bold">{{ $orderTransaction->method->name }}</p></td>
            <td><p class="bold">{{ $orderTransaction->externalId }}</p></td>
            <td><p class="bold">{{ number_format($orderTransaction->amount, 2) }}</p></td>
        </tr>
    @endforeach
</table>