@include('includes.headcss')
@include('includes.header')
@include('includes.sideNavigation')

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Users</h4>
            </div>
        </div>
        <div class="row">
            <div class="white-box">
                <div class="panel-body">
                    @if ($sessionData = Session::get('data'))
                    @if($sessionData['status_code'] == 1)
                    <div class="alert alert-success alert-block">
                        @else
                        <div class="alert alert-danger alert-block">
                            @endif
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $sessionData['message'] }}</strong>
                        </div>
                        @endif
                        <form action="{{ route('users.index') }}" enctype="multipart/form-data" method="get">
                            {{ method_field("GET") }}

                            @csrf

                            <div class="col-md-3 form-group">
                                <label>From Date </label>
                                <input type="text" @if(isset($data['start_date'])) value="{{$data['start_date']}}" @endif name="start_date" placeholder="Enter Start date here" class="form-control start-date" autocomplete="off">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>To Date </label>
                                <input type="text" @if(isset($data['end_date'])) value="{{$data['end_date']}}" @endif name="end_date" placeholder="Enter End date here" class="form-control end-date" autocomplete="off">
                            </div>

                            <div class="col-md-2 form-group">
                                <label>User id </label>
                                <input type="text" @if(isset($data['search'])) value="{{$data['search']}}" @endif name="search" placeholder="Enter user id / wallet address / mt5 / mt5 name / name / email here" class="form-control">
                            </div>

                            <div class="col-md-2 form-group">
                                <label>Rank</label><br>
                                <select name="rank" class="form-control">
                                    <option value="">Select Rank</option>
                                    <option value="1" @if(isset($data['rank'])) @if($data['rank']==1) selected @endif @endif>1</option>
                                    <option value="2" @if(isset($data['rank'])) @if($data['rank']==2) selected @endif @endif>2</option>
                                    <option value="3" @if(isset($data['rank'])) @if($data['rank']==3) selected @endif @endif>3</option>
                                    <option value="4" @if(isset($data['rank'])) @if($data['rank']==4) selected @endif @endif>4</option>
                                    <option value="5" @if(isset($data['rank'])) @if($data['rank']==5) selected @endif @endif>5</option>
                                    <option value="6" @if(isset($data['rank'])) @if($data['rank']==6) selected @endif @endif>6</option>
                                    <option value="7" @if(isset($data['rank'])) @if($data['rank']==7) selected @endif @endif>7</option>
                                </select>
                            </div>

                            <div class="col-md-2 form-group">
                                <label>Search</label><br>
                                <input type="submit" name="submit" value="Search" class="btn btn-success">
                            </div>

                        </form>
                        <div class="export-section">
                            <a href="{{ url()->current() }}?export=yes&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&search={{ request('search') }}&rank={{ request('rank') }}">
                                <button type="button" class="btn waves-effect waves-light btn-info">Export Excel</button>
                            </a>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Login</th>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Wallet Address</th>
                                            <th>User Id</th>
                                            <th>Rank</th>
                                            <th>Package</th>
                                            <th>Topup Balance</th>
                                            <th>Total Income</th>
                                            <th>Total Withdraw</th>
                                            <th>Available Balance</th>
                                            <th>Update User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $j=1;
                                        @endphp
                                        @if(isset($data['data']))
                                        @foreach($data['data']['data'] as $pkey => $pvalue)
                                        <tr>

                                            <td>
                                                <form action="{{route('floginProcess')}}" method="POST" target="_blank">
                                                    @csrf
                                                    @method('post')
                                                    <input type="hidden" name="wallet_address" value="{{$pvalue['wallet_address']}}">

                                                    <input type="submit" name="submit" value="login" class="btn btn-success">
                                                </form>
                                            </td>
                                            <td>{{$j}}</td>
                                            <td>{{$pvalue['name']}}</td>
                                            <td>{{$pvalue['email']}}</td>
                                            <td>{{$pvalue['wallet_address']}}</td>
                                            <td>{{$pvalue['refferal_code']}}</td>
                                            <td>{{$pvalue['rank']}}</td>
                                            <td>${{$pvalue['roi'] > 0 ? $pvalue['amount'] : 0}}</td>
                                            <td>${{number_format($pvalue['topup_balance'],2)}}</td>
                                            <td>${{number_format($pvalue['tincome'],2)}}</td>
                                            <td>${{number_format($pvalue['twithdraw'],2)}}</td>
                                            <td>${{number_format($pvalue['available_balance'],2)}}</td>
                                            <td>
                                                <form action="{{route('searchMember')}}" method="POST" target="_blank">
                                                    @method('post')
                                                    @csrf
                                                    <input type="hidden" name="member_code" value="{{$pvalue['refferal_code']}}">

                                                    <input type="submit" name="submit" value="Edit" class="btn btn-info">
                                                </form>
                                            </td>
                                        </tr>
                                        @php
                                        $j++;
                                        @endphp
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>

                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="example_paginate">
                                        <ul class="pagination">
                                            @foreach($data['data']['links'] as $key => $value)
                                            @if($value['url'] != null)
                                            <li class="paginate_button page-item @if($value['active'] == " true") active @endif"><a href="{{$value['url']}}" aria-controls="example" data-dt-idx="1" tabindex="0" class="page-link"><?php echo $value['label']; ?></a></li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('includes.footerJs')

    <script>
        $(function() {
            $('.start-date').datepicker({
                dateFormat: 'dd-mm-yy'
            });
            $('.end-date').datepicker({
                dateFormat: 'dd-mm-yy'
            });
        });
    </script>
    @include('includes.footer')