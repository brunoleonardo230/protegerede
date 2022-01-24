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
                            <p>Somos uma empresa especializada em venda e colocação de rede de proteção.</p> 
                            <p>Nossa empresa surgiu para atender um mercado de pessoas que visam a segurança em geral 
                            da sua família e seus bichinhos de estimação, uma empresa que vende materiais de qualidade. </p>
                            <p>Qualidade de prestação de serviço não está apenas na execução dos nossos serviços, mais 
                            também na comunicação, melhor preço, qualidade, por isso a empresa protege rede de 
                            proteção deu prioridade aos nossos principais bens, nossos clientes, visando melhor 
                            atendimento e qualidade, deixando cada um satisfeito e seguro. nossa empresa é 
                            comprometida, com uma equipe treinada e especializada. </p>
                            <p>Uma empresa preocupada com animais em estado de rua, uma empresa que presta 
                            alimentação e resgate para animais de rua, a cada execução de um serviço uma parte é 
                            destinada para este beneficio aos animais, trazendo esperança para eles.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-5 col-xs-12 about-img">
                        <img src="<?= INCLUDE_PATH; ?>/assets/images/about.png" title="Bem Vindo a <?= SITE_NAME; ?>" alt="Bem Vindo a <?= SITE_NAME; ?>"/>
                    </div>
                </div>
            </div> <!-- Container /- -->
        </div> <!-- About Section -->

        
    </main>
</div>
	
