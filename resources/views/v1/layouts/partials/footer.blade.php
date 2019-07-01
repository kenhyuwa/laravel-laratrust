<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 0.0.1
    </div>
    <strong>Copyright &copy; 2018-{{ date('Y') }} <a href="">{{ __app_name() }}</a>.</strong>
    <span class="hidden-xs">All rights reserved.</span>
</footer>
<div class="button-to-top">
    <a href="#" id="back-to-top">
      <i class="material-icons">arrow_upward</i>
    </a>
</div>
@push('js')
<script>
    const idle = new idleJs({
       	idle: 7200000, // idle time in ms
      	events: ['mousemove', 'keydown', 'mousedown', 'touchstart'], // events that will trigger the idle resetter
      	onIdle: function () {
        	console.log('idle');
      	}, // callback function to be executed after idle time
      	onActive: function () {}, // callback function to be executed after back form idleness
      	onHide: function () {}, // callback function to be executed when window become hidden
      	onShow: function () {}, // callback function to be executed when window become visible
      	keepTracking: true, // set it to false of you want to track only once
      	startAtIdle: false // set it to true if you want to start in the idle state
    }).start();
</script>
@endpush