<?php
$AdminLevel = LEVEL_WC_SPECIALTIES;
if (!APP_SPECIALTIES || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
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

$SpecialtieId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($SpecialtieId):
    $Read->ExeRead(DB_SPECIALTIES, "WHERE specialtie_id = :id", "id={$SpecialtieId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = "<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Uma Especialidade Que Não Existe ou Que Foi Removida Recentemente!";
        header('Location: dashboard.php?wc=especialidades/home');
    endif;
else:
    $SpecialtieCreate = ['specialtie_datecreate' => date('Y-m-d H:i:s')];
    $Create->ExeCreate(DB_SPECIALTIES, $SpecialtieCreate);
    header('Location: dashboard.php?wc=especialidades/create&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-lab">Especialidades</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=especialidades/home">Especialidades</a>
        </p>
    </div>
    
    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;">
        <a class="btn_header btn_darkaquablue icon-undo2" title="Voltar" href="dashboard.php?wc=especialidades/home">Voltar</a>
    </div>
</header>

<div class="dashboard_content">
    <div class="box box70">
        <article class="wc_tab_target wc_active" id="specialtie">
             <form class="auto_save" name="specialtie_info" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Specialties"/>
                <input type="hidden" name="callback_action" value="manager"/>
                <input type="hidden" name="specialtie_id" value="<?= $SpecialtieId; ?>"/>
            
                <div class="panel_header darkaquablue">
                    <h2 class="icon-lab">Dados Sobre a Especialidade</h2>
                </div>
                <div class="panel">
                    <label class="label">
                        <span class="legend">Imagem da Especialidade:</span>
                        <input type="file" class="wc_loadimage" name="specialtie_image"/>
                    </label>
                    
                    <label class="label">
                        <span class="legend">Título da Especialidade:</span>
                        <input name="specialtie_title" style="font-size: 1.3em;" value="<?= $specialtie_title; ?>" placeholder="Título da Especialidade" required/>
                    </label>
                    
                    <div class="box box30">
                        <label class="label">
                            <span class="legend">Tipo do Ícone:</span>
                            <select name="specialtie_icon_type" class="j_icon" required>
                                <option selected disabled value="">Selecione o Tipo:</option>
                                <option value="1" <?= ($specialtie_icon_type == 1 ? 'selected="selected"' : ''); ?>>Imagem</option>
                                <option value="2" <?= ($specialtie_icon_type == 2 ? 'selected="selected"' : ''); ?>>Texto</option>
                            </select>
                        </label>
                        
                        <div class="j_icon_image">    
                            <?php
                            $Image = (file_exists("../uploads/{$specialtie_icon}") && !is_dir("../uploads/{$specialtie_icon}") ? "uploads/{$specialtie_icon}" : 'admin/_img/no_avatar.jpg');
                            ?>
                            <label class="label" style="margin-bottom: 10px;">
                                <span class="legend">Ícone da Especialidade:</span>
                            </label>
                            
                            <img class="specialtie_icon" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" alt="" title=""/>
                            
                            <label class="label" style="margin-top: 10px;">
                                <input type="file" class="wc_loadimage" name="specialtie_icon"/>
                            </label>     
                        </div>    
                        
                        <div class="j_icon_text">        
                            <label class='label'>
                                <span class='legend'>Ícone da Especialidade:</span>
                                <input value='<?= $specialtie_icon_text; ?>' type='text' name='specialtie_icon_text' placeholder='Informe o Ícone' />
                            </label>
                        </div>
                    </div>
                    
                    <div class="box box70">
                        <label class="label">
                            <span class="legend">Descrição da Especialidade:</span>
                            <textarea class="work_mce" rows="50" name="specialtie_content"><?= $specialtie_content; ?></textarea>
                        </label>    
                    </div>
                    
                    <div class="wc_actions" style="text-align: center">
                        <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                        
                        <div class="switch__container">
                          <input value='1' id="switch-shadow" class="switch switch--shadow" type="checkbox" name='specialtie_status' <?= ($specialtie_status == 1 ? 'checked' : ''); ?>>
                          <label for="switch-shadow"></label>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>  
            </form>    
        </article>
        
        <article class="wc_tab_target ds_none" id="before-after">
            <form class="auto_save" name="specialtie_before_after" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Specialties"/>
                <input type="hidden" name="callback_action" value="before_after"/>
                <input type="hidden" name="specialtie_id" value="<?= $SpecialtieId; ?>"/>
            
                <div class="panel_header darkaquablue">
                    <h2 class="icon-image">Antes e Depois</h2>
                </div>
                <div class="panel">
                    <div class="box box50">
                        <?php
                        $Image = (file_exists("../uploads/{$specialtie_treatment_before}") && !is_dir("../uploads/{$specialtie_treatment_before}") ? "uploads/{$specialtie_treatment_before}" : 'admin/_img/no_avatar.jpg');
                        ?>
                        <label class="label">
                            <span class="legend border_bottom_title">Antes do Tratamento:</span>
                        </label>    
                        <img class="specialtie_treatment_before" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" alt="" title=""/>
                        
                        <label class="label">
                            <input type="file" class="wc_loadimage" name="specialtie_treatment_before"/>
                        </label>        
                    </div>
                    
                    <div class="box box50">
                        <?php
                        $Image = (file_exists("../uploads/{$specialtie_treatment_after}") && !is_dir("../uploads/{$specialtie_treatment_after}") ? "uploads/{$specialtie_treatment_after}" : 'admin/_img/no_avatar.jpg');
                        ?>
                        <label class="label">
                            <span class="legend border_bottom_title">Depois do Tratamento:</span>
                        </label> 
                        
                        <img class="specialtie_treatment_after" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" alt="" title=""/>
                        
                        <label class="label">
                            <input type="file" class="wc_loadimage" name="specialtie_treatment_after"/>
                        </label>        
                    </div>
                    
                    <div class="wc_actions" style="text-align: center">
                        <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                    </div>
                    <div class="clear"></div>
                </div>  
            </form>    
        </article>
        
        <article class="wc_tab_target ds_none" id="benefits">
            <div class="panel_header darkaquablue">
                <span>
                    <a title="Novo Benefício" href="dashboard.php?wc=especialidades/benefits&especialidades=<?= $specialtie_id; ?>" class="btn_header btn_aquablue icon-plus icon-notext"></a>    
                </span>
                <h2 class="icon-lab">Benefícios</h2>
            </div>
            <div class="panel">
                <?php
                $Read->ExeRead(DB_SPECIALTIES_BENEFITS, "WHERE specialtie_id = :benefits ORDER BY specialtie_benefits_datecreate DESC, specialtie_benefits_title ASC", "benefits={$specialtie_id}");
                if (!$Read->getResult()):
                    echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Benefícios da Especialidade Cadastrados. Comece Agora Mesmo Cadastrando o Primeiro Benefício!</span>", E_USER_NOTICE);
                else:
                    foreach ($Read->getResult() as $Benefits):
                        extract($Benefits);
                        $BenefitsImage = (file_exists("../uploads/{$specialtie_benefits_image}") && !is_dir("../uploads/{$specialtie_benefits_image}") ? "uploads/{$specialtie_benefits_image}" : 'admin/_img/no_image.jpg');
                        echo "<div class='single_user_addr js-rel-to' id='{$specialtie_benefits_id}'>
                            <h1 class='icon-list2'>{$specialtie_benefits_title}</h1>
                            <p>" . Check::Words($specialtie_benefits_content, 20) . "</p>
                            <div class='single_user_addr_actions'>
                                <a title='Editar Benefício' href='dashboard.php?wc=especialidades/benefits&id={$specialtie_benefits_id}' class='post_single_center icon-notext icon-pencil btn_header btn_darkaquablue'></a>
                                <span title='Excluir Benefício' rel='single_user_addr' class='j_delete_action icon-notext icon-bin btn_header btn_red' callback='Specialties' callback_action='delete_benefits' id='{$specialtie_benefits_id}'></span>
                            </div>
                        </div>";    
                    endforeach;
                endif;
                ?>
            </div>
            <div class="clear"></div>
        </article>
        
        <article class="wc_tab_target ds_none" id="procedures">
            <div class="panel_header darkaquablue">
                <span>
                    <a href="#" title='Novo Procedimento' class="btn_header btn_aquablue icon-plus icon-notext j_create_procedure_modal" data-modal=".js-procedure"></a>    
                </span>
                <h2 class="icon-aid-kit">Procedimentos</h2>
            </div>
            <div class="panel" id="specialtie-procedure">
                <?php
                $Read->ExeRead(DB_SPECIALTIES_PROCEDURES, "WHERE specialtie_id = :procedure ORDER BY specialtie_procedure_datecreate DESC, specialtie_procedure_title ASC", "procedure={$specialtie_id}");
                if (!$Read->getResult()):
                    echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Procedimentos da Especialidade Cadastrados. Comece Agora Mesmo Cadastrando o Primeiro Procedimento!</span>", E_USER_NOTICE);
                else:
                    foreach ($Read->getResult() as $Procedures):
                        extract($Procedures);
                        echo "<div class='single_user_addr js-rel-to' id='{$specialtie_procedure_id}'>
                            <h1 class='icon-list2'>{$specialtie_procedure_title}</h1>
                            <p class='icon-coin-dollar'>R$" . number_format($specialtie_procedure_price, 2, ',', '.') . "</p>
                            <div class='single_user_addr_actions'>
                                <span title='Editar Procedimento' class='btn_header btn_darkaquablue icon-notext icon-pencil j_edit_procedure_modal' callback='Specialties' callback_action='edit_procedure' id='{$specialtie_procedure_id}'></span>
                                <span title='Excluir Procedimento' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Specialties' callback_action='delete_procedure' id='{$specialtie_procedure_id}' data-id='{$specialtie_procedure_id}'></span>
                                </div>
                            </div>";    
                    endforeach;
                endif;
                ?>
            </div>
            <div class="clear"></div>
            
            <!-- MODAL DE PROCEDIMENTO DA ESPECIALIDADE -->
            <div class="bs_ajax_modal js-procedure" style="display: none;">
                <div class="bs_ajax_modal_box">
                    <p class="bs_ajax_modal_title aquablue"><span class="icon-aid-kit">Cadastrar Procedimento</span></p>
                    <span title="Fechar" class="bs_ajax_modal_close icon-cross icon-notext j_close_modal" data-modal=".js-procedure"></span>
                    <div class="bs_ajax_modal_content scrollbar">
                        <form name="procedure_create_modal" action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="callback" value="Specialties"/>
                            <input type="hidden" name="callback_action" value="create_procedure"/>
                            <input type="hidden" name="specialtie_id" value="<?= $SpecialtieId; ?>"/>
                            <input type="hidden" name="specialtie_procedure_id" value=""/>
                            
                            <div class="label_100">
                                <label class="label">
                                    <span class="legend">Nome do Procedimento:</span>
                                    <input style="font-size: 1em;" type="text" name="specialtie_procedure_title" value="" placeholder="Nome do Procedimento" required/>
                                </label>
                                
                                <label class="label">
                                    <span class="legend">Valor do Procedimento:</span>
                                    <input style="font-size: 1em;" type="text" name="specialtie_procedure_price" class="mask-money" value="" placeholder="Valor do Procedimento" required/>
                                </label>
                            </div>    
                                
                            <div class="wc_actions" style="text-align: right">
                                <button title="ENVIAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ENVIAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                            </div>
                        </form>
                    </div>
                            
                    <div class="bs_ajax_modal_footer">
                        <p>Cadastre o Procedimento da Especialidade Para Que Apareça Em Seu Site!</p>
                    </div>    
                    <div class="clear"></div>
                </div>
            </div>
            <!-- FECHA MODAL DE PROCEDIMENTO DA ESPECIALIDADE -->
            
        </article>
    
        <article class="wc_tab_target ds_none" id="doctors">
            <div class="panel_header darkaquablue">
                <span>
                    <a href="#" title='Novo Médico' class="btn_header btn_aquablue icon-plus icon-notext j_create_doctor_modal" data-modal=".js-doctors"></a>    
                </span>
                <h2 class="icon-briefcase">Médicos</h2>
            </div>
            <div class="panel" id="specialtie-doctor">
                <?php
                $Read->FullRead(
                "SELECT "
                . "s.specialtie_id, "
                . "s.doctor_id, "
                . "s.specialtie_doctor_id, "
                . "s.specialtie_doctor_datecreate, "
                . "d.doctor_name, "
                . "d.doctor_email, "
                . "d.doctor_cover, "
                . "d.doctor_number_advice "
                . "FROM " . DB_SPECIALTIES_DOCTORS . " s "
                . "INNER JOIN " . DB_DOCTORS . " d ON d.doctor_id = s.doctor_id "
                . "WHERE s.specialtie_id = :specialtie "
                . "ORDER BY specialtie_doctor_datecreate DESC", "specialtie={$specialtie_id}}"
                );
                if (!$Read->getResult()):
                    echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Médicos Cadastrados Para a Especialidade. Comece Agora Mesmo Cadastrando o Primeiro Médico!</span>", E_USER_NOTICE);
                else:
                    foreach ($Read->getResult() as $Doctors):
                        extract($Doctors);
                        $DoctorCover = "../uploads/{$doctor_cover}";
                        $doctor_cover = (file_exists($DoctorCover) && !is_dir($DoctorCover) ? "uploads/{$doctor_cover}" : 'admin/_img/no_avatar.jpg');
                        echo "<article class='single_user box box33 al_center js-rel-to' id='{$specialtie_doctor_id}' >
                            <div class='box_content wc_normalize_height'>
                                <img alt='Este é {$doctor_name}' title='Este é {$doctor_name}' src='../tim.php?src={$doctor_cover}&w=400&h=400'/>
                                <h1>{$doctor_name}</h1>
                                <div class='m_top'></div>  
                                <p class='info icon-clipboard'>CRO: " . $doctor_number_advice . "</p>
                                <p class='info icon-envelop'>" . $doctor_email . "</p>
                            </div>
                            <div class='single_user_actions'>
                                <a title='Editar Médico' class='btn_header btn_darkaquablue icon-pencil icon-notext' href='dashboard.php?wc=medicos/create&id={$doctor_id}'></a>
                                <span title='Excluir Médico' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Specialties' callback_action='delete_doctor' id='{$specialtie_doctor_id}'></span>
                            </div>
                        </article>";
                    endforeach;
                endif;
                ?>
            </div>
            <div class="clear"></div>
            
            <!-- MODAL DE MÉDICOS DA ESPECIALIDADE -->
            <div class="bs_ajax_modal js-doctors" style="display: none;">
                <div class="bs_ajax_modal_box">
                    <p class="bs_ajax_modal_title aquablue"><span class="icon-briefcase">Cadastrar Médico</span></p>
                    <span title="Fechar" class="bs_ajax_modal_close icon-cross icon-notext j_close_modal" data-modal=".js-doctors"></span>
                    <div class="bs_ajax_modal_content scrollbar">
                        <form name="doctor_create_modal" action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="callback" value="Specialties"/>
                            <input type="hidden" name="callback_action" value="create_doctor"/>
                            <input type="hidden" name="specialtie_id" value="<?= $SpecialtieId; ?>"/>
                            <input type="hidden" name="specialtie_doctor_id" value=""/>
                            
                            <div class="label_100"> 
                                <label class="label">
                                    <span class="legend">Médico:</span>
                                    <select name="doctor_id" required>
                                        <option value="" disabled="disabled" selected="selected">Selecione Um Médico:</option>
                                            <?php
                                            $Read->FullRead("SELECT doctor_id, doctor_name FROM " . DB_DOCTORS);
                                            if (!$Read->getResult()):
                                                echo '<option value="" disabled="disabled">Não Existem Médicos Cadastrados!</option>';
                                            else:
                                                foreach ($Read->getResult() as $Dentist):
                                                    echo "<option";
                                                    echo " value='{$Dentist['doctor_id']}'>{$Dentist['doctor_name']}</option>";
                                                endforeach;
                                            endif;
                                            ?>
                                    </select>
                                </label>    
                            </div>    
                                
                            <div class="m_top"></div>    
                            <div class="wc_actions" style="text-align: right">
                                <button title="ENVIAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ENVIAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                            </div>
                        </form>
                    </div>
                            
                    <div class="bs_ajax_modal_footer">
                        <p>Cadastre o Médico da Especialidade Para Que Apareça Em Seu Site!</p>
                    </div>    
                    <div class="clear"></div>
                </div>
            </div>
            <!-- FECHA MODAL DE MÉDICOS DA ESPECIALIDADE -->
            
        </article>
    </div>
    
    <div class="box box30">
        <div class="panel_header aquablue">
            <h2 class="icon-image">Imagem da Especialidade</h2>
        </div>
        <?php
        $Image = (file_exists("../uploads/{$specialtie_image}") && !is_dir("../uploads/{$specialtie_image}") ? "uploads/{$specialtie_image}" : 'admin/_img/no_avatar.jpg');
        ?>
        <img class="specialtie_image" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" alt="" title=""/>
        
        <div class="box_conf_menu no_icon" style="margin-top: 0;">
            <div class="panel">
                <a class='conf_menu wc_tab wc_active' href='#specialtie'><span class="icon-lab">Especialidade</span></a>
                <a class='conf_menu wc_tab' href='#before-after'><span class="icon-image">Antes e Depois</span></a>
                <a class='conf_menu wc_tab' href='#benefits'><span class="icon-list2">Benefícios</span></a>
                <a class='conf_menu wc_tab' href='#procedures'><span class="icon-aid-kit">Procedimentos</span></a>
                <a class='conf_menu wc_tab' href='#doctors'><span class="icon-briefcase">Médicos</span></a>
            </div>    
        </div>
    </div>
</div>