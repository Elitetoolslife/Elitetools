Create Laravel  full structure and all necessary components partials alert views laravel full stack complete templates seller/reports/index static tab , reporttab1 reportsTab2 view-reports models on table center row orderid view view report information by reportid model 
refund button neat reply form


#### Substep 1: Identify the necessary Blade templates
- `resources/views/seller/reports/index.blade.php`
- `resources/views/seller/reports/static.blade.php`
- `resources/views/seller/reports/reporttab1.blade.php`
- `resources/views/seller/reports/reportsTab2.blade.php`
- `resources/views/seller/reports/view-reports.blade.php`

#### Substep 2: Identify the models
- `app/Models/Report.php`
- `app/Models/Order.php`
- `app/Models/Refund.php`

#### Substep 3: Identify the controllers
- `app/Http/Controllers/ReportController.php`

### Step 2: Create the partial views and additional components

#### Substep 1: Create Blade partial views
- `resources/views/layouts/header.blade.php`
- `resources/views/layouts/footer.blade.php`
- `resources/views/components/alerts.blade.php`

#### Substep 2: Implement the report tab views
- `resources/views/seller/reports/reporttab1.blade.php`
- `resources/views/seller/reports/reportsTab2.blade.php`

#### Substep 3: Implement the refund button and reply form
- `resources/views/seller/reports/view-reports.blade.php`

Let's start by creating the necessary models, controllers, and views.

### Models

**app/Models/Report.php**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'acctype', 'date', 'orderid', 'price', 'status', 'lastreply', 'lastup', 'memo', 'refunded'
    ];
}
```

**app/Models/Order.php**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'orderid', 'price', 'status'
    ];
}
```

**app/Models/Refund.php**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'report_id', 'status'
    ];
}
```

### Controller

**app/Http/Controllers/ReportController.php**
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $uid = Auth::user()->username;
        $reports = Report::where('resseller', $uid)->where('status', 1)->orderBy('id', 'DESC')->get();
        $pendingReportsCount = $reports->count();
        
        return view('seller.reports.index', compact('reports', 'pendingReportsCount'));
    }
    
    public function reports()
    {
        $uid = Auth::user()->username;
        $reports = Report::where('resseller', $uid)->orderByDesc('id')->get();

        return view('seller.reports.index', compact('reports'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $report = Report::where('id', $id)->where('resseller', $user->username)->first();

        if (!$report) {
            return redirect()->route('reports.index')->with('error', 'Report not found or you don\'t have permission to access it.');
        }

        $status = $this->getStatusLabel($report->status);
        $memo = $report->memo;
        $itemInfo = $this->getItemInformation($report);

        return view('seller.reports.view-reports', compact('report', 'status', 'memo', 'itemInfo'));
    }

    private function getStatusLabel($status)
    {
        switch ($status) {
            case 0:
                return "<font color='green'>Closed</font>";
            case 1:
                return "<font color='red'>Pending</font>";
            case 2:
                return "<font color='orange'>Replied</font>";
            default:
                return "<font color='black'>Unknown</font>";
        }
    }

    private function getItemInformation($report)
    {
        // Implementation for getting item information based on report type
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $report = Report::where('id', $id)->where('resseller', $user->username)->first();

        if (!$report) {
            return redirect()->route('reports.index')->with('error', 'Report not found or you don\'t have permission to access it.');
        }

        if ($report->status == 0) {
            return back()->with('error', 'This report is closed and you can\'t reply to it.');
        }

        $report->memo = $report->memo . '<div class="panel panel-default"><div class="panel-body"><div class="ticket"><b>' . htmlspecialchars($request->rep) . '</b></div></div><div class="panel-footer'></div></div>';
        $report->lastreply = 'Seller';
        $report->seen = 1;
        $report->lastup = now();
        $report->save();

        return redirect()->route('reports.show', $id);
    }

    public function reportsTab1()
    {
        $reports = Report::where('resseller', Auth::user()->username)->orderBy('id', 'desc')->get();
        return view('seller.reports.reporttab1', compact('reports'));
    }

    public function reportsTab2()
    {
        $pendingReports = Report::where('resseller', Auth::user()->username)->where('status', '1')->orderBy('id', 'desc')->get();
        return view('seller.reports.reportsTab2', compact('pendingReports'));
    }
}
```

