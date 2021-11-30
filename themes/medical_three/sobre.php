<div class="main-container">
    <main>
        <!-- Page Banner -->
        <div class="page-banner container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="page-banner-content">
                    <h3>Sobre <?= SITE_NAME; ?></h3>
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
                            <a itemprop="item" title="Sobre <?= SITE_NAME; ?>" href="<?= BASE; ?>/sobre">
                                <span itemprop="name">Sobre <?= SITE_NAME; ?></span>
                            </a>
                            <meta itemprop="position" content="2" />
                        </li>
                    </ol>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Page Banner -->

        <!-- About Section -->
        <div class="about-section container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-sm-7 col-xs-12">
                        <div class="about-content">
                            <h5>Bem Vindo a <?= SITE_NAME; ?></h5>
                            <p>Welcome to our Hospital. Whether you are a patient or a visitor at our hospital, you can
                                expect that over 80 dedicated employees, physicians and volunteers will be working
                                tirelessly to ensure that you receive excellent care in a safe and comfortable
                                environment.</p>
                            <p>As a leading healthcare provider in US, Our Hospital provides quality, compassionate and
                                cost-effective services that continually meet and exceed our patient needs. I hope you
                                will consider the many quality healthcare services available to you at our hospital and
                                off-site facilities and providers. We offer high quality health care, the most advanced
                                technologies and skilled physicians and nurses who are passionate about what they
                                do.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-5 col-xs-12 about-img">
                        <img src="<?= INCLUDE_PATH; ?>/assets/images/about.jpg" title="Bem Vindo a <?= SITE_NAME; ?>" alt="Bem Vindo a <?= SITE_NAME; ?>"/>
                    </div>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- About Section -->

        <!-- Team Section -->
        <div class="team-section container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <!-- Section Header -->
                <div class="section-header">
                    <h3>Doctors</h3>
                </div>
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

        <!-- Testimonial Section -->
        <div class="testimonial-section container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <!-- Section Header -->
                <div class="section-header">
                    <h3>Testimonials</h3>
                </div><!-- Section Header /- -->
            </div>
            <div class="testimonial-slider">
                <?php
                $Read->ExeRead(DB_TESTIMONIALS, "WHERE testimonial_date <= NOW() ORDER BY rand() ASC");
                if (!$Read->getResult()):
                    echo Erro("Ainda Não Existem Depoimentos Cadastrados! :)", E_USER_NOTICE);
                else:
                    foreach ($Read->getResult() AS $Testimonials):
                        extract($Testimonials);
                        ?>
                        <div class="testimonial-box">
                            <div class="testimonial-content">
                                <i>
                                    <img src="<?= BASE; ?>/tim.php?src=uploads/<?= $testimonial_image; ?>&w=100&h=100"
                                        title="<?= $testimonial_name; ?>" alt="<?= $testimonial_name; ?>"/>
                                </i>
                                <h5><?= $testimonial_name; ?></h5>
                                <p><?= Check::Words($testimonial_depoiment, 25); ?></p>
                            </div>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div> <!-- Testimonial Section /- -->

        <!-- Clients -->
        <div class="clients container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <!-- Section Header -->
                <div class="section-header">
                    <h3>Marcas Parceiras</h3>
                </div>
                <div class="clients-carousel">
                    <?php
                    $Read->ExeRead(DB_BRANDS, "WHERE brand_datecreate <= NOW() ORDER BY rand() ASC");
                    if (!$Read->getResult()):
                        echo Erro("Ainda Não Existem Marcas Parceiras Cadastradas! :)", E_USER_NOTICE);
                    else:
                        foreach ($Read->getResult() AS $Brands):
                            extract($Brands);
                            ?>
                            <div class="col-md-12 item">
                                <a href="<?= (!empty($brand_site) ? $brand_site : ''); ?>" title="<?= $brand_name; ?>" target="_blank">
                                    <img src="<?= BASE; ?>/uploads/<?= $brand_image; ?>" title="<?= $brand_name; ?>" alt="<?= $brand_name; ?>"/>
                                </a>
                            </div>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Clients /- -->
    </main>
</div>
	
