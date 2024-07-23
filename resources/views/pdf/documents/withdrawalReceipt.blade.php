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
      width: 550px;
      float: left;
  }

  .header .title.credit {
      width: 680px;
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
      border-spacing: 0px;
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
      margin-top: 0px;
  }

  table.transactions-data td {
      width: 25%;
      line-height: 24px;
      vertical-align: top;
  }
</style>

<div class="header">
    @if($receipt->getStatus()->isCredit())
        <div class="title credit">
            <div>Credit seller withdrawal receipt</div>
        </div>
    @else
        <div class="title">
            <div>Seller withdrawal receipt</div>
        </div>
    @endif

    @if($receipt->getStatus()->isUnpaid())
        <div class="label unpaid">
            <div>UNPAID</div>
        </div>
    @elseif($receipt->getStatus()->isPaid())
        <div class="label paid">
            <div>PAID</div>
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
            <p class="bolder">{{ $receipt->user->billing->last_name }} {{ $receipt->user->billing->first_name }}</p>
            <p>{{ $receipt->user->billing->address }}</p>
            <p>{{ $receipt->user->billing->countryPlace->name }}</p>
            <p>{{ $receipt->user->billing->postal_code }}</p>
            <p>{{ $receipt->user->billing->city }}</p>
        </td>
    </tr>
</table>

<hr>

<table class="subject">
    <tr>
        <td class="buyer">
            <p class="bolder">{{ config('company.credentials.name') }}</p>
            <p>{{ config('company.credentials.address') }}</p>
            <p>{{ config('company.credentials.country') }}</p>
            <p>{{ config('company.credentials.phone') }}</p>
            <p>{{ config('company.credentials.btw') }}</p>
            <p>{{ config('company.credentials.email') }}</p>
            <p>{{ config('company.credentials.site') }}</p>
        </td>
        <td class="invoice-heading">
            <p>Receipt #</p>
            <p>Receipt date</p>
            <p>Due date</p>
            @if($receipt->getStatus()->isPaid())
                <p>Payment method</p>
                <p>Payment date</p>
            @endif
        </td>
        <td class="invoice-data">
            <p class="semibold">{{ $receipt->full_id }}</p>
            <p class="semibold">{{ $receipt->created_at->toFormattedDateString() }}</p>
            <p class="semibold">{{ $receipt->created_at->toFormattedDateString() }}</p>
            @if($receipt->getStatus()->isPaid())
                <p class="semibold">{{ $receipt->method->name }}</p>
                <p class="semibold">{{ $receipt->created_at->toFormattedDateString() }}</p>
            @endif
        </td>
    </tr>
</table>

<hr>

<div class="title">Description</div>

<table class="items">
    <tr>
        <td><p>Description item</p></td>
        <td align="right"><p>Amount ($)</p></td>
    </tr>
    <tr>
        <td><p class="bold">Withdrawal Request #SW6786175 Seller #SA{{ $receipt->user->id }}</p></td>
        <td align="right"><p class="bold">{{ number_format($receipt->amount, 2) }}</p></td>
    </tr>
</table>

<hr>

<table class="money">
    <tr>
        <td class="empty"></td>
        <td class="total-heading">
            <p>Total</p>
        </td>
        <td align="right" class="total-data">
            <p class="bold">{{ number_format($receipt->amount, 2) }}</p>
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
    @foreach($receipt->transactions as $receiptTransaction)
        <tr>
            <td><p class="bold">{{ $receiptTransaction->created_at->toFormattedDateString() }}</p></td>
            <td><p class="bold">{{ $receiptTransaction->method->name }}</p></td>
            <td><p class="bold">{{ $receiptTransaction->externalId }}</p></td>
            <td><p class="bold">{{ number_format($receiptTransaction->amount, 2) }}</p></td>
        </tr>
    @endforeach
</table>