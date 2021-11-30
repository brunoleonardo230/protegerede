<!-- A EMPRESA -->
<?php if (APP_COMPANY && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_COMPANY): ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'company/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class=" icon-office" title="A Empresa" href="dashboard.php?wc=company/home">A Empresa</a></li>
<?php endif; ?>

<!-- ESPECIALIDADES -->
<?php if (APP_SPECIALTIES && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_SPECIALTIES):
    $wc_specialties_alerts = null;
    $Read->FullRead("SELECT count(specialtie_id) as total FROM " . DB_SPECIALTIES . " WHERE specialtie_status != 1");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_specialties_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'especialidades/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-lab" title="Especialidades" href="dashboard.php?wc=especialidades/home">Especialidades<?= $wc_specialties_alerts; ?></a></li>
<?php endif; ?>

<!-- MÉDICOS -->
<?php if (APP_DOCTORS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_DOCTORS): ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'medicos/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-user-tie" title="Médicos" href="dashboard.php?wc=medicos/home">Médicos</a>
        <ul class="dashboard_nav_menu_sub">
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'medicos/home' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Ver Médicos" href="dashboard.php?wc=medicos/home">&raquo; Ver Médicos</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'medicos/create' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Novo Médico" href="dashboard.php?wc=medicos/create">&raquo; Novo Médico</a></li>
        </ul>
    </li>
<?php endif; ?>

<!-- SERVIÇOS -->
<?php if (APP_SERVICES && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_SERVICES):
    $wc_services_alerts = null;
    $Read->FullRead("SELECT count(service_id) as total FROM " . DB_SERVICES . " WHERE service_status != 1");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_services_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'servicos/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-briefcase" title="Serviços" href="dashboard.php?wc=servicos/home">Serviços<?= $wc_services_alerts; ?></a>
        <ul class="dashboard_nav_menu_sub">
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'servicos/home' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Ver Serviços" href="dashboard.php?wc=servicos/home">&raquo; Ver Serviços</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'servicos/create' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Novo Serviço" href="dashboard.php?wc=servicos/create">&raquo; Novo Serviço</a></li>
        </ul>
    </li>
<?php endif; ?>

<!-- POSTS -->
<?php if (APP_POSTS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_POSTS):
    $wc_posts_alerts = null;
    $Read->FullRead("SELECT count(post_id) as total FROM " . DB_POSTS . " WHERE post_status != 1");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_posts_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'posts/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-blog" title="Posts" href="dashboard.php?wc=posts/home">Posts <?= $wc_posts_alerts; ?></a>
        <ul class="dashboard_nav_menu_sub">
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'posts/home' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Ver Posts" href="dashboard.php?wc=posts/home">&raquo; Ver Posts <?= $wc_posts_alerts; ?></a></li>
            <li class="dashboard_nav_menu_sub_li <?= strstr($getViewInput, 'posts/categor') ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Categorias" href="dashboard.php?wc=posts/categories">&raquo; Categorias</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'posts/create' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Novo Post" href="dashboard.php?wc=posts/create">&raquo; Novo Post</a></li>
        </ul>
    </li>
<?php endif; ?>

<!-- PÁGINAS -->
<?php if (APP_PAGES && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_PAGES):
    $wc_pages_alerts = null;
    $Read->FullRead("SELECT count(page_id) as total FROM " . DB_PAGES . " WHERE page_status != 1");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_pages_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'pages/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-pagebreak" title="Páginas" href="dashboard.php?wc=pages/home">Páginas<?= $wc_pages_alerts; ?></a></li>
<?php endif; ?>

<!-- AGENDAMENTOS -->
<?php if (APP_SCHEDULES && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_SCHEDULES):
    $wc_schedules_alerts = null;
    $Read->FullRead("SELECT count(consultation_id) as total FROM " . DB_CONSULTATIONS . " WHERE consultation_status = 1");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_schedules_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'marcacoes/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-mail2" title="Marcações de Consultas" href="dashboard.php?wc=marcacoes/home">Marcações <?= $wc_schedules_alerts; ?></a></li>
<?php endif; ?>