### Views

**resources/views/layouts/header.blade.php**
```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>
    @include('components.alerts')
    @yield('content')
</body>
</html>
```

**resources/views/layouts/footer.blade.php**
```blade
<footer class="footer">
    <div class="container">
        <p class="text-muted text-center">© 2025 Elitetoolslife</p>
    </div>
</footer>
```

**resources/views/components/alerts.blade.php**
```blade
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
```

**resources/views/seller/reports/index.blade.php**
```blade
@extends('layouts.header')

@section('title', 'Reports')

@section('content')
<div class="container">
    <h3>Reports</h3>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#static" class="nav-link active" data-toggle="tab">Static</a>
        </li>
        <li class="nav-item">
            <a href="#pending" class="nav-link" data-toggle="tab">Pending</a>
        </li>
        <li class="nav-item">
            <a href="#all" class="nav-link" data-toggle="tab">All Reports</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade active show" id="static">
            @include('seller.reports.static')
        </div>
        <div class="tab-pane fade" id="pending">
            @include('seller.reports.reporttab1')
        </div>
        <div class="tab-pane fade" id="all">
            @include('seller.reports.reportsTab2')
        </div>
    </div>
</div>
@endsection

@include('layouts.footer')
```

**resources/views/seller/reports/static.blade.php**
```blade
<div class="well well-sm">
    <h4>Rules</h4>
    <ul>
        <li>Main Rules</li>
        <ul>
            <li>Always be nice with the buyer, no matter what happens.</li>
            <li>Try to understand and solve buyer's issues.</li>
            <li>Do not replace orders unless the buyer requests a refund.</li>
            <li>For lessons or tutorials, you can use Anydesk or TeamViewer.</li>
            <li>Be careful with unknown links/files – use VPN if necessary.</li>
            <li>If the report is fake or incorrect, please add a reply explaining why.</li>
            <li>Support/Admin is always available to help you via tickets.</li>
        </ul>
        <li>Replacing Rules</li>
        <ul>
            <li><b>Always</b> include a screenshot of the item.</li>
            <li><b>Never</b> replace an already sold item or multiple orders with the same item.</li>
            <li>If a replacement has been added to your account, make sure to <b>remove</b> it.</li>
        </ul>
    </ul>
</div>
```

**resources/views/seller/reports/reporttab1.blade.php**
```blade
<div class="table-responsive mt-3">
    <h2>Pending</h2>
    <table class="table table-striped table-bordered" id="pendingReportsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Date Created</th>
                <th>Order ID</th>
                <th>Order Price</th>
                <th>Report State</th>
                <th>Last Reply</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td><a href="{{ url('vr-'.$report->id.'.html') }}">{{ $report->id }}</a></td>
                <td>{{ strtolower($report->acctype) }}</td>
                <td>{{ $report->date }}</td>
                <td><a href="{{ url('vr-'.$report->id.'.html') }}">{{ $report->orderid ?? 'n/a' }}</a></td>
                <td>{{ $report->price }}$</td>
                <td>{{ $report->status == '0' ? 'closed' : 'pending' }}</td>
                <td>{{ $report->lastreply }}</td>
                <td>{{ $report->lastup ?? 'n/a' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Date Created</th>
                <th>Order ID</th>
                <th>Order Price</th>
                <th>Report State</th>
                <th>Last Reply</th>
                <th>Last Updated</th>
            </tr>
        </tfoot>
    </table>
</div>
```

