<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-{{ cbLang('right') }} hidden-xs">
        {{ cbLang('powered_by') }} {{CRUDBooster::getSetting('appname')}}
    </div>
    <!-- Default to the left -->
    {{ cbLang('copyright') }} &copy; <?php echo date('Y') ?>. {{ cbLang('all_rights_reserved') }}.
</footer>
