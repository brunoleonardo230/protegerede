<?php if(SITE_HEADER == 1): ?>
    <header itemscope itemtype="http://schema.org/WPHeader" id="" class="header_s header_default">
		<!-- Container -->
		<div class="container">
			<!-- Top Header -->
			<div class="default-top row">
				<div class="logo-block">
					<a class="navbar-brand mobile-hide" href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><i class="fa fa-building-o"></i>Protege</a>
				</div>
				<a href="tel:<?= SITE_ADDR_PHONE_A; ?>" title="<?= SITE_ADDR_PHONE_A; ?>" class="phone-call"><i class="fa fa-phone-square"></i><?= SITE_ADDR_PHONE_A; ?></a>
			</div> <!-- Top Header /-->
		</div><!-- Container /- -->
		<!-- Ownavigation -->
		<nav itemscope itemtype="http://schema.org/SiteNavigationElement" class="navbar ownavigation nav_absolute">
			<!-- Container -->
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand desktop-hide" href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><i class="fa fa-building-o"></i>Doctor</a>
				</div>
				<div class="navbar-collapse collapse" id="navbar">
					<ul class="nav navbar-nav menubar">
						<li class="<?= ($URL[0] == 'index' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><span itemprop="name">Home</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'sobre' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/sobre" title="Sobre <?= SITE_NAME; ?>"><span itemprop="name">Sobre</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'servicos' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/servicos" title="Serviços"><span itemprop="name">Serviços</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'parceiros' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/parceiros" title="Parceiros"><span itemprop="name">Parceiros</span></a>
                        </li>
                        <!-- <li class="<?= ($URL[0] == 'medicos' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/medicos" title="Médicos"><span itemprop="name">Médicos</span></a>
                        </li> -->
                        <li class="<?= ($URL[0] == 'galeria' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/galeria" title="Galeria"><span itemprop="name">Galeria</span></a>
                        </li>
                        <li class="dropdown mega-dropdown">
                            <a href="<?= BASE; ?>/artigos" title="Blog" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">Blog</a>
                            <i class="ddl-switch fa fa-angle-down"></i>
                            <ul class="dropdown-menu mega-menu">
                                <?php
                                $Read->ExeRead(DB_CATEGORIES, "WHERE category_id IN(SELECT post_category FROM " . DB_POSTS . " WHERE post_status = 1 AND post_date <= NOW()) ORDER BY category_title ASC");
                                if ($Read->getResult()):
                                    foreach ($Read->getResult() as $Ses):
                                        $Read->ExeRead(DB_POSTS, "WHERE post_category = :cid", "cid={$Ses['category_id']}");
                                        $Count = $Read->getRowCount();
                                        echo "<li><a itemprop='url' class='dropdown-item' title='{$Ses['category_title']}' href='" . BASE . "/categorias/{$Ses['category_name']}'><span itemprop='name'>{$Ses['category_title']}</span> ({$Count})</a></li>";
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </li>
                        <li class="<?= ($URL[0] == 'contato' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/contato" title="Contato"><span itemprop="name">Contato</span></a>
                        </li>
					</ul>
				</div>
			</div> <!-- Container /-->
		</nav> <!-- Ownavigation /-->
	</header>