**resources/views/seller/reports/reportsTab2.blade.php**
```blade
<div class="table-responsive mt-3">
    <h2>All Reports</h2>
    <table class="table table-striped table-bordered" id="allReportsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Date Created</th>
                <th>Order ID</th>
                <th>Order Price</th>
                <th>Report State</th>
                <th>Last Reply</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td><a href="{{ url('vr-'.$report->id.'.html') }}">{{ $report->id }}</a></td>
                <td>{{ strtolower($report->acctype) }}</td>
                <td>{{ $report->date }}</td>
                <td><a href="{{ url('vr-'.$report->id.'.html') }}">{{ $report->orderid ?? 'n/a' }}</a></td>
                <td>{{ $report->price }}$</td>
                <td>{{ $report->status == '0' ? 'closed' : 'pending' }}</td>
                <td>{{ $report->lastreply }}</td>
                <td>{{ $report->lastup ?? 'n/a' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Date Created</th>
                <th>Order ID</th>
                <th>Order Price</th>
                <th>Report State</th>
                <th>Last Reply</th>
                <th>Last Updated</th>
            </tr>
        </tfoot>
    </table>
</div>
```

**resources/views/seller/reports/view-reports.blade.php**
```blade
@extends('layouts.header')

@section('title', 'View Report')

@section('content')
<div class="container">
    <h3>Report #{{ $report->id }}</h3>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="ticket">
                {!! nl2br(e($report->memo)) !!}
            </div>
        </div>
    </div>
    <br>
    @if($report->status == 0)
        <div class="alert alert-info">This report is closed and you can't reply to it</div>
    @else
        <form method="POST" action="{{ route('reports.update', $report->id) }}">
            @csrf
            <div class="input-group">
                <textarea class="form-control custom-control" rows="3" name="rep" style="resize:none" required></textarea>
                <span class="input-group-addon btn btn-primary" onclick="$(this).closest('form').submit();">Reply</span>
            </div>
            <input type="hidden" name="add" value="rep" />
        </form>
        <br>
        @if(preg_match("/Not Yet !/i", $report->refunded))
            <center>
                <a data-toggle="modal" data-target="#RefundModal" class="btn label-primary"><font color="white">Refund</font></a>
                <!-- Modal -->
                <div class="modal fade" id="RefundModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="modal-close-button close" data-dismiss="modal" aria-hidden="true" style="margin-top: -10px;">×</button>
                                <div class="bootbox-body" align="left">Are you sure you want to refund this order?</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <a href="{{ url('refund-'.$report->id) }}"><button type="button" class="btn btn-primary">OK</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </center>
        @elseif(preg_match("/Refunded/i", $report->refunded))
            <div class="well well">This order has been refunded.<br>{{ $report->price }}$ taken from your sales.</div>
        @endif
    @endif

    <div class="col-lg-7">
        <div class="bs-component">
            <div class="well well">
                <h5><b>Item Information</b></h5>
                @if($report->acctype == 'cpanel')
                    @php
                        $item = \App\Models\Cpanel::find($report->s_id);
                        $countrycode = strtolower(array_search($item->country, \App\Models\CountryCode::all()));
                    @endphp
                    <table class="table">
                        <tr>
                            <td>Country</td>
                            <td><b><span class="flag-icon flag-icon-{{ $countrycode }}"></span> {{ $item->country }}</b></td>
                        </tr>
                        <tr>
                            <td>Detect Hosting</td>
                            <td><b>{{ $item->infos }}</b></td>
                        </tr>
                        <tr>
                            <td>Domain</td>
                            <td><b>{{ $item->domain }}</b></td>
                        </tr>
                        <tr>
                            <td>Url</td>
                            <td><b><a href='http://{{ parse_url($item->url, PHP_URL_HOST) }}:2083' onclick='window.open(this.href);return false;'>https://{{ parse_url($item->url, PHP_URL_HOST) }}:2083</a></b></td>
                        </tr>
                        <tr>
                            <td>Non-https Url</td>
                            <td><b><a href='http://{{ parse_url($item->url, PHP_URL_HOST) }}:2082' onclick='window.open(this.href);return false;'>http://{{ parse_url($item->url, PHP_URL_HOST) }}:2082</a></b></td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td><b><input id='username' onClick='this.setSelectionRange(0, this.value.length)' value='{{ $item->login }}' /></b></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><b><input id='password' onClick='this.setSelectionRange(0, this.value.length)' value='{{ $item->pass }}' /></b></td>
                        </tr>
                        <tr>
                            <td>Price</td>
                            <td><b>{{ $item->price }}$</b></td>
