<div>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
            <li>
                <a href="#ftp-configuration" data-toggle="tab">
                    FTP Configuration
                </a>
            </li>

            <li class="active">
                <a href="#command" data-toggle="tab">
                    Command
                </a>
            </li>
        <ul>
    </div>
    <div class="tab-content no-padding">
        <div class="tab-pane animated fadeIn" id="ftp-configuration">
            <div class="row mb-0">
                <div class="form-group col-md-6 col-xs-12">
                    <label>Device ID</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                        <input type="text" class="form-control" placeholder="Device ID" value="dapoersulawesi">
                    </div>
                </div>

                <div class="form-group col-md-6 col-xs-12">
                    <label>FTP Host</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-at"></i></span>
                        <input type="text" name="ftp_host" class="form-control" placeholder="FTP Host" value="makassar.dispenda.online">
                    </div>
                </div>

                <div class="form-group col-md-6 col-xs-12">
                    <label>FTP User</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                        <input type="text" name="ftp_user" class="form-control" placeholder="FTP User" value="makassar">
                    </div>
                </div>

                <div class="form-group col-md-6 col-xs-12">
                    <label>FTP Password</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        <input type="password" name="ftp_password" class="form-control" placeholder="FTP Password" value="pajak">
                    </div>
                </div>

                <div class="form-group col-md-6 col-xs-12">
                    <label>FTP Home</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-home"></i></span>
                        <input type="text" class="form-control" placeholder="FTP Home" value="/data/dapoersulawesi" disabled>
                        <input type="hidden" name="ftp_home" value="/data/dapoersulawesi_pattimura">
                    </div>
                </div>

                <div class="form-group col-md-6 col-xs-12">
                    <label>&nbsp;</label>

                    <button class="btn btn-success btn-block">
                        UPDATE
                    </button>
                </div>
            </div>
        </div>

        <div class="tab-pane animated fadeIn active" id="command">
            <div style="display: inline-flex; flex-wrap: wrap; gap: 10px; width: 100%;">
                <button wire:click="updateUseDNS" class="btn btn-success">
                    AUTO INTEGRATOR
                </button>

                <button class="btn btn-info">
                    GRABBER SCRIPT
                </button>

                <button class="btn btn-primary">
                    MANAGE CRONTAB
                </button>

                <button class="btn btn-warning">
                    FORCE SEND DATA
                </button>

                <button wire:click="restartDevice" class="btn btn-danger">
                    RESTART
                </button>
            </div>
        </div>
    </div>


    {{-- <label class="text-primary">
        <p class="mb-0">&mdash; Configurations</p>
    </label>



    <hr class="mt-0">

    <label class="text-primary">
        <p class="mb-0">&mdash; Command List</p>
    </label>

    <div style="display: inline-flex; flex-wrap: wrap; gap: 10px; width: 100%;">
        <button wire:click="updateUseDNS" class="btn btn-success">
            AUTO INTEGRATOR
        </button>

        <button class="btn btn-info">
            UPDATE PARSER SCRIPT
        </button>

        <button class="btn btn-primary">
            MANAGE CRONTAB
        </button>

        <button class="btn btn-warning">
            FORCE SEND DATA
        </button>

        <button wire:click="restartDevice" class="btn btn-danger">
            RESTART
        </button>
    </div> --}}
</div>

@push('bottom')
<script>
    Livewire.on('setAlert', msg => {
        new swal({
                title: msg,
                icon:'info',
                allowOutsideClick:true,
            });
    })
</script>
@endpush
