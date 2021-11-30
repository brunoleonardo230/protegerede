<?php
$AdminLevel = LEVEL_WC_USERS;
if (!APP_USERS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
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

$UserId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($UserId):
    $Read->ExeRead(DB_USERS, "WHERE user_id = :id", "id={$UserId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);

        if ($user_level > $_SESSION['userLogin']['user_level']):
            $_SESSION['trigger_controll'] = "<b>OPSSS {$Admin['user_name']}</b>. Por Questões De Segurança, é Restrito o Acesso a Usuário Com Nível de Acesso Maior Que o Seu!";
            header('Location: dashboard.php?wc=users/home');
            exit;
        endif;
    else:
        $_SESSION['trigger_controll'] = "<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Um Usuário Que Não Existe ou Que Foi Removido Recentemente!";
        header('Location: dashboard.php?wc=users/home');
        exit;
    endif;
else:
    $CreateUserDefault = [
        "user_registration" => date('Y-m-d H:i:s'),
        "user_level" => 1
    ];
    $Create->ExeCreate(DB_USERS, $CreateUserDefault);
    header("Location: dashboard.php?wc=users/create&id={$Create->getResult()}");
    exit;
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-user-plus">Novo Usuário</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=users/home">Usuários</a>
            <span class="crumb">/</span>
            Novo Usuário
        </p>
    </div>

    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;" id="<?= $UserId; ?>">
        <span title="Deletar Usuário" rel='dashboard_header_search' class='j_delete_action icon-warning btn_header btn_red' callback='Users' callback_action='delete' id='<?= $UserId; ?>'>Deletar Usuário</span>
    </div>
</header>

<div class="dashboard_content dashboard_users">
    <div class="box box70">
        <article class="wc_tab_target wc_active" id="profile">
            <div class="panel_header darkaquablue">
                <h2 class="icon-user-plus">Dados de <?= $user_name; ?></h2>
            </div>
            
            <div class="panel">
                <form class="auto_save" class="j_tab_home tab_create" name="user_manager" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="callback" value="Users"/>
                    <input type="hidden" name="callback_action" value="manager"/>
                    <input type="hidden" name="user_id" value="<?= $UserId; ?>"/>
                    
                    <label class="label">
                        <span class="legend">Foto (<?= AVATAR_W; ?>x<?= AVATAR_H; ?>px, JPG ou PNG):</span>
                        <input type="file" name="user_thumb" class="wc_loadimage" />
                    </label>
                    
                    <label class="label">
                        <span class="legend">Primeiro Nome:</span>
                        <input value="<?= $user_name; ?>" type="text" name="user_name" placeholder="Primeiro Nome" required />
                    </label>

                    <label class="label">
                        <span class="legend">Sobrenome:</span>
                        <input value="<?= $user_lastname; ?>" type="text" name="user_lastname" placeholder="Sobrenome" required />
                    </label>
                    
                    <label class="label">
                        <span class="legend">Sobre:</span>
                        <textarea class="work_mce" rows="30" name="user_content"><?= $user_content; ?></textarea>
                    </label>
                    
                    <label class="label">
                        <span class="legend">CPF:</span>
                        <input value="<?= $user_document; ?>" type="text" name="user_document" class="formCpf" placeholder="Informe o CPF" />
                    </label>

                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Telefone:</span>
                            <input value="<?= $user_telephone; ?>" class="formPhone" type="text" name="user_telephone" placeholder="Informe o Telefone" />
                        </label>

                        <label class="label">
                            <span class="legend">Celular:</span>
                            <input value="<?= $user_cell; ?>" class="formPhone" type="text" name="user_cell" placeholder="Informe o Celular" />
                        </label>
                    </div>

                    <label class="label">
                        <span class="legend">E-mail:</span>
                        <input value="<?= $user_email; ?>" type="email" name="user_email" placeholder="Informe o E-mail" required />
                    </label>

                    <label class="label">
                        <span class="legend">Senha:</span>
                        <input value="" type="password" name="user_password" placeholder="Informe a Senha" />
                    </label>
                    
                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Facebook:</span>
                            <input value="<?= $user_facebook; ?>" type="text" name="user_facebook" placeholder="Informe o Facebook" />
                        </label>
                        
                        <label class="label">
                            <span class="legend">Instagram:</span>
                            <input value="<?= $user_instagram; ?>" type="text" name="user_instagram" placeholder="Informe o Instagram" />
                        </label>
                    </div>    

                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Linkedin:</span>
                            <input value="<?= $user_linkedin; ?>" type="text" name="user_linkedin" placeholder="Informe o Linkedin" />
                        </label>
                        
                        <label class="label">
                            <span class="legend">Twitter:</span>
                            <input value="<?= $user_twitter; ?>" type="text" name="user_twitter" placeholder="Informe o Twitter" />
                        </label>
                    </div>
                    
                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Google:</span>
                            <input value="<?= $user_google; ?>" type="text" name="user_google" placeholder="Informe o Google" />
                        </label>
                        
                        <label class="label">
                            <span class="legend">Youtube:</span>
                            <input value="<?= $user_youtube; ?>" type="text" name="user_youtube" placeholder="Informe o Youtube" />
                        </label>
                    </div>

                    <?php if ($user_level < 10 || $_SESSION['userLogin']['user_level'] == 10): ?>
                        <div class="label_50">
                            <label class="label">
                                <span class="legend">Nível de Acesso:</span>
                                <select name="user_level" required>
                                    <option selected disabled value="">Selecione o Nível de Acesso:</option>
                                    <?php
                                    $NivelDeAcesso = getWcLevel();
                                    foreach ($NivelDeAcesso as $Nivel => $Desc):
                                        if ($Nivel <= $_SESSION['userLogin']['user_level']):
                                            echo "<option";
                                            if ($Nivel == $user_level):
                                                echo " selected='selected'";
                                            endif;
                                            echo " value='{$Nivel}'>{$Desc}</option>";
                                        endif;
                                    endforeach;
                                    ?>
                                </select>
                            </label>

                            <label class="label">
                                <span class="legend">Gênero do Usuário:</span>
                                <select name="user_genre" required>
                                    <option selected disabled value="">Selecione o Gênero do Usuário:</option>
                                    <option value="1" <?= ($user_genre == 1 ? 'selected="selected"' : ''); ?>>Masculino</option>
                                    <option value="2" <?= ($user_genre == 2 ? 'selected="selected"' : ''); ?>>Feminino</option>
                                </select>
                            </label>
                        </div>
                    <?php else: ?>
                        <label class="label">
                            <span class="legend">Gênero do Usuário:</span>
                            <select name="user_genre" required>
                                <option selected disabled value="">Selecione o Gênero do Usuário:</option>
                                <option value="1" <?= ($user_genre == 1 ? 'selected="selected"' : ''); ?>>Masculino</option>
                                <option value="2" <?= ($user_genre == 2 ? 'selected="selected"' : ''); ?>>Feminino</option>
                            </select>
                        </label>
                    <?php endif; ?>
                    <div class="clear"></div>

                    <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue fl_right icon-share" style="margin-left: 5px;">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                    <div class="clear"></div>
                </form>
            </div>
        </article>

        <?php if (APP_ORDERS): ?>
            <div class="j_tab_index tab_orders box box100 wc_tab_target" id="orders" style="padding: 0; margin: 0; display: none;">
                <div class="panel_header darkaquablue">
                    <h2 class="icon-cart">Pedidos de <?= $user_name; ?></h2>
                </div>
                <div class="panel">
                    <?php
                    $Read->ExeRead(DB_ORDERS, "WHERE user_id = :user ORDER BY order_status DESC, order_date DESC", "user={$user_id}");
                    if (!$Read->getResult()):
                        echo "<div class='trigger trigger_info trigger_none'><span class='al_center icon-info'>Olá {$user_name},  Ainda Não Possui Pedidos Efetuados!</span></div>";
                    else:
                        foreach ($Read->getResult() as $Order):
                            echo "<div class='single_user_order box box50' style='margin: 0;'>
                                    <h1 class='icon-cart'>" . str_pad($Order['order_id'], 7, 0, STR_PAD_LEFT) . "</h1>
                                    <p class='icon-calendar'>" . date('d/m/Y H\hi', strtotime($Order['order_date'])) . "</p>
                                    <p>R$ " . number_format($Order['order_price'], '2', ',', '.') . " via " . getOrderPayment($Order['order_payment']) . "</p>
                                    <p>" . getOrderStatus($Order['order_status']) . "</p>
                                    <a class='icon-redo2' href='dashboard.php?wc=orders/order&id={$Order['order_id']}' title='Detalhes do Pedido'>Detalhes do Pedido</a>
                                </div>";
                        endforeach;
                    endif;
                    ?>
                    <div class="clear"></div>
                </div>
            </div>
        <?php endif; ?>

        <article class="box box100 wc_tab_target" id="address" style="padding: 0; margin: 0; display: none;">
            <div class="panel_header darkaquablue">
                <span>
                    <a href="dashboard.php?wc=users/address&user=<?= $user_id; ?>" class="btn_header btn_aquablue icon-plus icon-notext a" title="Novo Endereço"></a>
                </span>
                <h2 class="icon-location">Endereços </h2>
            </div>
            <div class="panel">
                <?php
                //DELETE TRASH ADDR
                if (DB_AUTO_TRASH):
                    $Delete = new Delete;
                    $Delete->ExeDelete(DB_USERS_ADDR, "WHERE user_id = :id AND addr_street IS NULL AND addr_zipcode IS NULL", "id={$user_id}");
                endif;

                $Read->ExeRead(DB_USERS_ADDR, "WHERE user_id = :user ORDER BY addr_key DESC, addr_name ASC", "user={$user_id}");
                if (!$Read->getResult()):
                    echo "<div class='trigger trigger_info trigger_none al_center'>{$user_name} Ainda Não Possui Endereços de Entrega Cadastrados!</span></div><div class='clear'></div>";
                else:
                    foreach ($Read->getResult() as $Addr):
                        $Addr['addr_complement'] = ($Addr['addr_complement'] ? " - {$Addr['addr_complement']}" : null);
                        $Primary = ($Addr['addr_key'] ? ' - Principal' : null);
                        echo "<div class='single_user_addr' id='{$Addr['addr_id']}'>
                            <h1 class='icon-location'>{$Addr['addr_name']}{$Primary}</h1>
                            <p>{$Addr['addr_street']}, {$Addr['addr_number']}{$Addr['addr_complement']}</p>
                            <p>B. {$Addr['addr_district']}, {$Addr['addr_city']}/{$Addr['addr_state']}, {$Addr['addr_country']}</p>
                            <p>CEP: {$Addr['addr_zipcode']}</p>

                            <div class='single_user_addr_actions'>
                                <a title='Editar Endereço' href='dashboard.php?wc=users/address&id={$Addr['addr_id']}' class='post_single_center icon-notext icon-pencil btn_header btn_darkaquablue'></a>
                                <span title='Excluir Endereço' rel='single_user_addr' class='j_delete_action icon-notext icon-cancel-circle btn_header btn_red' callback='Users' callback_action='addr_delete' id='{$Addr['addr_id']}'></span>
                            </div>
                        </div>";
                    endforeach;
                endif;
                ?>
                <div class="clear"></div>
            </div>
        </article>
    </div>

    <div class="box box30">
        <div class="panel_header aquablue">
            <h2 class="icon-image">Foto do Usuário</h2>
        </div>
        <?php
        $Image = (file_exists("../uploads/{$user_thumb}") && !is_dir("../uploads/{$user_thumb}") ? "uploads/{$user_thumb}" : 'admin/_img/no_avatar.jpg');
        ?>
        
        <div class="box_image">
            <div class="box_image_img">
                <img class="user_thumn" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=<?= AVATAR_W; ?>&h=<?= AVATAR_H; ?>" alt="<?= $user_name; ?> <?= $user_lastname; ?>" title="<?= $user_name; ?> <?= $user_lastname; ?>"/>
            </div>  
            
            <div class="box_image_info">
                <?= (!empty($user_name) && !empty($user_lastname) ? "<h1 class='icon-user'>" . $user_name . " " . Check::Chars($user_lastname, 12) . "</h1>" : ""); ?>
                <?= (!empty($user_level) ? "<p class='icon-equalizer'>" . getWcLevel($user_level) . "</p>" : ""); ?>
                <?= (!empty($user_email) ? "<p class='icon-envelop'>" . $user_email . "</p>" : ""); ?>
                <?= (!empty($user_cell) ? "<p class='icon-phone'>" . $user_cell . "</p>" : ""); ?>
            </div>
        </div>
        
        <div class="box_conf_menu no_icon" style="margin-top: 0;">
            <div class="panel">
                <a class='conf_menu wc_tab wc_active' href='#profile' title='Perfil'><span class="icon-user">Perfil</span></a>
                <?php if (APP_ORDERS): ?>
                    <a class='conf_menu wc_tab' href='#orders' title='Pedidos'><span class="icon-cart">Pedidos</span></a>
                <?php endif; ?>
                <a class='conf_menu wc_tab' href='#address' title='Endereços'><span class="icon-location">Endereços</span></a>
            </div>
        </div>
    </div>
</div>