<?php elseif(SITE_HEADER == 2): ?>
    <header itemscope itemtype="http://schema.org/WPHeader" id="" class="header_s header_s1">
        <!-- SidePanel -->
        <div id="slidepanel">
            <!-- Top Header -->
            <div class="top-header container-fluid no-left-padding no-right-padding">
                <!-- Container -->
                <div class="container">
                    <div class="call-info">
                        <p><a href="tel:<?= SITE_ADDR_PHONE_A; ?>" title="<?= SITE_ADDR_PHONE_A; ?>" class="phone"><i class="fa fa-phone"></i><?= SITE_ADDR_PHONE_A; ?></a></p>
                        <p><a href="mailto:<?= SITE_ADDR_EMAIL; ?>" title="<?= SITE_ADDR_EMAIL; ?>"><i class="fa fa-envelope-o"></i><?= SITE_ADDR_EMAIL; ?></a></p>
                    </div>
                    <div class="header-social">
                        <ul>
                            <li><a title="<?= SITE_NAME; ?> No Facebook" href="//www.facebook.com/<?= SITE_SOCIAL_FB_PAGE; ?>"
                                   target="_blank"><i class="fa fa-facebook"></i></a></li>
                            <li><a title="<?= SITE_NAME; ?> No Instagram"
                                   href="//www.instagram.com/<?= SITE_SOCIAL_INSTAGRAM; ?>" target="_blank"><i
                                            class="fa fa-instagram"></i></a></li>
                            <li><a title="<?= SITE_NAME; ?> No Twitter" href="//www.twitter.com/<?= SITE_SOCIAL_TWITTER; ?>"
                                   target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li><a title="<?= SITE_NAME; ?> No Google" href="//plus.google.com/<?= SITE_SOCIAL_GOOGLE_PAGE; ?>"
                                   target="_blank"><i class="fa fa-google-plus"></i></a></li>
                            <li><a title="<?= SITE_NAME; ?> No Linkedin" href="//www.linkedin.com/<?= SITE_SOCIAL_LINKEDIN; ?>"
                                   target="_blank"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div> <!-- Container /- -->
            </div> <!-- Top Header /-->
        </div> <!-- SidePanel /-->

        <!-- Ownavigation -->
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement" class="navbar ownavigation">
            <!-- Container -->
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html"><i class="fa fa-building-o"></i>Doctor</a>
                </div>
                <div class="navbar-collapse collapse" id="navbar">
                    <ul class="nav navbar-nav menubar navbar-right">
                        <li class="<?= ($URL[0] == 'index' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><span itemprop="name">Home</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'sobre' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/sobre" title="Sobre <?= SITE_NAME; ?>"><span itemprop="name">Sobre</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'servicos' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/servicos" title="Serviços"><span itemprop="name">Serviços</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'especialidades' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/especialidades" title="Especialidades"><span itemprop="name">Especialidades</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'medicos' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/medicos" title="Médicos"><span itemprop="name">Médicos</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'galeria' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/galeria" title="Galeria"><span itemprop="name">Galeria</span></a>
                        </li>
                        <li class="dropdown mega-dropdown">
                            <a href="<?= BASE; ?>/artigos" title="Blog" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">Blog</a>
                            <i class="ddl-switch fa fa-angle-down"></i>
                            <ul class="dropdown-menu mega-menu">
                                <?php
                                $Read->ExeRead(DB_CATEGORIES, "WHERE category_id IN(SELECT post_category FROM " . DB_POSTS . " WHERE post_status = 1 AND post_date <= NOW()) ORDER BY category_title ASC");
                                if ($Read->getResult()):
                                    foreach ($Read->getResult() as $Ses):
                                        $Read->ExeRead(DB_POSTS, "WHERE post_category = :cid", "cid={$Ses['category_id']}");
                                        $Count = $Read->getRowCount();
                                        echo "<li><a itemprop='url' class='dropdown-item' title='{$Ses['category_title']}' href='" . BASE . "/categorias/{$Ses['category_name']}'><span itemprop='name'>{$Ses['category_title']}</span> ({$Count})</a></li>";
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </li>
                        <li class="<?= ($URL[0] == 'contato' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/contato" title="Contato"><span itemprop="name">Contato</span></a>
                        </li>
                    </ul>
                </div>
                <div id="loginpanel" class="desktop-hide">
                    <div class="right" id="toggle">
                        <a id="slideit" href="#slidepanel"><i class="fo-icons fa fa-inbox"></i></a>
                        <a id="closeit" href="#slidepanel"><i class="fo-icons fa fa-close"></i></a>
                    </div>
                </div>
            </div> <!-- Container /-->
        </nav> <!-- Ownavigation /-->
    </header>
