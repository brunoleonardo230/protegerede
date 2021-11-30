<!-- Footer Main -->
<footer itemscope itemtype="http://schema.org/WPHeader" id="footer-main" class="footer-main container-fluid no-left-padding no-right-padding">
    <!-- Container -->
    <div itemscope itemtype="http://schema.org/MedicalEntity" class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-6 contact-info">
                <aside class="widget widget_info">
                    <div style="display: none;">
                        <span itemprop="name"><?= SITE_NAME; ?></span>
                        <span itemprop="image"><img src=""></span>
                    </div>
                    <h3 class="widget-title">Contatos</h3>
                    <p><i class="fa fa-phone"></i><a itemprop="telephone" href="tel:<?= SITE_ADDR_PHONE_A; ?>" title="<?= SITE_ADDR_PHONE_A; ?>"><?= SITE_ADDR_PHONE_A; ?></a></p>
                    <p><i class="fa fa-envelope"></i><a itemprop="email" href="mailto:<?= SITE_ADDR_EMAIL; ?>" title="<?= SITE_ADDR_EMAIL; ?>"><?= SITE_ADDR_EMAIL; ?></a></p>
                    <p itemscope itemtype="http://schema.org/PostalAddress" itemprop="address">
                        <i class="fa fa-map-marker"></i>
                        <span itemprop="streetAddress"><?= SITE_ADDR_ADDR; ?></span>
                        <span itemprop="addressRegion" class="ds_none"><?= SITE_ADDR_CITY; ?></span>
                        <span itemprop="addressCountry" class="ds_none"><?= SITE_ADDR_UF; ?></span>
                    </p>
                    <div class="footer-social">
                        <i class="fa fa-heart"></i>
                        <ul>
                            <li><a title="<?= SITE_NAME; ?> No Facebook" href="//www.facebook.com/<?= SITE_SOCIAL_FB_PAGE; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                            <li><a title="<?= SITE_NAME; ?> No Instagram" href="//www.instagram.com/<?= SITE_SOCIAL_INSTAGRAM; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                            <li><a title="<?= SITE_NAME; ?> No Twitter" href="//www.twitter.com/<?= SITE_SOCIAL_TWITTER; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li><a title="<?= SITE_NAME; ?> No Google" href="//plus.google.com/<?= SITE_SOCIAL_GOOGLE_PAGE; ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                            <li><a title="<?= SITE_NAME; ?> No Linkedin" href="//www.linkedin.com/<?= SITE_SOCIAL_LINKEDIN; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </aside>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
                <aside class="widget widget_workinghours">
                    <span><i class="fa fa-clock-o"></i></span>
                    <h3 class="widget-title">Funcionamento</h3>
                    <ul>
                        <li><span>Segunda-Feira</span> <b>7:00 - 21:00</b></li>
                        <li><span>Terça-Feira</span> <b>7:00 - 21:00</b></li>
                        <li><span>Quarta-Feira</span> <b>7:00 - 21:00</b></li>
                        <li><span>Quinta-Feira</span> <b>7:00 - 21:00</b></li>
                        <li><span>Sexta-Feira</span> <b>7:00 - 21:00</b></li>
                        <li><span>Sábado</span> <b>8:00 - 19:00</b></li>
                        <li><span>Domingo</span> <b>Fechado</b></li>
                    </ul>
                </aside>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6 contact-form">
                <h3 class="widget-title">Fale Conosco</h3>
                <form name="contact_form" class="contato-form" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="phone">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Nome" required />
                    </div>
                    <div class="form-group">
                        <input type="text" name="email" class="form-control" placeholder="E-mail" required />
                    </div>
                    <div class="form-group">
                        <input type="text" name="subject" class="form-control" placeholder="Assunto" />
                    </div>
                    <div class="form-group">
                        <textarea name="message" class="form-control" placeholder="Mensagem" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" title="Enviar Mensagem" >Enviar</button>
                    </div>
                </form>
                <div style="display: none;" class="wc_contact_sended jwc_contact_sended">
                    <p class="h2"><span>&#10003;</span> Solicitação Enviada Com Sucesso!</p>
                    <p><b>Prezado(a) <span class="jwc_contact_sended_name">NOME</span>. Obrigado Pelo Contato,</b></p>
                    <p>Informamos que recebemos sua mensagem, e que vamos responder o mais breve possível.</p>
                    <p><em>Atenciosamente <?= SITE_NAME; ?>.</em></p>
                    <span title="Fechar" class="btn btn_red jwc_contact_close" style="margin-top: 20px;">FECHAR</span>
                </div>
            </div>
        </div>
    </div><!-- Container /- -->
</footer><!-- Footer Main /- -->

<div class="footer-bottom">
    <!-- Container -->
    <div class="container">
        <p>Copyright ® <?= date('Y'); ?> - Todos os Direitos Reservados - <a class="copyright" title="<?= SITE_NAME; ?>" href="<?= BASE; ?>"><?= SITE_NAME; ?></a></p>
        <p>Desenvolvido Com <span class="fa fa-heart heart"></span> Por <a class="copyright" title="GbTechWeb" target="_blank" href="https://www.gbtechweb.com.br">GbTechWeb</a></p>
    </div><!-- Container /- -->
</div>