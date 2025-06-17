<footer class="footer text-center"> {{date('Y')}} &copy;  Doodles. </footer>
</div>

<!-- /#wrapper -->
<!-- jQuery -->
<script src="{{ asset("/plugins/bower_components/jquery/dist/jquery.min.js") }}"></script>
<script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/ample-bootstrap/package/assets/extra-libs/taskboard/js/jquery-ui.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset("/admin_dep/bootstrap/dist/js/bootstrap.min.js") }}"></script>
<!-- Menu Plugin JavaScript -->
<script src="{{ asset("/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js") }}"></script>
<!--slimscroll JavaScript -->
<script src="{{ asset("/admin_dep/js/jquery.slimscroll.js") }}"></script>
<!--Wave Effects -->
<script src="{{ asset("/admin_dep/js/waves.js") }}"></script>
<!-- chartist chart -->
<script src="{{ asset("/plugins/bower_components/chartist-js/dist/chartist.min.js") }}"></script>
<script src="{{ asset("/plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js") }}"></script>
<!-- Sparkline chart JavaScript -->
<script src="{{ asset("/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js") }}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ asset("/admin_dep/js/custom.min.js") }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!--Style Switcher -->
<script src="{{ asset("/plugins/bower_components/styleswitcher/jQuery.style.switcher.js") }}"></script>

<script src="{{ asset("/plugins/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js") }}"></script>
<script src="{{ asset("plugins/bower_components/jquery.easy-pie-chart/easy-pie-chart.init.js") }}"></script>
<script src="{{ asset("plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js") }}"></script>

<!--<script src="{{ asset("/admin_dep/js/sweetalert.min.js") }}"></script>-->

<script>
// Date Picker
jQuery('.mydatepicker, #datepicker').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
});
jQuery('#datepicker-autoclose').datepicker({
    autoclose: true,
    todayHighlight: true
});
jQuery('#date-range').datepicker({
    toggleActive: true
});
jQuery('#datepicker-inline').datepicker({
    todayHighlight: true
});
</script>

<!-- Clock Plugin JavaScript -->
<script src="{{ asset("plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js") }}"></script>

<script>
// Clock pickers
$('#single-input').clockpicker({
    placement: 'bottom',
    align: 'left',
    autoclose: true,
    'default': 'now'
});
$('.clockpicker').clockpicker({
    donetext: 'Done',
}).find('input').change(function () {
    console.log(this.value);
});
$('#check-minutes').click(function (e) {
    // Have to stop propagation here
    e.stopPropagation();
    input.clockpicker('show').clockpicker('toggleView', 'minutes');
});
function confirmDelete() {
    var txt;
    var r = confirm("Are you sure ?");
    if (r == true) {
        return true;
    } else {
        return false;
    }
//    document.getElementById("demo").innerHTML = txt;
}

</script>


<script language="javascript">
function printdiv(printpage)
{
var headstr = "<html><head><title></title></head><body>";
var footstr = "</body>";
var newstr = document.getElementById(printpage).innerHTML;
var oldstr = document.body.innerHTML;
document.body.innerHTML = headstr+newstr+footstr;
window.print();
document.body.innerHTML = oldstr;
return false;
}
</script>

<script src="{{ asset("/admin_dep/js/ajax.js") }}"></script>


<script src="{{ asset("/plugins/bower_components/datatables/datatables.min.js") }}"></script>
<!-- start - This is for export functionality only -->
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

<script src="{{asset('web3/ethers.umd.min.js')}}"></script>

<script src="{{asset('web3/web3.min.js')}}"></script>

<script>
    @if(Session::has('admin_user_id'))

    async function checkWalletAddress() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        // Get the stored address from wherever it's stored (e.g., local storage)
        var storedAddress = "{{ Session::get('admin_wallet_address') }}"

        // Get the connected wallet address
        var addressConnected = await window.ethereum.request({method: 'eth_requestAccounts'}); // Replace with your code to get the connected address

        // Compare the stored and connected addresses
        if (storedAddress.toLowerCase() !== addressConnected[0].toLowerCase()) {
            // Call your function or perform the desired action
            // handleAccountChange(addressConnected); // Replace with the function you want to call
            alert("Wallet Address Mismatch! Please connect the correct wallet address.");

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{route('flogout')}}';

            // Add CSRF token
            var token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = csrfToken;
            form.appendChild(token);

            document.body.appendChild(form);
            setTimeout(function () {
                form.submit();
            }, 300);
        }
    }

    setInterval(checkWalletAddress, 1500); // Call checkWalletAddress() every 5 seconds (5000 milliseconds)

    @endif
</script>