<?php elseif(SITE_HEADER == 3): ?>
    <header itemscope itemtype="http://schema.org/WPHeader" id="" class="header_s header_s1 header_s2 header_s3">
        <!-- Logo Block -->
        <div class="logo-block">
            <!-- Container -->
            <div class="container">
                <a class="navbar-brand mobile-hide" href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><i class="fa fa-building-o"></i>Doctor</a>
            </div> <!-- Container /-->
        </div> <!-- Logo Block /- -->

        <!-- Ownavigation -->
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement" class="navbar ownavigation">
            <!-- Container -->
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand desktop-hide" href="index.html"><i class="fa fa-building-o"></i>Doctor</a>
                </div>
                <div class="navbar-collapse collapse" id="navbar">
                    <ul class="nav navbar-nav menubar">
                        <li class="<?= ($URL[0] == 'index' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><span itemprop="name">Home</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'sobre' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/sobre" title="Sobre <?= SITE_NAME; ?>"><span itemprop="name">Sobre</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'servicos' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/servicos" title="Serviços"><span itemprop="name">Serviços</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'especialidades' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/especialidades" title="Especialidades"><span itemprop="name">Especialidades</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'medicos' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/medicos" title="Médicos"><span itemprop="name">Médicos</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'galeria' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/galeria" title="Galeria"><span itemprop="name">Galeria</span></a>
                        </li>
                        <li class="dropdown mega-dropdown">
                            <a href="<?= BASE; ?>/artigos" title="Blog" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">Blog</a>
                            <i class="ddl-switch fa fa-angle-down"></i>
                            <ul class="dropdown-menu mega-menu">
                                <?php
                                $Read->ExeRead(DB_CATEGORIES, "WHERE category_id IN(SELECT post_category FROM " . DB_POSTS . " WHERE post_status = 1 AND post_date <= NOW()) ORDER BY category_title ASC");
                                if ($Read->getResult()):
                                    foreach ($Read->getResult() as $Ses):
                                        $Read->ExeRead(DB_POSTS, "WHERE post_category = :cid", "cid={$Ses['category_id']}");
                                        $Count = $Read->getRowCount();
                                        echo "<li><a itemprop='url' class='dropdown-item' title='{$Ses['category_title']}' href='" . BASE . "/categorias/{$Ses['category_name']}'><span itemprop='name'>{$Ses['category_title']}</span> ({$Count})</a></li>";
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </li>
                        <li class="<?= ($URL[0] == 'contato' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/contato" title="Contato"><span itemprop="name">Contato</span></a>
                        </li>
                    </ul>
                </div>
                <div id="loginpanel" class="desktop-hide">
                    <div class="right" id="toggle">
                        <a id="slideit" href="#slidepanel"><i class="fo-icons fa fa-inbox"></i></a>
                        <a id="closeit" href="#slidepanel"><i class="fo-icons fa fa-close"></i></a>
                    </div>
                </div>
            </div> <!-- Container /-->
        </nav> <!-- Ownavigation /-->

        <!-- SidePanel -->
        <div id="slidepanel">
            <!-- Top Header -->
            <div class="top-header container-fluid no-left-padding no-right-padding">
                <!-- Container -->
                <div class="container">
                    <div class="call-info">
                        <p><a href="tel:<?= SITE_ADDR_PHONE_A; ?>" title="<?= SITE_ADDR_PHONE_A; ?>" class="phone"><i class="fa fa-phone"></i><?= SITE_ADDR_PHONE_A; ?></a></p>
                        <p><a href="mailto:<?= SITE_ADDR_EMAIL; ?>" title="<?= SITE_ADDR_EMAIL; ?>"><i class="fa fa-envelope-o"></i><?= SITE_ADDR_EMAIL; ?></a></p>
                    </div>
                    <div class="header-social">
                        <ul>
                            <li><a title="<?= SITE_NAME; ?> No Facebook" href="//www.facebook.com/<?= SITE_SOCIAL_FB_PAGE; ?>"
                                   target="_blank"><i class="fa fa-facebook"></i></a></li>
                            <li><a title="<?= SITE_NAME; ?> No Instagram"
                                   href="//www.instagram.com/<?= SITE_SOCIAL_INSTAGRAM; ?>" target="_blank"><i
                                            class="fa fa-instagram"></i></a></li>
                            <li><a title="<?= SITE_NAME; ?> No Twitter" href="//www.twitter.com/<?= SITE_SOCIAL_TWITTER; ?>"
                                   target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li><a title="<?= SITE_NAME; ?> No Google" href="//plus.google.com/<?= SITE_SOCIAL_GOOGLE_PAGE; ?>"
                                   target="_blank"><i class="fa fa-google-plus"></i></a></li>
                            <li><a title="<?= SITE_NAME; ?> No Linkedin" href="//www.linkedin.com/<?= SITE_SOCIAL_LINKEDIN; ?>"
                                   target="_blank"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div> <!-- Container /-->
            </div> <!-- Top Header /-->
        </div> <!-- SidePanel /-->
    </header>
