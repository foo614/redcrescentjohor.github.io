<script>
    @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}";
        switch(type){
            case 'info':
                toastr.info("{{ Session::get('message') }}", "{{ Session::get('title') }}");
                break;
            
            case 'warning':
                toastr.warning("{{ Session::get('message') }}", "{{ Session::get('title') }}" );
                break;
    
            case 'success':
                toastr.success("{{ Session::get('message') }}", "{{ Session::get('title') }}");
                break;
    
            case 'error':
                toastr.error("{{ Session::get('message') }}", "{{ Session::get('title') }}");
                break;
        }
    @endif
</script>