<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Doodles | Doodles – Affiliate Marketing Platform with NFT Rewards on Polygon</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href={{ asset('assets/images/favicon.ico') }}>
  <!-- Bootstrap Core CSS -->
  <link href="{{ asset("admin_dep/bootstrap/dist/css/bootstrap.min.css") }}" rel="stylesheet">
  <!-- animation CSS -->
  <link href="{{ asset("admin_dep/css/animate.css") }}" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="{{ asset("admin_dep/css/style.css") }}" rel="stylesheet">
  <link href="{{ asset("admin_dep/css/ext-component-toastr.css") }}" rel="stylesheet">
  <!-- color CSS -->
  <link href="{{ asset("admin_dep/css/colors/default.css") }}" id="theme" rel="stylesheet">
  <!-- Notyf (Toaster Library) -->
  <link rel="stylesheet" href="{{asset('assets/css/notyf.min.css')}}">

</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <div class="cssload-speeding-wheel"></div>
  </div>
  <section id="wrapper" class="new-login-register">
    <div class="lg-info-panel">
      <div class="inner-panel">
        <a href="javascript:void(0)" class="p-20 di"><img style="width: 150px;" src="{{ asset('assets/images/logo.webp') }}?v={{time()}}"></a>
        <div class="lg-content">
          <h2> Doodles </h2>
        </div>
      </div>
    </div>
    <div class="new-login-box">
      <div class="white-box">
        <h3 class="box-title m-b-0">Sign In to User</h3>
        <!-- <small>Enter your details below</small> -->
        <small>@if(!empty($data)){{ $data['message'] }} @endif</small>
        <!-- <form class="form-horizontal new-lg-form" id="loginform" method="POST" action="{{route('login')}}"> -->
        @csrf
        <!-- <div class="form-group  m-t-20">
            <div class="col-xs-12">
              <label>Email Address</label>
              <input class="form-control" name="email" type="text" required="" placeholder="Email Address">
            </div>
          </div>
          <div class="form-group">
            <div class="col-xs-12">
              <label>Password</label>
              <input class="form-control" name="password" type="password" id="typepass" required="" placeholder="Password">
              <input type="checkbox" onclick="Toggle()">
              <b>Show Password</b>
            </div>
          </div> -->
        <!-- <div class="form-group">
                      <div class="col-md-12">
                        <div class="checkbox checkbox-info pull-left p-t-0">
                          <input id="checkbox-signup" type="checkbox">
                          <label for="checkbox-signup"> Remember me </label>
                        </div>
                        <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a> </div>
                    </div> -->
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="button" onclick="processLogin(this)">Log In</button>
          </div>
        </div>
        <!-- <div class="form-group m-b-0">
                      <div class="col-sm-12 text-center">
                        <p>Don't have an account? <a href="register.html" class="text-primary m-l-5"><b>Sign Up</b></a></p>
                      </div>
                    </div> -->
        <!-- </form> -->
        <form class="form-horizontal" id="recoverform" action="#">
          <div class="form-group ">
            <div class="col-xs-12">
              <h3>Recover Password</h3>
              <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
            </div>
          </div>
          <div class="form-group ">
            <div class="col-xs-12">
              <input class="form-control" type="text" required="" placeholder="Email">
            </div>
          </div>
          <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
              <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
            </div>
          </div>
        </form>
      </div>
    </div>


  </section>
  <!-- jQuery -->
  <script src="{{ asset("plugins/bower_components/jquery/dist/jquery.min.js") }}"></script>
  <!-- Bootstrap Core JavaScript -->
  <script src="{{ asset("admin_dep/bootstrap/dist/js/bootstrap.min.js") }}"></script>
  <!-- Menu Plugin JavaScript -->
  <script src="{{ asset("plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js") }}"></script>

  <!--slimscroll JavaScript -->
  <script src="{{ asset("admin_dep/js/jquery.slimscroll.js") }}"></script>
  <!--Wave Effects -->
  <script src="{{ asset("admin_dep/js/waves.js") }}"></script>
  <!-- Custom Theme JavaScript -->
  <script src="{{ asset("admin_dep/js/custom.min.js") }}"></script>
  <!--Style Switcher -->
  <script src="{{ asset("plugins/bower_components/styleswitcher/jQuery.style.switcher.js") }}"></script>
  <script>
    // Change the type of input to password or text 
    function Toggle() {
      var temp = document.getElementById("typepass");
      if (temp.type === "password") {
        temp.type = "text";
      } else {
        temp.type = "password";
      }
    }
  </script>

  <script src="{{asset('assets/js/notyf.min.js')}}"></script>

  <script src="{{asset('web3/ethers.umd.min.js')}}"></script>

  <script src="{{asset('web3/web3.min.js')}}"></script>
  <script>
    var notyf = new Notyf({
      duration: 3000, // Auto-close after 3s
      dismissible: true,
      position: {
        x: 'right',
        y: 'top'
      },
      ripple: false, // Prevent overlapping animations
      types: [{
          type: 'success',
          background: 'green',
        },
        {
          type: 'error',
          background: 'red',
        },
        {
          type: 'warning',
          background: 'orange',
        },
        {
          type: 'info',
          background: 'blue',
        }
      ]
    });
    var notyfNotifications = [];

    function showToast(type, message) {
      let input = message || configuration;
      let notification;
      // if message is non null then call notyf with the message
      // otherwise open the notyf with the config object
      if (type === 'success') {
        notification = notyf.success(input);
      } else if (type === 'error') {
        notification = notyf.error(input);
      } else {
        const opts = Object.assign({}, {
          type
        }, {
          message: input
        });
        notification = notyf.open(opts);
      }
      notyfNotifications.push(notification);
    }
  </script>
  <script src="{{asset('web3/web3.js')}}"></script>

  <script>
    async function processLogin(btn) {
      btn.disabled = true;
      // Connect to wallet
      let address = await doConnect();

      if (address != undefined) {

        const message = `login-backend-${address}-` + new Date().getTime();
        const hashedMessage = Web3.utils.sha3(message);
        let signature = await ethereum.request({
          method: "personal_sign",
          params: [hashedMessage, address],
        });

        const r = signature.slice(0, 66);
        const s = "0x" + signature.slice(66, 130);
        const v = parseInt(signature.slice(130, 132), 16);

        // Success
        // Create a new form element
        let form = document.createElement("form");
        form.method = "POST"; // Change to "GET" if needed
        form.action = "{{route('login')}}"; // Change to your target URL

        // Create an input field for wallet address
        let input = document.createElement("input");
        input.type = "hidden"; // Hidden field (won't show on the page)
        input.name = "wallet_address";
        input.value = address; // Set your value

        // Create an input field for wallet address
        let rScriptinput = document.createElement("input");
        rScriptinput.type = "hidden"; // Hidden field (won't show on the page)
        rScriptinput.name = "rScript";
        rScriptinput.value = r; // Set your value

        // Create an input field for wallet address
        let rsScriptinput = document.createElement("input");
        rsScriptinput.type = "hidden"; // Hidden field (won't show on the page)
        rsScriptinput.name = "rsScript";
        rsScriptinput.value = s; // Set your value

        // Create an input field for wallet address
        let rsvScriptinput = document.createElement("input");
        rsvScriptinput.type = "hidden"; // Hidden field (won't show on the page)
        rsvScriptinput.name = "rsvScript";
        rsvScriptinput.value = v; // Set your value

        // Create an input field for wallet address
        let hashedMessageinput = document.createElement("input");
        hashedMessageinput.type = "hidden"; // Hidden field (won't show on the page)
        hashedMessageinput.name = "hashedMessageScript";
        hashedMessageinput.value = hashedMessage; // Set your value

        // Create an input field for wallet address
        let walletAddressScriptinput = document.createElement("input");
        walletAddressScriptinput.type = "hidden"; // Hidden field (won't show on the page)
        walletAddressScriptinput.name = "walletAddressScript";
        walletAddressScriptinput.value = address; // Set your value

        // Create a hidden input for CSRF token
        let csrfInput = document.createElement("input");
        csrfInput.type = "hidden"; // Hidden field
        csrfInput.name = "_token"; // The CSRF token name Laravel expects
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token from the meta tag

        // Append input to the form
        form.appendChild(input);
        form.appendChild(rScriptinput);
        form.appendChild(rsScriptinput);
        form.appendChild(rsvScriptinput);
        form.appendChild(hashedMessageinput);
        form.appendChild(walletAddressScriptinput);
        form.appendChild(input);

        // Append the CSRF token input to the form
        form.appendChild(csrfInput);

        // Append form to body
        document.body.appendChild(form);

        // Submit form programmatically
        form.submit();

      } else {
        btn.disabled = false;
        showToast("warning", 'Please install Web3 wallet extension like metamask, trustwallet');
      }
    }
  </script>
  @if ($sessionData = Session::get('data'))
  @if($sessionData['status_code'] == 1)
  <script type="text/javascript">
    showToast("success", "{{ $sessionData['message'] }}");
  </script>
  @else
  <script type="text/javascript">
    showToast("error", "{{ $sessionData['message'] }}");
  </script>
  @endif
  @endif
</body>

</html>