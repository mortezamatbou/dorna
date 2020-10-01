
        </div>
        
        <div id="message-to-user" style="position: fixed; left: 10px; bottom: 20px; max-width: 95%;">
            
        </div>
        
        <footer style="text-align: center;position: fixed; width: 100%; bottom: 0px; left: 0px; box-sizing: border-box; padding: 5px; background-color: rgba(250,250,250, .9);">
            &copy; All rights reserved by <b><a target="_blank" href="https://gilaki.net">gilaki.net</a></b> <span style="font-size: 12px">2018 - <?= date("Y") ?></span>
        </footer>
        
        <script type="text/javascript">
            // init some variables
            var baseUrl = "<?= host_url() ?>";
            var host = "<?= base_url() ?>";
            var token = "<?= get_instance()->token ?>";
            var route = "<?= get_instance()->input->get_route() ?>";
            var routeW = "<?= get_instance()->input->get_route(TRUE) ?>";
        </script>
        <script src="<?= base_url(); ?>f/handle.js" type="text/javascript"></script>
    </body>
    
</html>
