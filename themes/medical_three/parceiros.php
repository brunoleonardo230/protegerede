<div class="main-container">
    <main>
        <!-- Page Banner -->
        <div class="page-banner container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="page-banner-content">
                    <h3>Parceiros</h3>
                </div>
                <div class="banner-content">
                    <ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
                        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemprop="item" title="<?= SITE_NAME; ?>" href="<?= BASE; ?>">
                                <span itemprop="name">Home</span>
                            </a>
                            <meta itemprop="position" content="1"/>
                        </li>

                        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="active">
                            <a itemprop="item" title="Parceiros" href="<?= BASE; ?>/parceiros">
                                <span itemprop="name">Parceiros</span>
                            </a>
                            <meta itemprop="position" content="2"/>
                        </li>
                    </ol>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Page Banner -->

        <!-- Other Services -->
        <div class="other-services container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <!-- Section Header -->
                <div class="section-header">
                    <h3>Outros Serviços</h3>
                </div><!-- Section Header /- -->
                <div class="row srv-box">
                    <?php
                    $Read->ExeRead(DB_SERVICES, "WHERE service_type = 3 AND service_status = 1 ORDER BY service_datecreate ASC LIMIT :limit", "limit=6");
                    if (!$Read->getResult()):
                        echo Erro("Ainda Não Existem Serviços Cadastrados! :)", E_USER_NOTICE);
                    else:
                        foreach ($Read->getResult() as $Service):
                            extract($Service);
                            ?>
                            <div class="col-md-4 col-sm-6 col-xs-6">
                                <div class="other-services-block">
                                    <div class="services-block-icon">
                                        <i class="fa fa-<?= $service_icon_text; ?>"></i>
                                    </div>
                                    <div class="other-services-content">
                                        <h5><?= $service_title; ?></h5>
                                        <p><?= Check::Words($service_content,15); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Other Services -->
    </main>
</div>

