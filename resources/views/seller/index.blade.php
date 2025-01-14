

            <!-- Navigation Tabs -->
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

            <!-- Tab Content -->
            <div class="tab-content">

                <!-- Static Tab -->
                <div class="tab-pane fade active show" id="static">
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
                </div>

                <!-- Pending Tab -->
                <div class="tab-pane fade" id="pending">
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
                </div>

                <!-- All Reports Tab -->
                <div class="tab-pane fade" id="all">
                    <h2>All Reports</h2>
                    <div class="table-responsive mt-3">
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
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Initialize DataTables -->
<script>
$(document).ready(function() {
    // Initialize DataTables for Pending Reports Table
    $('#pendingReportsTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        footerCallback: function(row, data, start, end, display) {
            let total = data.length;
            $(row).find('th').eq(0).html('Total Records: ' + total);
        }
    });

    // Initialize DataTables for All Reports Table
    $('#allReportsTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        footerCallback: function(row, data, start, end, display) {
            let total = data.length;
            $(row).find('th').eq(0).html('Total Records: ' + total);
        }
    });
});
</script>
    <title>Report #{{ $report->id }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
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
                            $countrycode = strtolower(array_search($item->country, $countrycodes));
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
                            </tr>
                        </table>
                    @endif


                    @if($report->acctype == 'banks')
                        @php
                            $item = \App\Models\Banks::find($report->s_id);
                            $countrycode = strtolower(array_search($item->country, $countrycodes));
                        @endphp
                        <table class="table">
                            <tr>
                                <td>Country</td>
                                <td><b><span class="flag-icon flag-icon-{{ $countrycode }}"></span> {{ $item->country }}</b></td>
                            </tr>
                            <tr>
                                <td>Bank Name</td>
                                <td><b>{{ $item->bankname }}</b></td>
                            </tr>
                            <tr>
                                <td>Available Information</td>
                                <td><b>{{ $item->infos }}</b></td>
                            </tr>
                            <tr>
                                <td>Balance</td>
                                <td><b>{{ $item->balance }}</b></td>
                            </tr>
                            <tr>
                                <td>Account Info</td>
                                <td><b><textarea rows='10' cols='30'>{{ $item->url }}</textarea></b></td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td><b>{{ $item->price }}$</b></td>
                            </tr>
                        </table>
                    @endif

                    @if($report->acctype == 'tutorial')
                        @php
                            $item = \App\Models\Tutorial::find($report->s_id);
                        @endphp
                        <table class="table">
                            <tr>
                                <td>Name</td>
                                <td><b>{{ $item->tutoname }}</b></td>
                            </tr>
                            <tr>
                                <td>Information</td>
                                <td><b>{{ $item->infos }}</b></td>
                            </tr>
                            <tr>
                                <td>Download</td>
                                <td><b><a href='{{ $item->url }}' onclick='window.open(this.href);return false;'>{{ $item->url }}</a></b></td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td><b>{{ $item->price }}$</b></td>
                            </tr>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Cpanel;
use App\Models\Bank;
use App\Models\Tutorial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

    class ReportController extends Controller
    {
 public function index()
    {
        $uid = Auth::user()->username;
        $reports = Report::where('resseller', $uid)->where('status', 1)->orderBy('id', 'DESC')->get();
        $pendingReportsCount = $reports->count();
        
        return view('reports.index', compact('reports', 'pendingReportsCount'));
    }
    
   public function reports()
    {
        $uid = Auth::user()->username;
        $reports = Report::where('resseller', $uid)->orderByDesc('id')->get();

            return view('reports', compact('reports.index'));
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

        return view('reports.show', compact('report', 'status', 'memo', 'itemInfo'));
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
        $itemInfo = [];
        switch ($report->acctype) {
            case 'cpanel':
                 break;
            case 'banks':
                $itemInfo = Bank::where('id', $report->s_id)->first();
                break;
              case 'tutorial':
                $itemInfo = Tutorial::where('id', $report->s_id)->first();
                break;
        }
        return $itemInfo;
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

        $report->memo = $report->memo . '<div class="panel panel-default"><div class="panel-body"><div class="ticket"><b>' . htmlspecialchars($request->rep) . '</b></div></div><div class="panel-footer"><div class="label label-success">Seller</div></div></div>';
        $report->lastreply = 'Seller';
        $report->seen = 1;
        $report->lastup = now();
        $report->save();

        return redirect()->route('reports.show', $id);
    }
       public function reportsTab1()
    {
        $reports = Report::where('resseller', Auth::user()->username)->orderBy('id', 'desc')->get();
        return view('reports.reportsTab1', compact('reports'));
    }

    public function reportsTab2()
    {
        $pendingReports = Report::where('resseller', Auth::user()->username)->where('status', '1')->orderBy('id', 'desc')->get();
        return view('reports.reportsTab2', compact('pendingReports'));
    }
}