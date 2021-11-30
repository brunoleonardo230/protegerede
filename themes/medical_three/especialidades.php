<div class="main-container">
    <main>
        <!-- Page Banner -->
        <div class="page-banner container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="page-banner-content">
                    <h3>Especialidades</h3>
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
                            <a itemprop="item" title="Especialidades" href="<?= BASE; ?>/especialidades">
                                <span itemprop="name">Especialidades</span>
                            </a>
                            <meta itemprop="position" content="2"/>
                        </li>
                    </ol>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Page Banner -->

        <!-- Department Section -->
        <div class="department-section container-fluid no-left-padding no-right-padding">
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
    </main>
</div>