<!-- COMENTÁRIOS -->
<?php if (APP_COMMENTS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_COMMENTS):
    $wc_comment_alerts = null;
    $Read->FullRead("SELECT count(id) as total FROM " . DB_COMMENTS . " WHERE status != 1 AND alias_id IS NULL");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_comment_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'comments/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-bubbles2" title="Comentários" href="dashboard.php?wc=comments/home">Comentários<?= $wc_comment_alerts; ?></a></li>
<?php endif; ?>

<!-- EAD -->
<?php if (APP_EAD):
    $wc_ead_courses_alerts = null;
    $Read->FullRead("SELECT count(course_id) as total FROM " . DB_EAD_COURSES . " WHERE course_status != 1");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_ead_courses_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;

    $wc_ead_support_alerts = null;
    $Read->FullRead("SELECT count(support_id) as total FROM " . DB_EAD_SUPPORT . " WHERE support_status = 1");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_ead_support_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;

    $wc_ead_orders_alerts = null;
    $Read->FullRead("SELECT count(order_id) as total FROM " . DB_EAD_ORDERS . " WHERE order_status = 'chargeback'");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_ead_orders_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;

    $SupportEadStatus = filter_input(INPUT_GET, 'status', FILTER_VALIDATE_INT);
    $OrdersEadStatus = filter_input(INPUT_GET, 'status', FILTER_DEFAULT);
    ?>
    <?php if ($Admin['user_level'] >= LEVEL_WC_EAD_COURSES): ?>
        <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'teach/courses') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-books" title="Cursos" href="dashboard.php?wc=teach/courses">Cursos<?= $wc_ead_courses_alerts; ?></a>
            <ul class="dashboard_nav_menu_sub">
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/courses' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Todos os Cursos" href="dashboard.php?wc=teach/courses">&raquo; Todos os Cursos</a></li>
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/courses_segments' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Segmentos de Cursos" href="dashboard.php?wc=teach/courses_segments">&raquo; Segmentos</a></li>
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/courses_create' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Cadastrar Novo Curso" href="dashboard.php?wc=teach/courses_create">&raquo; Novo Curso</a></li>
            </ul>
        </li>
    <?php endif; ?>
    
    <?php if ($Admin['user_level'] >= LEVEL_WC_EAD_STUDENTS): ?><li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'teach/students') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-user-check" title="Alunos" href="dashboard.php?wc=teach/students">Alunos</a></li><?php endif; ?>
    <?php if ($Admin['user_level'] >= LEVEL_WC_EAD_SUPPORT): ?><li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'teach/support') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-bubbles3" title="Suporte" href="dashboard.php?wc=teach/support">Suporte<?= $wc_ead_support_alerts; ?></a>
            <ul class="dashboard_nav_menu_sub">
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/support' && $SupportEadStatus == 1 ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Tickets Em Aberto" href="dashboard.php?wc=teach/support&support_status=1">&raquo; Em aberto <?= $wc_ead_support_alerts; ?></a></li>
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/support' && $SupportEadStatus == 2 ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Tickets Respondidos" href="dashboard.php?wc=teach/support&support_status=2">&raquo; Respondidos</a></li>
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/support' && $SupportEadStatus == 3 ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Tickets Concluídos" href="dashboard.php?wc=teach/support&support_status=3">&raquo; Concluídos</a></li>
            </ul>
        </li>
    <?php endif; ?>
    
    <?php if ($Admin['user_level'] >= LEVEL_WC_EAD_ORDERS): ?><li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'teach/orders') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-codepen" title="Matrículas" href="dashboard.php?wc=teach/orders">Matrículas <?= $wc_ead_orders_alerts; ?></a>
            <ul class="dashboard_nav_menu_sub">
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/orders' && $OrdersEadStatus != 'chargeback' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Todos os Pedidos" href="dashboard.php?wc=teach/orders">&raquo; Pedidos</a></li>
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/orders_sales' && $OrdersEadStatus != 'chargeback' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Vendas" href="dashboard.php?wc=teach/orders_sales">&raquo; Vendas</a></li>
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/orders_signatures' && $OrdersEadStatus != 'chargeback' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Assinaturas" href="dashboard.php?wc=teach/orders_signatures">&raquo; Assinaturas</a></li>
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/orders' && $OrdersEadStatus == 'chargeback' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Pedidos Com Chargeback" href="dashboard.php?wc=teach/orders&status=chargeback">&raquo; Chargebacks <?= $wc_ead_orders_alerts; ?></a></li>
            </ul>
        </li>
    <?php endif; ?>
