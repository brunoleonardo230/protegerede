<?php
$AdminLevel = LEVEL_WC_COMPANY;
if (!APP_COMPANY || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Create)):
    $Create = new Create;
endif;

$CompanyId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($CompanyId):
    $Read->ExeRead(DB_COMPANY, "WHERE company_id = :id", "id={$CompanyId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = "<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Uma Empresa Que Não Existe ou Que Foi Removida Recentemente!";
        header('Location: dashboard.php?wc=company/home');
    endif;
else:
    $CompanyCreate = ['company_datecreated' => date('Y-m-d H:i:s')];
    $Create->ExeCreate(DB_COMPANY, $CompanyCreate);
    header('Location: dashboard.php?wc=company/create&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-office">A Empresa</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=company/home">A Empresa</a>
        </p>
    </div>
    
    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;">
        <a class="btn_header btn_darkaquablue icon-undo2" title="Voltar" href="dashboard.php?wc=company/home">Voltar</a>
    </div>
</header>

<div class="dashboard_content">
    <div class="box box70">
        <article class="wc_tab_target wc_active" id="office">
             <form class="auto_save" name="create_company" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Company"/>
                <input type="hidden" name="callback_action" value="manager"/>
                <input type="hidden" name="company_id" value="<?= $CompanyId; ?>"/>
            
                <div class="panel_header darkaquablue">
                    <h2 class="icon-office">Dados Sobre a Empresa</h2>
                </div>
                <div class="panel">
                    <label class="label">
                        <span class="legend">Capa: (JPG <?= IMAGE_W; ?>x<?= IMAGE_H; ?>px)</span>
                        <input type="file" class="wc_loadimage" name="company_image"/>
                    </label>
                    
                    <label class="label">
                        <span class="legend">Nome da Empresa:</span>
                        <input name="company_title" style="font-size: 1.2em;" value="<?= $company_title; ?>" placeholder="Informe o Nome da Empresa" required/>
                    </label>
                    
                    <label class="label">
                        <span class="legend">Segmento da Empresa:</span>
                        <input name="company_segment" value="<?= $company_segment; ?>" placeholder="Informe o  Segmento" required/>
                    </label>
                    
                    <label class="label">
                        <span class="legend">Responsável Pela Empresa:</span>
                        <input name="company_responsible" value="<?= $company_responsible; ?>" placeholder="Informe o  Responsável"/>
                    </label>
                    
                    <label class="label">
                        <span class="legend">Descrição da Empresa:</span>
                        <textarea class="work_mce" rows="50" name="company_content"><?= $company_content; ?></textarea>
                    </label>  
                    
                    <div class="label_50">
                        <label class="label">
                            <span class="legend">CNPJ:</span>
                            <input value="<?= $company_document; ?>" type="text" name="company_document" class="formCnpj" placeholder="Informe o  CNPJ" />
                        </label>
                        
                        <label class="label">
                            <span class="legend">Ano de Inauguração:</span>
                            <input value="<?= $company_opening; ?>" type="text" name="company_opening" class="formYear" placeholder="Informe o Ano de Inaguração" />
                        </label>
                    </div> 
                    
                    <div class="clear"></div>
                    <h3 class="form_subtitle icon-phone m_botton">Contatos:</h3>

                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Telefone:</span>
                            <input value="<?= $company_telephone; ?>" class="formPhone" type="text" name="company_telephone" placeholder="Informe o Telefone" />
                        </label>

                        <label class="label">
                            <span class="legend">Celular:</span>
                            <input value="<?= $company_cell; ?>" class="formPhone" type="text" name="company_cell" placeholder="Informe o Celular" />
                        </label>
                    </div>
                    
                    <label class="label">
                        <span class="legend">E-mail:</span>
                        <input value="<?= $company_email; ?>" type="email" name="company_email" placeholder="Informe o E-mail" />
                    </label>
                    
                    <div class="clear"></div>
                    <h3 class="form_subtitle icon-share2 m_botton">Redes Sociais:</h3>
                    
                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Facebook:</span>
                            <input value="<?= $company_facebook; ?>" type="text" name="company_facebook" placeholder="Informe o Facebook" />
                        </label>
                        
                        <label class="label">
                            <span class="legend">Instagram:</span>
                            <input value="<?= $company_instagram; ?>" type="text" name="company_instagram" placeholder="Informe o Instagram" />
                        </label>
                    </div>    

                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Twitter:</span>
                            <input value="<?= $company_twitter; ?>" type="text" name="company_twitter" placeholder="Informe o Twiiter" />
                        </label>

                        <label class="label">
                            <span class="legend">Youtube:</span>
                            <input value="<?= $company_youtube; ?>" type="text" name="company_youtube" placeholder="Informe o Youtube" />
                        </label>
                    </div>
                    
                    <div class="clear"></div>
                    <h3 class="form_subtitle icon-location m_botton">Endereço:</h3>
                    
                    <div class="label_50">
                    <label class="label">
                        <span class="legend">CEP:</span>
                        <input name="company_zipcode" value="<?= $company_zipcode; ?>" class="formCep wc_getCep" placeholder="Informe o CEP" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Rua:</span>
                        <input class="wc_logradouro" name="company_street" value="<?= $company_street; ?>" placeholder="Informe o Nome da Rua" required/>
                    </label>
                </div>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">Número:</span>
                        <input name="company_number" value="<?= $company_number; ?>" placeholder="Informe o Número" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Complemento:</span>
                        <input class="wc_complemento" name="company_complement" value="<?= $company_complement; ?>" placeholder="Informe o Complemento (Ex: Casa, Apto, Etc)"/>
                    </label>
                </div>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">Bairro:</span>
                        <input class="wc_bairro" name="company_district" value="<?= $company_district; ?>" placeholder="Informe o Bairro" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Cidade:</span>
                        <input class="wc_localidade" name="company_city" value="<?= $company_city; ?>" placeholder="Informe a Cidade" required/>
                    </label>
                </div>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">Estado (UF):</span>
                        <input class="wc_uf" name="company_state" value="<?= $company_state; ?>" maxlength="2" placeholder="Informe o Estado (Ex.: RJ)" required/>
                    </label>

                    <label class="label">
                        <span class="legend">País:</span>
                        <input name="company_country" value="<?= ($company_country ? $company_country : 'Brasil'); ?>" required/>
                    </label>
                </div>
                    
                    <div class="m_top">&nbsp;</div>
                    <div class="wc_actions" style="text-align: center">
                        <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                        
                        <div class="switch__container" style="margin-bottom: 10px;">
                          <input value='1' id="switch-shadow" class="switch switch--shadow" type="checkbox" name='company_status' <?= ($company_status == 1 ? 'checked' : ''); ?>>
                          <label for="switch-shadow"></label>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>  
            </form>    
        </article>
        
        <article class="wc_tab_target ds_none" id="blocks">
            <div class="panel_header darkaquablue">
                <span>
                    <a title="Novo Bloco" href="dashboard.php?wc=company/blocks&company=<?= $company_id; ?>" class="btn_header btn_aquablue icon-plus icon-notext"></a>    
                </span>
                <h2 class="icon-stack">Blocos</h2>
            </div>
            <div class="panel">
                <?php
                $Read->ExeRead(DB_COMPANY_BLOCKS, "WHERE company_id = :company ORDER BY block_datecreate DESC, block_title ASC", "company={$company_id}");
                if (!$Read->getResult()):
                    echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Blocos Cadastrados. Comece Agora Mesmo Cadastrando Seu Primeiro Bloco!</span>", E_USER_NOTICE);
                else:
                    foreach ($Read->getResult() as $Blocks):
                        extract($Blocks);
                        echo "<div class='single_user_addr js-rel-to' id='{$block_id}'>
                            <h1 class='icon-stack'>{$block_title}</h1>
                            <p>" . Check::Words($block_content, 20) . "</p>
                            <div class='single_user_addr_actions'>
                                <a title='Editar Bloco' href='dashboard.php?wc=company/blocks&id={$block_id}' class='post_single_center icon-notext icon-pencil btn_header btn_darkaquablue'></a>
                                <span title='Excluir Bloco' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Company' callback_action='delete_block' id='{$block_id}'></span>
                            </div>
                        </div>";
                    endforeach;
                endif;
                ?>
            </div>
            <div class="clear"></div>
        </article>
        
        <article class="wc_tab_target ds_none" id="gallery">
            <div class="panel_header darkaquablue">
                <h2 class="icon-images">Galeria</h2>
                <span class="btn_header btn_aquablue icon-spinner9 wc_drag_active" title="Ordenar" style="display:inline-block; margin-top: -20px;">Ordenar</span>
            </div>
            <div class="panel">
                <form class="auto_save" name="create_gallery_company" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="callback" value="Company"/>
                    <input type="hidden" name="callback_action" value="gallery_image"/>
                    <input type="hidden" name="company_id" value="<?= $CompanyId; ?>"/>
        
                    <div class="upload_progress none" style="padding: 5px; background: #218FE5; color: #fff; width: 0%; text-align: center; max-width: 100%;">0%</div>
                    
                    <input type="file" name="gallery_images[]" multiple required/>                
                    <div class="clear"></div>

                    <div class='gallery panel_gallery'>
                        <?php
                            $Read->ExeRead(DB_COMPANY_GALLERY, "WHERE company_id = :id ORDER BY gallery_image_order ASC", "id={$CompanyId}");
                            if (!$Read->getResult()):
                                Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Fotos Cadastradas Em Nossa Galeria!</span>", E_USER_NOTICE);
                            else:
                                foreach ($Read->getResult() as $image):
                                    extract($image);
                                    ?>                            
                                    <div class='panel_gallery_image wc_draganddrop' callback='Company' callback_action='gallery_image_order' id='<?= $gallery_image_id; ?>' data-id="<?= $gallery_image_id; ?>" >
                                        <img src='../tim.php?src=uploads/<?= $gallery_file; ?>&w=200&h=200'>
                                        <div class='panel_gallery_action'>
                                            <ul class="buttons">
                                            <li><span title="Editar Imagem" class="j_edit_action icon-pencil icon-notext btn_header btn_aquablue"></span></li>
                                            <li><span title="Excluir Imagem" rel='panel_gallery_image' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Company' callback_action='gallery_image_delete' id="<?= $gallery_image_id; ?>"></span></li>
                                            </ul>
                                        </div>
                                        <span class="panel_gallery_image_legend al_center"><?= Check::Words($gallery_image_legend, 80) ?></span>
                                    </div>
    
                                    <?php
                                endforeach;
                            endif;    
                        ?>    
                    </div>
                    <div class="clear"></div>
                </form>
                
                <div class="modal_legend">
                    <div class="modal_legend_content">
                        <form class="j_form_legend" action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="gallery_image_id" value=""/>
                            <input type="hidden" name="callback" value="Company"/>
                            <input type="hidden" name="callback_action" value="gallery_legend"/>
    
                            <span class="legend">Alterar Legenda da Foto:</span>
                            <input type="text" name="gallery_image_legend" placeholder="Legenda:" required/>
                            <span title="Fechar" class="modal_cancel icon-cancel-circle btn btn_red" id="post_control" style="margin-right: 8px;">Fechar</span>
                            <button title="ATUALIZAR" class="btn btn_aquablue icon-share">ATUALIZAR</button>
                            <div class="clear"></div>
                        </form>  
                    </div>    
                </div>
            </div>
            
            <script src="<?= BASE; ?>/admin/_siswc/company/company.js"></script>
        </article>

        <article class="wc_tab_target ds_none" id="mission">
            <div class="panel_header darkaquablue">
                <h2 class="icon-eye">Missão, Visão e Valores</h2>
            </div>
            <div class="panel">
                <form class="auto_save" name="create_tripod_company" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="callback" value="Company"/>
                    <input type="hidden" name="callback_action" value="create_tripod"/>
                    <input type="hidden" name="company_id" value="<?= $CompanyId; ?>"/>
        
                    <label class="label">
                        <span class="legend">Descrição da Missão:</span>
                        <textarea class="work_mce" rows="50" name="company_mission"><?= $company_mission; ?></textarea>
                    </label>
                    
                    <div class="m_top">&nbsp;</div>
                    <label class="label">
                        <span class="legend">Descrição da Visão:</span>
                        <textarea class="work_mce" rows="50" name="company_view"><?= $company_view; ?></textarea>
                    </label>
                    
                    <div class="m_top">&nbsp;</div>
                    <label class="label">
                        <span class="legend">Descrição dos Valores:</span>
                        <textarea class="work_mce" rows="50" name="company_values"><?= $company_values; ?></textarea>
                    </label>
                    
                    <div class="m_top">&nbsp;</div>
                    <div class="wc_actions" style="text-align: center">
                        <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                    </div>
                </form>   
            </div>  
            <div class="clear"></div>
        </article>
        
        <article class="wc_tab_target ds_none" id="differentials">
            <div class="panel_header darkaquablue">
                <span>
                    <a title="Novo Diferencial" href="dashboard.php?wc=company/differentials&company=<?= $company_id; ?>" class="btn_header btn_aquablue icon-plus icon-notext"></a>
                </span>
                <h2 class="icon-list2">Diferenciais</h2>
            </div>
            <div class="panel">
                <?php
                $Read->ExeRead(DB_COMPANY_DIFFERENTIALS, "WHERE company_id = :differentials ORDER BY differential_datecreate DESC, 	differential_title ASC", "differentials={$company_id}");
                if (!$Read->getResult()):
                    echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Diferenciais da Empresa Cadastrados. Comece Agora Mesmo Cadastrando o Primeiro Diferencial!</span>", E_USER_NOTICE);
                else:
                    foreach ($Read->getResult() as $Differentials):
                        extract($Differentials);
                        $DifferentialImage = (file_exists("../uploads/{$differential_image}") && !is_dir("../uploads/{$differential_image}") ? "uploads/{$differential_image}" : 'admin/_img/no_image.jpg');
                        echo "<div class='single_user_addr js-rel-to' id='{$differential_id}'>
                            <h1 class='icon-list2'>{$differential_title}</h1>
                            <p>" . Check::Words($differential_content, 20) . "</p>
                            <div class='single_user_addr_actions'>
                                <a title='Editar Diferencial' href='dashboard.php?wc=company/differentials&id={$differential_id}' class='post_single_center icon-notext icon-pencil btn_header btn_darkaquablue'></a>
                                <span title='Excluir Diferencial' rel='single_user_addr' class='j_delete_action icon-notext icon-cancel-circle btn_header btn_red' callback='Company' callback_action='delete_differential' id='{$differential_id}'></span>
                            </div>
                        </div>";    
                    endforeach;
                endif;
                ?>
            </div>
            <div class="clear"></div>
        </article>
        
        <article class="wc_tab_target ds_none" id="faq">
            <div class="panel_header darkaquablue">
                <span>
                    <a href="#" title='Nova FAQ' class="btn_header btn_aquablue icon-plus icon-notext j_create_faq_modal" data-modal=".js-faq"></a>    
                </span>
                <h2 class="icon-info">FAQ</h2>
            </div>
            <div class="panel" id="company-faq">
                <?php
                $Read->ExeRead(DB_COMPANY_FAQ, "WHERE company_id = :company ORDER BY faq_datecreate DESC, faq_title ASC", "company={$company_id}");
                if (!$Read->getResult()):
                    echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Perguntas Cadastradas Sobre a Empresa. Comece Agora Mesmo Cadastrando a Primeira Pergunta!</span>", E_USER_NOTICE);
                else:
                    foreach ($Read->getResult() as $FAQ):
                        extract($FAQ);
                        echo "<div class='single_user_addr js-rel-to' id='{$faq_id}'>
                            <h1 class='icon-info'>{$faq_title}</h1>
                            <p>" . Check::Words($faq_content, 20) . "</p>
                            <div class='single_user_addr_actions'>
                                <span title='Editar FAQ' class='btn_header btn_darkaquablue icon-notext icon-pencil j_edit_faq_modal' callback='Company' callback_action='edit' id='{$faq_id}'></span>
                                <span title='Excluir FAQ' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Company' callback_action='delete_faq' id='{$faq_id}'></span>
                                </div>
                            </div>";    
                    endforeach;
                endif;
                ?>
            </div>
            <div class="clear"></div>
            
            <!-- MODAL DE FAQ DA EMPRESA -->
            <div class="bs_ajax_modal js-faq" style="display: none;">
                <div class="bs_ajax_modal_box">
                    <p class="bs_ajax_modal_title aquablue"><span class="icon-info">Cadastrar FAQ</span></p>
                    <span title="Fechar" class="bs_ajax_modal_close icon-cross icon-notext j_close_modal" data-modal=".js-faq"></span>
                    <div class="bs_ajax_modal_content scrollbar">
                        <form name="faq_create_modal" action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="callback" value="Company"/>
                            <input type="hidden" name="callback_action" value="create_faq"/>
                            <input type="hidden" name="company_id" value="<?= $CompanyId; ?>"/>
                            <input type="hidden" name="faq_id" value=""/>
                            
                            <div class="label_100">
                                <label class="label">
                                    <span class="legend">Pergunta:</span>
                                    <input style="font-size: 1em;" type="text" name="faq_title" value="" placeholder="Informe a Pergunta:" required/>
                                </label>
                                
                                <label class="label">
                                    <span class="legend">Resposta:</span>
                                    <input style="font-size: 1em;" type="text" name="faq_content" value="" placeholder="Informe a Resposta:" required/>
                                </label>
                            </div>    
                                
                            <div class="wc_actions" style="text-align: right">
                                <button title="ENVIAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ENVIAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                            </div>
                        </form>
                    </div>
                            
                    <div class="bs_ajax_modal_footer">
                        <p>Cadastre a Pergunta e a Resposta Para Que Apareça Como FAQ Em Seu Site!</p>
                    </div>    
                    <div class="clear"></div>
                </div>
            </div>
            <!-- FECHA MODAL DE FAQ DA EMPRESA -->
            
        </article>
    </div>
    
    <div class="box box30">
        <div class="panel_header aquablue">
            <h2 class="icon-image">Imagem da Empresa</h2>
        </div>
        <?php
        $Image = (file_exists("../uploads/{$company_image}") && !is_dir("../uploads/{$company_image}") ? "uploads/{$company_image}" : 'admin/_img/no_image.jpg');
        ?>
        <img class="company_image" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" alt="" title=""/>
        
        <div class="box_conf_menu no_icon" style="margin-top: 0;">
            <div class="panel">
                <a class='conf_menu wc_tab wc_active' href='#office'><span class="icon-office">A Empresa</span></a>
                <a class='conf_menu wc_tab' href='#blocks'><span class="icon-stack">Blocos</span></a>
                <a class='conf_menu wc_tab' href='#gallery'><span class="icon-images">Galeria de Imagens</span></a>
                <a class='conf_menu wc_tab' href='#mission'><span class="icon-eye">Missão, Visão e Valores</span></a>
                <a class='conf_menu wc_tab' href='#differentials'><span class="icon-list2">Diferenciais</span></a>
                <a class='conf_menu wc_tab' href='#faq'><span class="icon-info">FAQ</span></a>
            </div>    
        </div>
    </div>
</div>