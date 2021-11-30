<?php
$AdminLevel = LEVEL_WC_DOCTORS;
if (!APP_DOCTORS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
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

$DoctorId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($DoctorId):
    $Read->ExeRead(DB_DOCTORS, "WHERE doctor_id = :id", "id={$DoctorId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = "<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Um Médico Que Não Existe ou Que Foi Removido Recentemente!";
        header('Location: dashboard.php?wc=medicos/home');
    endif;
else:
    $DoctorCreate = ['doctor_datecreate' => date('Y-m-d H:i:s'), 'doctor_status' => 0];
    $Create->ExeCreate(DB_DOCTORS, $DoctorCreate);
    header('Location: dashboard.php?wc=medicos/create&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-user-tie">Médicos</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=medicos/home">Médicos</a>
        </p>
    </div>
    
    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;">
        <a class="btn_header btn_darkaquablue icon-undo2" title="Voltar" href="dashboard.php?wc=medicos/home">Voltar</a>
    </div>
</header>

<div class="dashboard_content">
    <div class="box box70">
		<form class="auto_save" name="create_doctor" action="" method="post" enctype="multipart/form-data">
			<input type="hidden" name="callback" value="Doctors"/>
			<input type="hidden" name="callback_action" value="manager"/>
			<input type="hidden" name="doctor_id" value="<?= $DoctorId; ?>"/>
		
			<div class="panel_header darkaquablue">
				<h2 class="icon-user-tie">Dados Sobre o Médico</h2>
			</div>
			<div class="panel">
				<label class="label">
					<span class="legend">Foto: (JPG <?= AVATAR_W; ?>x<?= AVATAR_H; ?>px)</span>
					<input type="file" class="wc_loadimage" name="doctor_cover"/>
				</label>
				
				<label class="label">
					<span class="legend">Nome do Médico:</span>
					<input name="doctor_name" style="font-size: 1em;" value="<?= $doctor_name; ?>" placeholder="Informe o Nome do Médico" required/>
				</label>
				
				<label class="label">
					<span class="legend">Especialidade do Médico</span>
					<select name="doctor_specialty" required="">
						<option value="">Selecione a Especialidade</option>
						<?php
						foreach (getSpecialtiesDoctors() as $SpecialtieId => $SpecialtieValue):
							echo "<option " . ($doctor_specialty == $SpecialtieId ? "selected='selected'" : null) . " value='{$SpecialtieId}'>{$SpecialtieValue}</option>";
						endforeach;
						?>
					</select>
				</label>
				
				<div class="label_50">
					<label class="label">
						<span class="legend">Data de Nascimento:</span>
						<input value="<?= (!empty($doctor_datebirth) ? date('d/m/Y', strtotime($doctor_datebirth)) : ''); ?>" class="formDate" type="text" name="doctor_datebirth" placeholder="Informe a Data de Nascimento" />
					</label>

					<label class="label">
						<span class="legend">Gênero do Médico:</span>
						<select name="doctor_genre" required>
							<option selected disabled value="">Selecione o Gênero do Usuário:</option>
							<option value="1" <?= ($doctor_genre == 1 ? 'selected="selected"' : ''); ?>>Masculino</option>
							<option value="2" <?= ($doctor_genre == 2 ? 'selected="selected"' : ''); ?>>Feminino</option>
						</select>
					</label>
				</div>
				
				<label class="label">
					<span class="legend">Biografia do Médico:</span>
					<textarea class="work_mce" rows="30" name="doctor_content"><?= $doctor_content; ?></textarea>
				</label>
				
				<label class="label">
					<span class="legend">Curriculum do Médico:</span>
					<textarea class="work_mce" rows="30" name="doctor_curriculum"><?= $doctor_curriculum; ?></textarea>
				</label>
				
				<div class="clear"></div>
				<h3 class="form_subtitle icon-profile m_botton">Documentos:</h3>
				
				<div class="label_50">
					<label class="label">
						<span class="legend">CPF:</span>
						<input value="<?= $doctor_cpf; ?>" class="formCpf" type="text" name="doctor_cpf" placeholder="Informe o CPF do Médico" />
					</label>

					<label class="label">
						<span class="legend">RG:</span>
						<input value="<?= $doctor_rg; ?>" type="text" name="doctor_rg" placeholder="Informe o RG do Médico" />
					</label>
				</div>
				
				<div class="label_33">
					<label class="label">
						<span class="legend">Número do Conselho:</span>
						<input value="<?= $doctor_number_advice; ?>" type="text" name="doctor_number_advice" placeholder="Informe o Número do Conselho" />
					</label>

					<label class="label">
						<span class="legend">Sigla do Conselho:</span>
						<input value="<?= $doctor_initials_advice; ?>" type="text" name="doctor_initials_advice" placeholder="Informe a Sigla do Conselho" />
					</label>
					
					<label class="label">
						<span class="legend">Estado do Conselho:</span>
						<input value="<?= $doctor_state_advice; ?>" class="formState" type="text" name="doctor_state_advice" placeholder="Informe o Estado do Conselho" />
					</label>
				</div>
				
				<div class="clear"></div>
				<h3 class="form_subtitle icon-phone m_botton">Contatos:</h3>

				<div class="label_50">
					<label class="label">
						<span class="legend">Telefone:</span>
						<input value="<?= $doctor_telephone; ?>" class="formPhone" type="text" name="doctor_telephone" placeholder="Informe o Telefone do Médico" />
					</label>

					<label class="label">
						<span class="legend">Celular:</span>
						<input value="<?= $doctor_cell; ?>" class="formPhone" type="text" name="doctor_cell" placeholder="Informe o Celular do Médico" />
					</label>
				</div>
				
				<label class="label">
					<span class="legend">E-mail:</span>
					<input value="<?= $doctor_email; ?>" type="email" name="doctor_email" placeholder="Informe o E-mail do Médico" />
				</label>
				
				<div class="clear"></div>
				<h3 class="form_subtitle icon-share2 m_botton">Redes Sociais:</h3>
				
				<div class="label_50">
					<label class="label">
						<span class="legend">Facebook:</span>
						<input value="<?= $doctor_facebook; ?>" type="text" name="doctor_facebook" placeholder="Informe o Facebook" />
					</label>
					
					<label class="label">
						<span class="legend">Instagram:</span>
						<input value="<?= $doctor_instagram; ?>" type="text" name="doctor_instagram" placeholder="Informe o Instagram" />
					</label>
				</div>    

				<div class="label_33">
					<label class="label">
						<span class="legend">Linkedin:</span>
						<input value="<?= $doctor_linkedin; ?>" type="text" name="doctor_linkedin" placeholder="Informe o Linkedin" />
					</label>
					
					<label class="label">
						<span class="legend">Twitter:</span>
						<input value="<?= $doctor_twitter; ?>" type="text" name="doctor_twitter" placeholder="Informe o Twitter" />
					</label>

					<label class="label">
						<span class="legend">Youtube:</span>
						<input value="<?= $doctor_youtube; ?>" type="text" name="doctor_youtube" placeholder="Informe o Youtube" />
					</label>
				</div>
				
				<div class="clear"></div>
				<h3 class="form_subtitle icon-location m_botton">Endereço:</h3>
				
				<div class="label_50">
				<label class="label">
					<span class="legend">CEP:</span>
					<input name="doctor_zipcode" value="<?= $doctor_zipcode; ?>" class="formCep wc_getCep" placeholder="Informe o CEP" required/>
				</label>

				<label class="label">
					<span class="legend">Rua:</span>
					<input class="wc_logradouro" name="doctor_street" value="<?= $doctor_street; ?>" placeholder="Informe o Nome da Rua" required/>
				</label>
			</div>

			<div class="label_50">
				<label class="label">
					<span class="legend">Número:</span>
					<input name="doctor_number" value="<?= $doctor_number; ?>" placeholder="Informe o Número" required/>
				</label>

				<label class="label">
					<span class="legend">Complemento:</span>
					<input class="wc_complemento" name="doctor_complement" value="<?= $doctor_complement; ?>" placeholder="Informe o Complemento (Ex: Casa, Apto, Etc)"/>
				</label>
			</div>

			<div class="label_50">
				<label class="label">
					<span class="legend">Bairro:</span>
					<input class="wc_bairro" name="doctor_district" value="<?= $doctor_district; ?>" placeholder="Informe o Bairro" required/>
				</label>

				<label class="label">
					<span class="legend">Cidade:</span>
					<input class="wc_localidade" name="doctor_city" value="<?= $doctor_city; ?>" placeholder="Informe a Cidade" required/>
				</label>
			</div>

			<div class="label_50">
				<label class="label">
					<span class="legend">Estado (UF):</span>
					<input class="wc_uf" name="doctor_state" value="<?= $doctor_state; ?>" maxlength="2" placeholder="Informe o Estado (Ex.: RJ)" required/>
				</label>

				<label class="label">
					<span class="legend">País:</span>
					<input name="doctor_country" value="<?= ($doctor_country ? $doctor_country : 'Brasil'); ?>" required/>
				</label>
			</div>
				
				<div class="m_top">&nbsp;</div>
				<div class="wc_actions" style="text-align: center">
					<button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
					
					<div class="switch__container" style="margin-bottom: 10px;">
					  <input value='1' id="switch-shadow" class="switch switch--shadow" type="checkbox" name='doctor_status' <?= ($doctor_status == 1 ? 'checked' : ''); ?>>
					  <label for="switch-shadow"></label>
					</div>
				</div>
				<div class="clear"></div>
			</div>  
		</form>    
    </div>
    
    <div class="box box30">
        <div class="panel_header aquablue">
            <h2 class="icon-image">Foto do Médico</h2>
        </div>
        <?php
        $Image = (file_exists("../uploads/{$doctor_cover}") && !is_dir("../uploads/{$doctor_cover}") ? "uploads/{$doctor_cover}" : 'admin/_img/no_avatar.jpg');
        ?>
        <div class="box_image">
            <div class="box_image_img">
                <img class="doctor_cover" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=<?= AVATAR_W; ?>&h=<?= AVATAR_H; ?>" alt="<?= $doctor_name; ?>" title="<?= $doctor_name; ?>"/>
            </div>  
            
            <div class="box_image_info">
                <?= (!empty($doctor_name) ? "<h1 class='icon-user-tie'>" . Check::Chars($doctor_name, 20) . "</h1>" : ""); ?>
                <?= (!empty($doctor_email) ? "<p class='icon-envelop'>" . $doctor_email . "</p>" : ""); ?>
                <?= (!empty($doctor_cell) ? "<p class='icon-phone'>" . $doctor_cell . "</p>" : ""); ?>
            </div>
        </div>
    </div>
</div>