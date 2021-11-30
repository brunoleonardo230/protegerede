<div class="main-container">
    <main>
        <!-- Page Banner -->
        <div class="page-banner container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="page-banner-content">
                    <h3>Médicos</h3>
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
                            <a itemprop="item" title="Médicos" href="<?= BASE; ?>/medicos">
                                <span itemprop="name">Médicos</span>
                            </a>
                            <meta itemprop="position" content="2" />
                        </li>
                    </ol>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Page Banner -->

        <!-- Team Section -->
        <div class="team-section container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="team-carousel">
                    <?php
                    $Read->ExeRead(DB_DOCTORS, "WHERE doctor_status = :s ORDER BY rand() ASC LIMIT :limit", "s=1&limit=12");
                    if (!$Read->getResult()):
                        echo Erro("Ainda Não Existem Médicos Cadastrados! :)", E_USER_NOTICE);
                    else:
                        foreach ($Read->getResult() as $Doctor):
                            extract($Doctor);

                            require REQUIRE_PATH . '/inc/medico_item.php';
                        endforeach;
                    endif;
                    ?>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Team Section /- -->
    </main>
</div>