<?php endif; ?>

<!-- PRODUTOS -->
<?php if (APP_PRODUCTS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_PRODUCTS):
    $wc_pdt_alerts = null;
    $Read->FullRead("SELECT count(pdt_id) as total FROM " . DB_PDT . " WHERE pdt_status != 1 OR pdt_inventory < 1");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_pdt_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>

    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'products/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-bullhorn" title="Produtos" href="dashboard.php?wc=products/home">Produtos <?= $wc_pdt_alerts; ?></a>
        <ul class="dashboard_nav_menu_sub">
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'products/home' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Ver Produtos" href="dashboard.php?wc=products/home">&raquo; Ver Produto</a></li>
            <li class="dashboard_nav_menu_sub_li <?= strstr($getViewInput, 'products/home&opt=outsale') ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Fora de Estoque ou Inativos" href="dashboard.php?wc=products/home&opt=outsale">&raquo; Indisponíveis <?= $wc_pdt_alerts; ?></a></li>
            <li class="dashboard_nav_menu_sub_li <?= strstr($getViewInput, 'products/categor') ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Categorias de Produtos" href="dashboard.php?wc=products/categories">&raquo; Categorias</a></li>
            <li class="dashboard_nav_menu_sub_li <?= strstr($getViewInput, 'products/bran') ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Marcas ou Fabricantes" href="dashboard.php?wc=products/brands">&raquo; Fabricantes</a></li>
            <li class="dashboard_nav_menu_sub_li <?= strstr($getViewInput, 'products/coupons') ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Cupons de Desconto" href="dashboard.php?wc=products/coupons">&raquo; Descontos</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'products/create' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Novo Produto" href="dashboard.php?wc=products/create">&raquo; Novo Produto</a></li>
        </ul>
    </li>
<?php endif; ?>

<!-- PEDIDOS -->
<?php if (APP_ORDERS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_PRODUCTS_ORDERS):
    $wc_order_alerts = null;
    $Read->FullRead("SELECT count(order_id) as total FROM " . DB_ORDERS . " WHERE order_status = 6");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_order_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'orders/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-cart" title="Pedidos" href="dashboard.php?wc=orders/home">Pedidos <?= $wc_order_alerts; ?></a>
        <ul class="dashboard_nav_menu_sub">
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'orders/home' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Ver Pedidos" href="dashboard.php?wc=orders/home">&raquo; Ver Pedidos <?= $wc_order_alerts; ?></a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'orders/completed' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Pedidos Completos" href="dashboard.php?wc=orders/completed">&raquo; Concluídos</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'orders/canceled' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Pedidos Cancelados" href="dashboard.php?wc=orders/canceled">&raquo; Cancelados</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'orders/create' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Criar Pedido" href="dashboard.php?wc=orders/create">&raquo; Criar Pedido</a></li>
        </ul>   
    </li>
<?php endif; ?>

<!-- IMOBI -->
<?php if (APP_IMOBI && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_IMOBI):
    $wc_imobi_alerts = null;
    $Read->FullRead("SELECT count(realty_id) as total FROM " . DB_IMOBI . " WHERE realty_status != 1");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_imobi_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'imobi/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-home3" title="Imóveis" href="dashboard.php?wc=imobi/home">Imóveis <?= $wc_imobi_alerts; ?></a>
        <ul class="dashboard_nav_menu_sub">
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'imobi/home' || $getViewInput == 'imobi/search' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Ver Imóveis" href="dashboard.php?wc=imobi/home">&raquo; Ver Imóveis</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'imobi/inactive' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Imóveis Inativos" href="dashboard.php?wc=imobi/inactive">&raquo; Indisponíveis <?= $wc_imobi_alerts; ?></a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'imobi/create' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Novo Imóvel" href="dashboard.php?wc=imobi/create">&raquo; Novo Imóvel</a></li>
        </ul>
    </li>
<?php endif; ?>

<!-- LISTA DE CONTATOS -->
<?php if(APP_CONTACTS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_CONTACTS): ?>
<li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'contatos/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-envelop" title="Lista de Contatos" href="dashboard.php?wc=contatos/home">Lista de Contatos</a></li>
<?php endif; ?>

