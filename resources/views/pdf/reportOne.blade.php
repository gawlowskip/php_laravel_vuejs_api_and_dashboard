<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid">

    <p class="lead">
        {{ trans('pdf.developer_details') }}
    </p>

    <table class="table table-sm" style="font-size: 8px;">
        <thead class="thead-dark">
        <tr>
            <th>
                {{ trans('pdf.id') }}
            </th>
            <th>
                {{ trans('pdf.first_name') }}
            </th>
            <th>
                {{ trans('pdf.last_name') }}
            </th>
            <th>
                {{ trans('pdf.email') }}
            </th>
            <th>
                {{ trans('pdf.phone') }}
            </th>
            <th>
                {{ trans('pdf.street_1') }}
            </th>
            <th>
                {{ trans('pdf.street_2') }}
            </th>
            <th>
                {{ trans('pdf.city') }}
            </th>
            <th>
                {{ trans('pdf.postal_code') }}
            </th>
            <th>
                {{ trans('pdf.latitude') }}
            </th>
            <th>
                {{ trans('pdf.longitude') }}
            </th>
            <th>
                {{ trans('pdf.cvr_number') }}
            </th>
            <th>
                {{ trans('pdf.active') }}
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>
                {{ $data['developer']['id'] }}
            </th>
            <th>
                {{ $data['developer']['name'] }}
            </th>
            <th>
                {{ $data['developer']['last_name'] }}
            </th>
            <th>
                @if($data['developer']['email'])
                    <a href="mailto:{{ $data['developer']['email'] }}">{{ $data['developer']['email'] }}</a>
                @else
                    -
                @endif
            </th>
            <th>
                @if($data['developer']['phone'])
                    <a href="tel:{{ $data['developer']['phone'] }}">{{ $data['developer']['phone'] }}</a>
                @else
                    -
                @endif
            </th>
            <th>
                {{ $data['developer']['street_1'] }}
            </th>
            <th>
                {{ $data['developer']['street_2'] }}
            </th>
            <th>
                {{ $data['developer']['city'] }}
            </th>
            <th>
                {{ $data['developer']['postal_code'] }}
            </th>
            <th>
                {{ $data['developer']['latitude'] }}
            </th>
            <th>
                {{ $data['developer']['longitude'] }}
            </th>
            <th>
                {{ $data['developer']['cvr_number'] }}
            </th>
            <th>
                @if ($data['developer']['active'])
                    {{ trans('pdf.active') }}
                @else
                    {{ trans('pdf.inactive') }}
                @endif
            </th>
        </tr>
        </tbody>
    </table>

    <p class="lead">
        {{ trans('pdf.ads_and_leads_per_developer') }}
    </p>

    <table class="table table-sm" style="font-size: 8px;">
        <thead class="thead-dark">
        <tr>
            <th>
                {{ trans('pdf.ads') }}
            </th>
            <th>
                {{ trans('pdf.leads') }}
            </th>
            <th>
                {{ trans('pdf.from_date') }}
            </th>
            <th>
                {{ trans('pdf.to_date') }}
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>
                {{ count($data['ads']) }}
            </th>
            <th>
                {{ count($data['leads']) }}
            </th>
            <th>
                {{ $data['fromDate'] }}
            </th>
            <th>
                {{ $data['toDate'] }}
            </th>
        </tr>
        </tbody>
    </table>

</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</body>
</html>