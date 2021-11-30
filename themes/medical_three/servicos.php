<div class="main-container">
    <main>
        <!-- Page Banner -->
        <div class="page-banner container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="page-banner-content">
                    <h3>Serviços</h3>
                </div>
                <div class="banner-content">
                    <ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
                        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemprop="item" title="<?= SITE_NAME; ?>" href="<?= BASE; ?>">
                                <span itemprop="name">Home</span>
                            </a>
                            <meta itemprop="position" content="1" />
                        </li>

                        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="active">
                            <a itemprop="item" title="Serviços" href="<?= BASE; ?>/servicos">
                                <span itemprop="name">Serviços</span>
                            </a>
                            <meta itemprop="position" content="2" />
                        </li>
                    </ol>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Page Banner -->

        <!-- Department Section -->
        <div class="department-section department-section2 container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="row">
                    <?php
                    $Read->ExeRead(DB_SPECIALTIES, "WHERE specialtie_status = 1 AND specialtie_datecreate <= NOW() ORDER BY specialtie_datecreate ASC, specialtie_title ASC LIMIT :limit", "limit=12");
                    if (!$Read->getResult()):
                        echo Erro("Ainda Não Existem Especialidades Cadastradas! :)", E_USER_NOTICE);
                    else:
                        foreach ($Read->getResult() as $Specialtie):
                            extract($Specialtie);
                            ?>
                            <div class="col-md-2 col-sm-6 col-xs-6 department-box">
                                <div class="department-img-block">
                                    <img src="<?= BASE; ?>/uploads/<?= $specialtie_image; ?>" title="<?= $specialtie_title; ?>" alt="<?= $specialtie_title; ?>">
                                    <span><?= $specialtie_title; ?></span>
                                </div>
                            </div>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Department Section -->

        <!-- Offer Section -->
        <div class="offer-section container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <!-- Section Header -->
                <div class="section-header">
                    <h3>O Que Oferecemos</h3>
                </div> <!-- Section Header /- -->
                <div class="row">
                    <?php
                    $Read->ExeRead(DB_SERVICES, "WHERE service_type = 2 AND service_status = 1 ORDER BY service_datecreate ASC LIMIT :limit", "limit=3");
                    if (!$Read->getResult()):
                        echo Erro("Ainda Não Existem Serviços Cadastrados! :)", E_USER_NOTICE);
                    else:
                        foreach ($Read->getResult() as $Service):
                            extract($Service);
                            ?>
                            <div class="col-md-4 col-sm-6 col-xs-6">
                                <div class="offer-box">
                                    <i class="fa fa-<?= $service_icon_text; ?>"></i>
                                    <h5><?= $service_title; ?></h5>
                                    <p><?= Check::Words($service_content,15); ?></p>
                                </div>
                            </div>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div> <!-- Container -->
        </div> <!-- Offer Section /- -->

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

        <!-- Extra Services -->
        <div class="extra-services container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <!-- Section Header -->
                <div class="section-header">
                    <h3>Serviços Extras</h3>
                </div><!-- Section Header /- -->
                <div class="row">
                    <?php
                    $Read->ExeRead(DB_SERVICES, "WHERE service_type = 4 AND service_status = 1 ORDER BY service_datecreate ASC LIMIT :limit", "limit=6");
                    if (!$Read->getResult()):
                        echo Erro("Ainda Não Existem Serviços Cadastrados! :)", E_USER_NOTICE);
                    else:
                        foreach ($Read->getResult() as $Service):
                            extract($Service);
                            ?>
                            <div class="col-md-4 col-sm-6 col-xs-6">
                                <div class="extra-box">
                                    <i class="fa fa-<?= $service_icon_text; ?>"></i>
                                    <h5><?= $service_title; ?></h5>
                                    <p><?= Check::Words($service_content,15); ?></p>
                                </div>
                            </div>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Extra Services /- -->

        <!-- Tab Section -->
        <div class="tab-section container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="tab-detail">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs services-tabs" role="tablist">
                        <?php
                        $Read->ExeRead(DB_SPECIALTIES, "WHERE specialtie_status = 1 AND specialtie_datecreate <= NOW() ORDER BY specialtie_datecreate ASC LIMIT :limit", "limit=5");
                        if (!$Read->getResult()):
                            echo Erro("Ainda Não Existem Especialidades Cadastradas! :)", E_USER_NOTICE);
                        else:
                            $i = 1;
                            foreach ($Read->getResult() as $Specialties):
                                extract($Specialties);
                                ?>
                                <li role="presentation" class="<?= ($i == 1 ? 'active' : ''); ?>">
                                    <a href="#tab-<?= $i; ?>" role="tab" data-toggle="tab"><?= $specialtie_title; ?></a>
                                </li>
                                <?php
                                $i++;
                            endforeach;
                        endif;
                        ?>
                    </ul>
                    <!-- Tab Panes -->
                    <div class="tab-content">
                        <?php
                        $Read->ExeRead(DB_SPECIALTIES, "WHERE specialtie_status = 1 AND specialtie_datecreate <= NOW() ORDER BY specialtie_datecreate ASC LIMIT :limit", "limit=5");
                        if (!$Read->getResult()):
                            echo Erro("Ainda Não Existem Especialidades Cadastradas! :)", E_USER_NOTICE);
                        else:
                            $i = 1;
                            foreach ($Read->getResult() as $Specialties):
                                extract($Specialties);
                                ?>
                                <div role="tabpanel" class="tab-pane <?= ($i == 1 ? 'active' : ''); ?>" id="tab-<?= $i; ?>">
                                    <i><img src="<?= BASE; ?>/uploads/<?= $specialtie_image; ?>"
                                            title="<?= $specialtie_title; ?>" alt="<?= $specialtie_title; ?>"/></i>
                                    <h5><?= $specialtie_title; ?></h5>
                                    <p><?= Check::Words($specialtie_content,50); ?></p>
                                </div>
                                <?php
                                $i++;
                            endforeach;
                        endif;
                        ?>
                    </div> <!-- Tab Panes /- -->
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Tab Section -->
    </main>
</div>
	