<!-- FAQ -->
<?php if (APP_FAQ && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_FAQ):
                        ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'faq/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-info" title="Perguntas Frequentes" href="dashboard.php?wc=faq/home">FAQ</a></li>
<?php endif; ?>

<!-- DEPOIMENTOS -->
<?php if (APP_TESTIMONIALS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_TESTIMONIALS):
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'testimonials/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-bubbles3" title="Depoimentos" href="dashboard.php?wc=testimonials/home">Depoimentos</a></li>
<?php endif; ?>

<!-- FB REVIEWS -->
<?php if (APP_FBREVIEW && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_FBREVIEWS):
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'fbreview/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-facebook2" title="FB Reviews" href="dashboard.php?wc=fbreview/home">FB Reviews</a></li>
<?php endif; ?>

<!-- MARCAS PARCEIRAS -->
<?php if (APP_BRANDS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_BRANDS):
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'marcas/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-heart" title="Marcas Parceiras" href="dashboard.php?wc=marcas/home">Marcas Parceiras</a></li>
<?php endif; ?>	

<!-- SLIDES -->                    
<?php if (APP_SLIDE && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_SLIDES):
    $wc_slide_alerts = null;
    $Read->FullRead("SELECT count(slide_id) as total FROM " . DB_SLIDES . " WHERE slide_end <= NOW()");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_slide_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'slide/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-images" title="Slides" href="dashboard.php?wc=slide/home">Slides<?= $wc_slide_alerts; ?></a>
        <ul class="dashboard_nav_menu_sub">
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'slide/home' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Slides Ativos" href="dashboard.php?wc=slide/home">&raquo; Slides Ativos</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'slide/end' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Slides Agendados ou Inativos" href="dashboard.php?wc=slide/end">&raquo; Slides Inativos <?= $wc_slide_alerts; ?></a></li>
        </ul>
    </li>
<?php endif; ?>

<!-- GALERIA DE IMAGENS -->
<?php if (APP_GALLERY && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_GALLERY):
    $wc_gallery_alerts = null;
    $Read->FullRead("SELECT count(gallery_id) as total FROM " . DB_GALLERY . " WHERE gallery_status != 1");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_gallery_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'gallery/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-camera" title="Galerias" href="dashboard.php?wc=gallery/home">Galerias <?= $wc_gallery_alerts ?></a>
        <ul class="dashboard_nav_menu_sub">
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'gallery/home' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Ver Galerias" href="dashboard.php?wc=gallery/home">&raquo; Ver Galerias</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'gallery/create' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Nova Galeria" href="dashboard.php?wc=gallery/create">&raquo; Nova Galeria</a></li>
        </ul>
    </li>
<?php endif; ?>

<!-- GALERIA DE VÍDEOS -->
<?php if(APP_VIDEOS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_VIDEOS):
    $wc_videos_alerts = null;
    $Read->FullRead("SELECT count(videos_id) as total FROM " . DB_GALLERY_VIDEOS . " WHERE videos_status != 1");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_videos_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>
	<li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'videos/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-video-camera" title="Vídeos" href="dashboard.php?wc=videos/home">Vídeos <?= $wc_videos_alerts ?></a>
        <ul class="dashboard_nav_menu_sub">
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'videos/home' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Ver Vídeos" href="dashboard.php?wc=videos/home">&raquo; Ver Vídeos</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'videos/create' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Novo Vídeo" href="dashboard.php?wc=videos/create">&raquo; Novo Vídeo</a></li>
        </ul>
	</li>
<?php endif; ?>

<!-- HELLOBAR -->
<?php if ($_SESSION['userLogin']['user_level'] >= LEVEL_WC_HELLO): 
    $wc_hello_alerts = null;
    $Read->FullRead("SELECT count(hello_id) as total FROM " . DB_HELLO . " WHERE hello_status != 1");
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1):
        $wc_hello_alerts .= "<span class='wc_alert bar_aquablue'>{$Read->getResult()[0]['total']}</span>";
    endif;
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'hello/home') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-bullhorn" title="Hellobar" href="dashboard.php?wc=hello/home">Hellobar <?= $wc_hello_alerts ?></a>
        <ul class="dashboard_nav_menu_sub">
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'hello/home' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Ver Hellobar" href="dashboard.php?wc=hello/home">&raquo; Ver Hellobar</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'hello/create' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Nova Hellobar" href="dashboard.php?wc=hello/create">&raquo; Nova Hellobar</a></li>
        </ul>
    </li>
