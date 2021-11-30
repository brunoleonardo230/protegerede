<div class="main-container">
    <main>
        <!-- Page Banner -->
        <div class="page-banner container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="page-banner-content">
                    <h3>Galeria</h3>
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
                            <a itemprop="item" title="Galeria" href="<?= BASE; ?>/galeria">
                                <span itemprop="name">Galeria</span>
                            </a>
                            <meta itemprop="position" content="2"/>
                        </li>
                    </ol>
                </div>
            </div><!-- Container /- -->
        </div><!-- Page Banner -->

        <!-- Gallery Section -->
        <div class="gallery-section gallery-section1 container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <ul id="filters" class="portfolio-categories no-left-padding">
                    <li><a data-filter="*" class="active" href="#">Todos</a></li>
                    <?php
                    $Read->ExeRead(DB_GALLERY, "WHERE gallery_date <= NOW() LIMIT :limit", "limit=6");
                    if (!$Read->getResult()):
                        echo Erro("Ainda Não Existem Galerias Cadastradas! :)", E_USER_NOTICE);
                    else:
                        foreach ($Read->getResult() as $Gallery):
                            extract($Gallery);
                            ?>
                            <li><a data-filter=".<?= $gallery_name; ?>" href="#"><?= $gallery_title; ?></a></li>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </ul>

                <ul itemscope itemtype="http://schema.org/ImageGallery" class="portfolio-list no-left-padding">
                    <?php
                    $Read->ExeRead(DB_GALLERY, "WHERE gallery_date <= NOW() LIMIT :limit", "limit=6");
                    if (!$Read->getResult()):
                        echo Erro("Ainda Não Existem Galerias Cadastradas! :)", E_USER_NOTICE);
                    else:
                        foreach ($Read->getResult() as $Gallery):
                            extract($Gallery);

                            $Read->ExeRead(DB_GALLERY_IMAGES, "WHERE gallery_id = :id", "id={$gallery_id}");
                            if (!$Read->getResult()):
                                echo Erro("Ainda Não Existe Imagens Cadastradas. Por Favor, Volte Mais Tarde :)", E_USER_NOTICE);
                            else:
                                foreach ($Read->getResult() as $GalleryImages):
                                    extract($GalleryImages);

                                    require REQUIRE_PATH . '/inc/galeria_item.php';
                                endforeach;
                            endif;
                        endforeach;
                    endif;
                    ?>
                </ul>
            </div> <!-- Container /- -->
        </div> <!-- Gallery Section -->
    </main>
</div>
