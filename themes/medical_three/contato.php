<div class="main-container">
    <main>
        <!-- Page Banner -->
        <div class="page-banner container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="page-banner-content">
                    <h3>Contato</h3>
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
                            <a itemprop="item" title="Contato" href="<?= BASE; ?>/contato">
                                <span itemprop="name">Contato</span>
                            </a>
                            <meta itemprop="position" content="2" />
                        </li>
                    </ol>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Page Banner -->

        <!-- Contact Us -->
        <div class="contact-us container-fluid no-left-padding no-right-padding">
            <!-- Container -->
            <div class="container">
                <div class="contact-header">
                    <h5>Fale Conosco</h5>
                    <p></p>
                </div>
                <div class="contact-form">
                    <h5>Enviar Mensagem</h5>
                    <form class="row contato-form" name="contact_form" method="post" action="" enctype="multipart/form-data">
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                            <input type="text" name="name" placeholder="Nome" class="form-control" required/>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                            <input type="text" name="email" placeholder="E-mail" class="form-control" required/>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                            <input type="text" name="phone" placeholder="Telefone" class="form-control"/>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                            <input type="text" name="subject" placeholder="Assuto" class="form-control"/>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <textarea name="message" rows="6" placeholder="Mensagem" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <button type="submit" title="Enviar Mensagem" >Enviar</button>
                        </div>
                    </form>
                    <div style="display: none;" class="wc_contact_sended_dark jwc_contact_sended">
                        <p class="h2"><span>&#10003;</span> Solicitação Enviada Com Sucesso!</p>
                        <p><b>Prezado(a) <span class="jwc_contact_sended_name">NOME</span>. Obrigado Pelo Contato,</b></p>
                        <p>Informamos que recebemos sua mensagem, e que vamos responder o mais breve possível.</p>
                        <p><em>Atenciosamente <?= SITE_NAME; ?>.</em></p>
                        <span title="Fechar" class="btn btn_red jwc_contact_close" style="margin-top: 20px;">FECHAR</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="contact-call-box">
                            <p>
                                <i class="fa fa-phone"></i>
                                <a href="tel:<?= SITE_ADDR_PHONE_A; ?>" title="<?= SITE_ADDR_PHONE_A; ?>">
                                    <?= SITE_ADDR_PHONE_A; ?>
                                </a>
                            </p>
                        </div>
                        <div class="contact-call-box">
                            <p>
                                <i class="fa fa-envelope"></i>
                                <a href="mailto:<?= SITE_ADDR_EMAIL; ?>" title="<?= SITE_ADDR_EMAIL; ?>">
                                    <?= SITE_ADDR_EMAIL; ?>
                                </a>
                            </p>
                        </div>
                        <div class="contact-call-box">
                            <p>
                                <i class="fa fa-map-marker"></i>
                                <?= SITE_ADDR_ADDR; ?>
                            </p>
                        </div>
                        <div class="contact-call-box">
                            <p><i class="fa fa-heart"></i></p>
                            <ul>
                                <li><a title="<?= SITE_NAME; ?> No Facebook" href="//www.facebook.com/<?= SITE_SOCIAL_FB_PAGE; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li><a title="<?= SITE_NAME; ?> No Instagram" href="//www.instagram.com/<?= SITE_SOCIAL_INSTAGRAM; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                <li><a title="<?= SITE_NAME; ?> No Twitter" href="//www.twitter.com/<?= SITE_SOCIAL_TWITTER; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li><a title="<?= SITE_NAME; ?> No Google" href="//plus.google.com/<?= SITE_SOCIAL_GOOGLE_PAGE; ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                                <li><a title="<?= SITE_NAME; ?> No Linkedin" href="//www.linkedin.com/<?= SITE_SOCIAL_LINKEDIN; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-6 col-xs-12">
                        <!-- Map Section -->
                        <div class="map">
                            <div class="map-canvas" id="map-canvas-contact" data-lat="-37.817214" data-lng="144.955925"
                                 data-string="<?= SITE_ADDR_ADDR; ?>" data-zoom="10"></div>
                        </div>
                        <!--  Map Section /- -->
                    </div>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- Contact Us /- -->
    </main>
</div>
	