<?php else: ?>
    <header itemscope itemtype="http://schema.org/WPHeader" id="" class="header_s header_default">
        <!-- Container -->
        <div class="container">
            <!-- Top Header -->
            <div class="default-top row">
                <div class="logo-block">
                    <a class="navbar-brand mobile-hide" href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><i class="fa fa-building-o"></i>Doctor</a>
                </div>
                <a href="tel:<?= SITE_ADDR_PHONE_A; ?>" title="<?= SITE_ADDR_PHONE_A; ?>" class="phone-call"><i class="fa fa-phone-square"></i><?= SITE_ADDR_PHONE_A; ?></a>
            </div> <!-- Top Header /-->
        </div><!-- Container /- -->
        <!-- Ownavigation -->
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement" class="navbar ownavigation nav_absolute">
            <!-- Container -->
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand desktop-hide" href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><i class="fa fa-building-o"></i>Doctor</a>
                </div>
                <div class="navbar-collapse collapse" id="navbar">
                    <ul class="nav navbar-nav menubar">
                        <li class="<?= ($URL[0] == 'index' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><span itemprop="name">Home</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'sobre' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/sobre" title="Sobre <?= SITE_NAME; ?>"><span itemprop="name">Sobre</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'especialidades' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/especialidades" title="Especialidades"><span itemprop="name">Especialidades</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'medicos' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/medicos" title="Médicos"><span itemprop="name">Médicos</span></a>
                        </li>
                        <li class="<?= ($URL[0] == 'galeria' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/galeria" title="Galeria"><span itemprop="name">Galeria</span></a>
                        </li>
                        <li class="dropdown mega-dropdown">
                            <a href="<?= BASE; ?>/artigos" title="Blog" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">Blog</a>
                            <i class="ddl-switch fa fa-angle-down"></i>
                            <ul class="dropdown-menu mega-menu">
                                <?php
                                $Read->ExeRead(DB_CATEGORIES, "WHERE category_id IN(SELECT post_category FROM " . DB_POSTS . " WHERE post_status = 1 AND post_date <= NOW()) ORDER BY category_title ASC");
                                if ($Read->getResult()):
                                    foreach ($Read->getResult() as $Ses):
                                        $Read->ExeRead(DB_POSTS, "WHERE post_category = :cid", "cid={$Ses['category_id']}");
                                        $Count = $Read->getRowCount();
                                        echo "<li><a itemprop='url' class='dropdown-item' title='{$Ses['category_title']}' href='" . BASE . "/categorias/{$Ses['category_name']}'><span itemprop='name'>{$Ses['category_title']}</span> ({$Count})</a></li>";
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </li>
                        <li class="<?= ($URL[0] == 'contato' ? 'active' : ''); ?>">
                            <a itemprop="url" href="<?= BASE; ?>/contato" title="Contato"><span itemprop="name">Contato</span></a>
                        </li>
                    </ul>
                </div>
            </div> <!-- Container /-->
        </nav> <!-- Ownavigation /-->
    </header>
<?php endif; ?>