<?php endif; ?>

<!-- USUÁRIOS -->
<?php if (APP_USERS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_USERS):
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'users/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-users" title="Usuários" href="dashboard.php?wc=users/home">Usuários</a>
        <ul class="dashboard_nav_menu_sub">
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'users/home' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Ver Usuários" href="dashboard.php?wc=users/home">&raquo; Ver Usuários</a></li>
            <li class="dashboard_nav_menu_sub_li <?= strstr($getViewInput, 'users/home&opt=customers') ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Clientes" href="dashboard.php?wc=users/home&opt=customers">&raquo; Clientes</a></li>
            <li class="dashboard_nav_menu_sub_li <?= strstr($getViewInput, 'users/home&opt=team') ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Equipe" href="dashboard.php?wc=users/home&opt=team">&raquo; Equipe</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'users/create' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Novo Usuário" href="dashboard.php?wc=users/create">&raquo; Novo Usuário</a></li>
        </ul>
    </li>
<?php endif; ?>

<!-- RELATÓRIOS -->
<?php if ($_SESSION['userLogin']['user_level'] >= LEVEL_WC_REPORTS):
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'report') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-pie-chart" title="Relatórios" href="dashboard.php?wc=reports/reports">Relatórios</a>
        <ul class="dashboard_nav_menu_sub">
            <?php if (APP_EAD): ?>
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/report_students' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Relatório de Alunos" href="dashboard.php?wc=teach/report_students">&raquo; Alunos</a></li>
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/report_support' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Relatório de Suporte" href="dashboard.php?wc=teach/report_support">&raquo; Suporte</a></li>
                <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'teach/report_sales' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Relatório de Vendas" href="dashboard.php?wc=teach/report_sales">&raquo; Vendas</a></li>
            <?php endif; ?>
        </ul>
    </li>
<?php endif; ?>

<!-- TUTORIAIS -->
<?php if (APP_TUTORIAIS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_TUTORIAIS): ?>     
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'tutoriais/home') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-film" title="Tutoriais" href="dashboard.php?wc=tutoriais/home">Tutoriais</a>
    </li>     
<?php endif; ?>

<!-- CONFIGURAÇÕES -->
<?php if ($Admin['user_level'] >= LEVEL_WC_CONFIG_MASTER || $Admin['user_level'] >= LEVEL_WC_CONFIG_API || $Admin['user_level'] >= LEVEL_WC_CONFIG_CODES):
    ?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'config/') ? 'dashboard_nav_menu_active' : ''; ?>"><a style="cursor: default;" onclick="return false;" class="icon-cogs" title="Configurações" href="#">Configurações</a>
        <ul class="dashboard_nav_menu_sub top">
            <?php if ($Admin['user_level'] >= LEVEL_WC_CONFIG_MASTER): ?><li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'config/home' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Configurações Gerais" href="dashboard.php?wc=config/home">&raquo; Configurações Gerais</a></li><?php endif; ?>
            <?php if ($Admin['user_level'] >= LEVEL_WC_CONFIG_MASTER): ?><li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'config/license' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Licenciar Domínio" href="dashboard.php?wc=config/license">&raquo; Licenciar Domínio</a></li><?php endif; ?>
            <?php if ($Admin['user_level'] >= LEVEL_WC_CONFIG_CODES): ?><li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'config/codes' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Gerenciar Pixels" href="dashboard.php?wc=config/codes">&raquo; Gerenciar Pixels</a></li><?php endif; ?>
            <?php if ($Admin['user_level'] >= LEVEL_WC_CONFIG_API): ?><li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'config/wcapi' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="WorkControl API" href="dashboard.php?wc=config/wcapi">&raquo; Work Control® API</a></li><?php endif; ?>
            <?php if ($Admin['user_level'] >= LEVEL_WC_CONFIG_MASTER): ?><li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'config/sample' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="WorkControl Samples" href="dashboard.php?wc=config/samples">&raquo; Work Control® Samples</a></li><?php endif; ?>
        </ul>
    </li>
<?php endif; ?>

<li class="dashboard_nav_menu_li"><a target="_blank" class="icon-forward" title="Ver Site" href="<?= BASE; ?>">Ver Site</a></li>
