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
        width: 450px;
        float: left;
    }

    .header .title.credit {
        width: 600px;
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

    table.company tr td.seller {
        width: 10%;
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

    table.subject tr td.credentials {
        width: 65%;
        line-height: 24px;
        vertical-align: top;
    }

    table.subject tr td.invoice-heading {
        width: 20%;
        line-height: 24px;
        vertical-align: top;
    }

    table.subject tr td.invoice-data {
        width: 15%;
        line-height: 24px;
        vertical-align: top;
    }

    .title {
        font-size: 16px;
        font-weight: bold;
        margin-top: 15px;
    }

    .credit-subtitle {
        font-size: 12px;
        font-weight: bold;
        margin-top: 20px;
        margin-bottom: 20px;
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

    table.items tr th p {
        font-size: 16px;
        color: red !important;
        font-weight: 600;
    }

    table.items tr td p {
        font-size: 18px;
        color: blue !important;
        font-weight: 600;
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
    @if($invoice->getStatus()->isCredit())
        <div class="title credit">
            <div>Credit tip invoice for Seller</div>
        </div>
    @else
        <div class="title">
            <div>Tip invoice for Seller</div>
        </div>
    @endif

    @if($invoice->getStatus()->isPaid())
        <div class="label paid">
            <div>PAID</div>
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
        <td class="seller">
            <p>Seller</p>
            <p class="bolder">{{ $invoice->tip->seller->billing->last_name }} {{ $invoice->tip->seller->billing->first_name }}</p>
            <p>{{ $invoice->tip->seller->billing->address }}</p>
            <p>{{ $invoice->tip->seller->billing->countryPlace->name }}</p>
            <p>{{ $invoice->tip->seller->billing->postal_code }}</p>
            <p>{{ $invoice->tip->seller->billing->city }}</p>
        </td>
    </tr>
</table>

<hr>

<table class="subject">
    <tr>
        <td class="credentials">
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
            <p>Due date</p>
            <p>Payment method</p>
            @if($invoice->getStatus()->isPaid())
                <p>Payment date</p>
                <p>Sale date</p>
            @elseif($invoice->getStatus()->isPendingPayout())
                <p>Sale date</p>
            @elseif($invoice->getStatus()->isCredit())
                <p>Refund date</p>
            @endif
        </td>
        <td class="invoice-data">
            <p class="semibold">{{ $invoice->full_id }}</p>
            <p class="semibold">{{ $invoice->created_at->toFormattedDateString() }}</p>
            <p class="semibold">{{ $invoice->created_at->toFormattedDateString() }}</p>
            <p class="semibold">{{ $invoice->tip->method->name }}</p>
            @if($invoice->getStatus()->isPaid())
                <p class="semibold">{{ $invoice->tip->payed_at ? $invoice->tip->payed_at->toFormattedDateString() : null }}</p>
                <p class="semibold">{{ $invoice->created_at->toFormattedDateString() }}</p>
            @elseif($invoice->getStatus()->isCredit())
                <p class="semibold">{{ $invoice->created_at->toFormattedDateString() }}</p>
            @elseif($invoice->getStatus()->isUnpaid())
                <p class="semibold">{{ $invoice->created_at->toFormattedDateString() }}</p>
            @endif
        </td>
    </tr>
</table>

<hr>

<div class="title">Description</div>

<table class="items">
    <tr>
        <td align="left"><p>Order item</p></td>
        <td align="left"><p>Vybe type</p></td>
        <td align="left"><p>Vybe title</p></td>
        <td align="right"><p>Tip amount($)</p></td>
    </tr>
    <tr>
        <td><p class="bold">{{ $invoice->tip->item->full_id }}</p></td>
        <td><p class="bold">{{ $invoice->tip->item->vybe->title }}</p></td>
        <td><p class="bold">{{ $invoice->tip->item->vybe->title }}</p></td>
        <td align="right"><p class="bold">{{ number_format($invoice->tip->amount, 2) }}</p></td>
    </tr>
</table>

<hr>

<table class="money">
    <tr>
        <td class="empty"></td>
        <td class="total-heading">
            <p>Subtotal</p>
            <p>Handling fee</p>
            <p>Total</p>
        </td>
        <td class="total-data" align="right">
            <p class="bold">{{ number_format($invoice->tip->amount, 2) }}</p>
            <p class="bold">{{ number_format($invoice->tip->handling_fee, 2) }}</p>
            <p class="bold">{{ number_format($invoice->tip->amount_earned, 2) }}</p>
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
        <td align="right"><p>Amount ($)</p></td>
    </tr>
</table>

<hr class="thin">

<table class="transactions-data">
    @foreach($invoice->tip->transactions as $tipTransaction)
        <tr>
            <td><p class="bold">{{ $tipTransaction->created_at->toFormattedDateString() }}</p></td>
            <td><p class="bold">{{ $tipTransaction->method->name }}</p></td>
            <td><p class="bold">{{ $tipTransaction->external_id }}</p></td>
            <td align="right"><p class="bold">{{ number_format($tipTransaction->amount, 2) }}</p></td>
        </tr>
    @endforeach
</table>