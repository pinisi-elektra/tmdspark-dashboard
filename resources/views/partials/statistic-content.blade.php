<div class="row">
    <div class="col-xs-12 animated fadeIn">
        <legend>Statistik Konten</legend>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 animated fadeIn">
                <div class="info-box bg-green">
                    <span class="info-box-icon">
                        <i class="fas fa-check-circle"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Portofolio</span>
                        <span class="info-box-number">
                            <h3>
                                <span class="countUp">{{ number_format($content_projects) }}</span>
                            </h3>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 animated fadeIn">
                <div class="info-box bg-red">
                    <span class="info-box-icon">
                        <i class="fas fa-times-circle"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Tulisan</span>
                        <span class="info-box-number">
                            <h3>
                                <span class="countUp">{{ number_format($content_posts) }}</span>
                            </h3>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 animated fadeIn">
                <div class="info-box bg-blue">
                    <span class="info-box-icon">
                        <i class="fas fa-times-circle"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Klien</span>
                        <span class="info-box-number">
                            <h3>
                                <span class="countUp">{{ number_format($content_clients) }}</span>
                            </h3>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
